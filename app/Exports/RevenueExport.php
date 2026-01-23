<?php

namespace App\Exports;

use App\Models\Payment;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;
use Maatwebsite\Excel\Concerns\WithStyles;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;
use Carbon\Carbon;

class RevenueExport implements FromCollection, WithHeadings, WithMapping, WithStyles, ShouldAutoSize
{
    protected $startDate;
    protected $endDate;

    public function __construct($startDate = null, $endDate = null)
    {
        $this->startDate = $startDate ?: Carbon::now()->startOfMonth()->format('Y-m-d');
        $this->endDate = $endDate ?: Carbon::now()->endOfMonth()->format('Y-m-d');
    }

    public function collection()
    {
        return Payment::with(['booking.room'])
            ->where('status', 'verified')
            ->whereBetween('created_at', [$this->startDate, $this->endDate])
            ->orderBy('created_at', 'desc')
            ->get();
    }

    public function headings(): array
    {
        return [
            'Tanggal Pembayaran',
            'Kode Booking',
            'Nama Tamu',
            'Nama Kamar',
            'Metode Pembayaran',
            'Jumlah Pembayaran',
            'Status',
            'Tanggal Verifikasi',
        ];
    }

    public function map($payment): array
    {
        return [
            $payment->created_at->format('d/m/Y H:i'),
            $payment->booking->booking_code,
            $payment->booking->guest_name,
            $payment->booking->room->name,
            $payment->payment_method === 'bank' ? 'Transfer Bank' : 'E-Wallet',
            'Rp ' . number_format($payment->amount, 0, ',', '.'),
            'Terverifikasi',
            $payment->verified_at ? $payment->verified_at->format('d/m/Y H:i') : '-',
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