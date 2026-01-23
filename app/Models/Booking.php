<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Auth;
use Carbon\Carbon;

class Booking extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'user_id',
        'room_id',
        'booking_code',
        'guest_name',
        'guest_email',
        'guest_phone',
        'check_in',
        'check_out',
        'guests',
        'rooms_count',
        'nights',
        'room_price',
        'total_price',
        'status',
        'payment_method',
        'admin_notes',
        'rejection_reason',
        'cancellation_reason',  
        'cancelled_at',          
        'cancelled_by',
    ];

    protected $casts = [
        'check_in' => 'date',
        'check_out' => 'date',
        'total_price' => 'decimal:2',
        'room_price' => 'decimal:2',
        'guests' => 'integer',
        'rooms_count' => 'integer',
        'nights' => 'integer',
        'cancelled_at' => 'datetime',
    ];

    // ===== RELATIONSHIPS =====

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function room()
    {
        return $this->belongsTo(Room::class)->withTrashed();
    }

    public function payment()
    {
        return $this->hasOne(Payment::class);
    }

    public function review()
    {
        return $this->hasOne(Review::class);
    }

    /**
     * Relationship: Admin yang membatalkan booking
     */
    public function cancelledBy()
    {
        return $this->belongsTo(User::class, 'cancelled_by');
    }

    // ===== ACCESSORS =====

    public function getUserNameAttribute()
    {
        return $this->guest_name;
    }

    public function getUserEmailAttribute()
    {
        return $this->guest_email;
    }

    public function getUserPhoneAttribute()
    {
        return $this->guest_phone;
    }

    public function getStatusLabelAttribute()
{
    return match ($this->status) {
        'pending' => 'Menunggu Pembayaran',
        'waiting_verification' => 'Menunggu Verifikasi',
        'confirmed' => 'Dikonfirmasi',
        'paid' => 'Dibayar',
        'completed' => 'Selesai',
        'cancelled' => 'Dibatalkan',
        default => '-',
    };
}

    // ===== SCOPES =====

    public function scopePending($query)
    {
        return $query->where('status', 'pending');
    }

    public function scopeWaitingVerification($query)
    {
        return $query->where('status', 'waiting_verification');
    }

    public function scopeConfirmed($query)
    {
        return $query->where('status', 'confirmed');
    }

    public function scopeCancelled($query)
    {
        return $query->where('status', 'cancelled');
    }

    /**
     * Scope: Hanya booking yang aktif (tidak di-soft delete)
     */
    public function scopeActive($query)
    {
        return $query->whereNull('deleted_at');
    }

    /**
     * Scope: Hanya booking yang di-soft delete (dibatalkan)
     */
    public function scopeOnlyCancelled($query)
    {
        return $query->onlyTrashed();
    }

    // ===== HELPER METHODS =====

    public function getFormattedTotalAttribute()
    {
        return 'Rp ' . number_format((float) $this->total_price, 0, ',', '.');
    }

    public function getDisplayStatusColorAttribute()
    {
        if ($this->status === 'cancelled') {
            return 'red';
        }

        if (!$this->relationLoaded('payment')) {
            return 'gray';
        }

        if (!$this->payment) {
            return 'yellow';
        }

        return match ($this->payment->status) {
            'pending'  => 'yellow',
            'verified' => 'green',
            'failed'   => 'red',
            default    => 'gray',
        };
    }

    public function getDisplayStatusAttribute()
    {
        // Jika booking dibatalkan
        if ($this->status === 'cancelled') {
            return 'Dibatalkan';
        }

        // Jika belum ada payment
        if (!$this->payment) {
            return 'Menunggu';
        }

        return match ($this->payment->status) {
            'pending'  => 'Menunggu',
            'verified' => 'Terkonfirmasi',
            'failed'   => 'Dibatalkan',
            default    => 'Menunggu',
        };
    }

    // ===== KONFIRMASI & TOLAK METHODS =====

    /**
     * Konfirmasi booking dan update payment status ke verified
     */
    public function confirmBooking($adminId = null)
    {
        DB::beginTransaction();

        try {
            // Update booking status
            $this->status = 'confirmed';
            $this->rejection_reason = null;
            $this->save();

            // Update payment status jika ada
            if ($this->payment) {
                $updateData = [
                    'status' => 'verified',
                    'verified_at' => now(),
                ];
                
                if ($adminId !== null) {
                    $updateData['verified_by'] = $adminId;
                } elseif (Auth::check()) {
                    $updateData['verified_by'] = Auth::id();
                }
                
                $this->payment->update($updateData);
            }

            DB::commit();
            return true;
        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('Error confirming booking: ' . $e->getMessage());
            return false;
        }
    }

    /**
     * Tolak booking dan update payment status ke rejected
     */
    public function rejectBooking($reason, $adminId = null)
    {
        DB::beginTransaction();

        try {
            // Update booking status
            $this->status = 'cancelled';
            $this->rejection_reason = $reason;
            $this->save();

            // Update payment status jika ada
            if ($this->payment) {
                $updateData = [
                    'status' => 'failed',
                    'rejection_reason' => $reason,
                    'rejected_at' => now(),
                ];
                
                if ($adminId !== null) {
                    $updateData['rejected_by'] = $adminId;
                } elseif (Auth::check()) {
                    $updateData['rejected_by'] = Auth::id();
                }
                
                $this->payment->update($updateData);
            }

            DB::commit();
            return true;
        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('Error rejecting booking: ' . $e->getMessage());
            return false;
        }
    }

    // ===== CANCEL & RESTORE METHODS (BARU) =====

    /**
     * Cancel booking (soft delete)
     */
    public function cancelBooking($reason, $adminId = null)
    {
        DB::beginTransaction();

        try {
            // Update booking info
            $this->status = 'cancelled';
            $this->cancellation_reason = $reason;
            $this->cancelled_at = now();
            $this->cancelled_by = $adminId ?? Auth::id();
            $this->save();

            // Soft delete
            $this->delete();

            // Kembalikan stok kamar
            if ($this->room) {
                $this->room->increment('available_rooms', $this->rooms_count);
            }

            // Update payment status jika ada
            if ($this->payment) {
                $this->payment->update([
                    'status' => 'cancelled',
                    'cancelled_at' => now(),
                ]);
            }

            DB::commit();
            Log::info("Booking {$this->booking_code} cancelled by admin {$this->cancelled_by}");
            return true;
        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('Error cancelling booking: ' . $e->getMessage());
            return false;
        }
    }

    /**
     * Restore cancelled booking
     */
    public function restoreBooking()
    {
        DB::beginTransaction();

        try {
            // Restore dari soft delete
            $this->restore();

            // Update status kembali ke confirmed
            $this->status = 'confirmed';
            $this->cancellation_reason = null;
            $this->cancelled_at = null;
            $this->cancelled_by = null;
            $this->save();

            // Kurangi stok kamar lagi
            if ($this->room) {
                $this->room->decrement('available_rooms', $this->rooms_count);
            }

            // Update payment status jika ada
            if ($this->payment) {
                $this->payment->update([
                    'status' => 'verified',
                    'cancelled_at' => null,
                ]);
            }

            DB::commit();
            Log::info("Booking {$this->booking_code} restored");
            return true;
        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('Error restoring booking: ' . $e->getMessage());
            return false;
        }
    }

    /**
     * Cek apakah booking bisa dibatalkan
     */
    public function canBeCancelled()
    {
        // Tidak bisa cancel jika sudah dibatalkan atau sudah check-in
        $nonCancellableStatuses = ['cancelled'];
        
        if (in_array($this->status, $nonCancellableStatuses)) {
            return false;
        }

        // Tidak bisa cancel jika sudah lewat check-in date
        if (Carbon::parse($this->check_in)->isPast()) {
            return false;
        }

        return true;
    }

    // ===== EXISTING METHODS =====

    public function canBeConfirmed()
    {
        return $this->status === 'waiting_verification' && $this->hasPaymentProof();
    }

    public function canBeRejected()
    {
        return $this->status === 'waiting_verification';
    }

    public function hasPaymentProof()
    {
        return $this->payment && $this->payment->payment_proof;
    }

    public function getPaymentProofUrlAttribute()
    {
        if ($this->payment && $this->payment->payment_proof) {
            return asset('storage/' . $this->payment->payment_proof);
        }
        return null;
    }

    public function isConfirmed()
    {
        return $this->status === 'confirmed';
    }
}