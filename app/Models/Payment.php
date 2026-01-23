<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Payment extends Model
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     */
    protected $fillable = [
        'booking_id',
        'payment_method',
        'amount',
        'payment_proof',
        'status',
        'rejection_reason',
        'paid_at',
        'verified_at',
        'verified_by',
        'rejected_at',
        'rejected_by',
    ];

    /**
     * The attributes that should be cast.
     */
    protected $casts = [
        'amount' => 'decimal:2',
        'paid_at' => 'datetime',
        'verified_at' => 'datetime',
        'rejected_at' => 'datetime',
    ];

    /**
     * Relationship: Payment belongs to a booking
     */
    public function booking()
    {
        return $this->belongsTo(Booking::class);
    }

    /**
     * Relationship: Payment verified by admin
     */
    public function verifier()
    {
        return $this->belongsTo(User::class, 'verified_by');
    }

    /**
     * Relationship: Payment rejected by admin
     */
    public function rejector()
    {
        return $this->belongsTo(User::class, 'rejected_by');
    }

    /**
     * Scope: Pending payments
     */
    public function scopePending($query)
    {
        return $query->where('status', 'pending');
    }

    /**
     * Scope: Verified payments
     */
    public function scopeVerified($query)
    {
        return $query->where('status', 'verified');
    }

    /**
     * Scope: Rejected payments
     */
    public function scopeRejected($query)
    {
        return $query->where('status', 'failed');
    }

    /**
     * Check if payment has proof
     */
    public function hasProof()
    {
        return !empty($this->payment_proof);
    }

    /**
     * Get payment proof URL
     */
    public function getProofUrlAttribute()
    {
        if ($this->payment_proof) {
            return asset('storage/' . $this->payment_proof);
        }
        return null;
    }

    /**
     * Get formatted amount
     */
    public function getFormattedAmountAttribute()
    {
        return 'Rp ' . number_format((float) $this->amount, 0, ',', '.');
    }

    /**
     * Get payment method label
     */
    public function getPaymentMethodLabelAttribute()
    {
        $methods = [
            'bank_transfer' => 'Transfer Bank',
            'ewallet' => 'E-Wallet',
            'credit_card' => 'Kartu Kredit',
            'cash' => 'Tunai'
        ];

        return $methods[$this->payment_method] ?? 'Transfer Bank';
    }

    /**
     * Get status color for badge
     */
    public function getStatusColorAttribute()
    {
        $colors = [
            'pending' => 'warning',
            'verified' => 'success',
            'failed' => 'danger'
        ];

        return $colors[$this->status] ?? 'secondary';
    }

    /**
     * Get status label
     */
    public function getStatusLabelAttribute()
    {
        $labels = [
            'pending' => 'Menunggu Verifikasi',
            'verified' => 'Sudah Dibayar',
            'failed' => 'Ditolak'
        ];

        return $labels[$this->status] ?? $this->status;
    }

    /**
     * Check if payment can be verified
     */
    public function canBeVerified()
    {
        return $this->status === 'pending' && $this->hasProof();
    }

    /**
     * Check if payment can be rejected
     */
    public function canBeRejected()
    {
        return $this->status === 'pending';
    }
}