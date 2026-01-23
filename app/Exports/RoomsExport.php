<?php

namespace App\Exports;

use App\Models\Room;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;
use Maatwebsite\Excel\Concerns\WithStyles;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;
use Carbon\Carbon;

class RoomsExport implements FromCollection, WithHeadings, WithMapping, WithStyles, ShouldAutoSize
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
        return Room::withCount([
            'bookings' => function ($query) {
                $query->whereBetween('created_at', [$this->startDate, $this->endDate])
                      ->where('status', 'confirmed');
            }
        ])
        ->withSum([
            'bookings' => function ($query) {
                $query->whereBetween('created_at', [$this->startDate, $this->endDate])
                      ->where('status', 'confirmed');
            }
        ], 'total_price')
        ->orderBy('bookings_count', 'desc')
        ->get();
    }

    public function headings(): array
    {
        return [
            'Nama Kamar',
            'Harga per Malam',
            'Kapasitas',
            'Ukuran (m²)',
            'Status',
            'Total Booking',
            'Total Revenue',
            'Rating',
            'Jumlah Review',
        ];
    }

    public function map($room): array
    {
        return [
            $room->name,
            'Rp ' . number_format($room->price, 0, ',', '.'),
            $room->capacity . ' orang',
            $room->size . ' m²',
            $room->status === 'available' ? 'Tersedia' : 'Tidak Tersedia',
            $room->bookings_count ?: 0,
            'Rp ' . number_format($room->bookings_sum_total_price ?: 0, 0, ',', '.'),
            $room->rating ?: 0,
            $room->reviews_count ?: 0,
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