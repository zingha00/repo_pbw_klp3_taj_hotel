<?php

namespace App\Exports;

use App\Models\Booking;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;
use Maatwebsite\Excel\Concerns\WithStyles;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;
use Carbon\Carbon;

class BookingsExport implements FromCollection, WithHeadings, WithMapping, WithStyles, ShouldAutoSize
{
    protected $startDate;
    protected $endDate;
    protected $status;

    public function __construct($startDate = null, $endDate = null, $status = null)
    {
        $this->startDate = $startDate ?: Carbon::now()->startOfMonth()->format('Y-m-d');
        $this->endDate = $endDate ?: Carbon::now()->endOfMonth()->format('Y-m-d');
        $this->status = $status;
    }

    public function collection()
    {
        $query = Booking::with(['room'])
            ->whereBetween('created_at', [$this->startDate, $this->endDate]);

        if ($this->status && $this->status !== 'all') {
            $query->where('status', $this->status);
        }

        return $query->orderBy('created_at', 'desc')->get();
    }

    public function headings(): array
    {
        return [
            'Kode Booking',
            'Tanggal Booking',
            'Nama Tamu',
            'Email Tamu',
            'Telepon Tamu',
            'Nama Kamar',
            'Check-in',
            'Check-out',
            'Jumlah Malam',
            'Jumlah Tamu',
            'Total Harga',
            'Status',
            'Metode Pembayaran',
        ];
    }

    public function map($booking): array
    {
        return [
            $booking->booking_code,
            $booking->created_at->format('d/m/Y H:i'),
            $booking->guest_name,
            $booking->guest_email,
            $booking->guest_phone ?: '-',
            $booking->room->name,
            $booking->check_in->format('d/m/Y'),
            $booking->check_out->format('d/m/Y'),
            $booking->nights,
            $booking->guests,
            'Rp ' . number_format($booking->total_price, 0, ',', '.'),
            $booking->status_label,
            $booking->payment_method ? ($booking->payment_method === 'bank' ? 'Transfer Bank' : 'E-Wallet') : '-',
        ];
    }

    public function styles(Worksheet $sheet)
    {
        return [
            // Style the first row as bold text.
            1 => ['font' => ['bold' => true]],
        ];
    }
}