<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Laporan Booking</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            font-size: 12px;
            margin: 0;
            padding: 20px;
        }
        .header {
            text-align: center;
            margin-bottom: 30px;
            border-bottom: 2px solid #333;
            padding-bottom: 10px;
        }
        .header h1 {
            margin: 0;
            color: #333;
            font-size: 24px;
        }
        .header p {
            margin: 5px 0;
            color: #666;
        }
        .info-box {
            background: #f8f9fa;
            padding: 15px;
            margin-bottom: 20px;
            border-left: 4px solid #007bff;
        }
        .info-box h3 {
            margin: 0 0 10px 0;
            color: #333;
        }
        .stats {
            display: flex;
            justify-content: space-between;
            margin-bottom: 20px;
        }
        .stat-item {
            text-align: center;
            flex: 1;
        }
        .stat-value {
            font-size: 18px;
            font-weight: bold;
            color: #007bff;
        }
        .stat-label {
            color: #666;
            font-size: 11px;
        }
        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 20px;
        }
        th, td {
            border: 1px solid #ddd;
            padding: 8px;
            text-align: left;
            font-size: 10px;
        }
        th {
            background-color: #f8f9fa;
            font-weight: bold;
            color: #333;
        }
        .status {
            padding: 2px 6px;
            border-radius: 3px;
            font-size: 9px;
            font-weight: bold;
        }
        .status-pending { background: #fff3cd; color: #856404; }
        .status-waiting { background: #cce5ff; color: #004085; }
        .status-confirmed { background: #d4edda; color: #155724; }
        .status-cancelled { background: #f8d7da; color: #721c24; }
        .footer {
            margin-top: 30px;
            text-align: center;
            font-size: 10px;
            color: #666;
            border-top: 1px solid #ddd;
            padding-top: 10px;
        }
        .text-right { text-align: right; }
        .text-center { text-align: center; }
    </style>
</head>
<body>
    <div class="header">
        <h1>Grand Azure Hotel</h1>
        <p>Laporan Booking</p>
        <p>Periode: {{ \Carbon\Carbon::parse($startDate)->format('d M Y') }} - {{ \Carbon\Carbon::parse($endDate)->format('d M Y') }}</p>
    </div>

    <div class="info-box">
        <h3>Ringkasan Laporan</h3>
        <div class="stats">
            <div class="stat-item">
                <div class="stat-value">{{ number_format($data['total_bookings']) }}</div>
                <div class="stat-label">Total Booking</div>
            </div>
            <div class="stat-item">
                <div class="stat-value">Rp {{ number_format($data['total_revenue'], 0, ',', '.') }}</div>
                <div class="stat-label">Total Revenue</div>
            </div>
            <div class="stat-item">
                <div class="stat-value">{{ $data['bookings']->where('status', 'confirmed')->count() }}</div>
                <div class="stat-label">Booking Selesai</div>
            </div>
            <div class="stat-item">
                <div class="stat-value">{{ $data['bookings']->where('status', 'pending')->count() }}</div>
                <div class="stat-label">Booking Pending</div>
            </div>
        </div>
    </div>

    <table>
        <thead>
            <tr>
                <th>No</th>
                <th>Kode Booking</th>
                <th>Tanggal</th>
                <th>Nama Tamu</th>
                <th>Kamar</th>
                <th>Check-in</th>
                <th>Check-out</th>
                <th>Malam</th>
                <th>Total</th>
                <th>Status</th>
            </tr>
        </thead>
        <tbody>
            @foreach($data['bookings'] as $index => $booking)
            <tr>
                <td class="text-center">{{ $index + 1 }}</td>
                <td>{{ $booking->booking_code }}</td>
                <td>{{ $booking->created_at->format('d/m/Y') }}</td>
                <td>{{ $booking->guest_name }}</td>
                <td>{{ $booking->room->name }}</td>
                <td>{{ $booking->check_in->format('d/m/Y') }}</td>
                <td>{{ $booking->check_out->format('d/m/Y') }}</td>
                <td class="text-center">{{ $booking->nights }}</td>
                <td class="text-right">Rp {{ number_format($booking->total_price, 0, ',', '.') }}</td>
                <td class="text-center">
                    <span class="status status-{{ $booking->status }}">
                        {{ $booking->status_label }}
                    </span>
                </td>
            </tr>
            @endforeach
        </tbody>
    </table>

    <div class="footer">
        <p>Laporan digenerate pada {{ now()->format('d M Y H:i') }} WIB</p>
        <p>Grand Azure Hotel - Sistem Manajemen Hotel</p>
    </div>
</body>
</html>