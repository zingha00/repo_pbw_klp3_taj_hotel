<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Laporan Kamar</title>
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
            border-left: 4px solid #6f42c1;
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
            color: #6f42c1;
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
        .status {
            padding: 2px 6px;
            border-radius: 3px;
            font-size: 9px;
            font-weight: bold;
        }
        .status-available { background: #d4edda; color: #155724; }
        .status-unavailable { background: #f8d7da; color: #721c24; }
    </style>
</head>
<body>
    <div class="header">
        <h1>Grand Azure Hotel</h1>
        <p>Laporan Performa Kamar</p>
        <p>Periode: {{ \Carbon\Carbon::parse($startDate)->format('d M Y') }} - {{ \Carbon\Carbon::parse($endDate)->format('d M Y') }}</p>
    </div>

    <div class="info-box">
        <h3>Ringkasan Kamar</h3>
        <div class="stats">
            <div class="stat-item">
                <div class="stat-value">{{ $data['rooms']->count() }}</div>
                <div class="stat-label">Total Kamar</div>
            </div>
            <div class="stat-item">
                <div class="stat-value">{{ $data['rooms']->where('status', 'available')->count() }}</div>
                <div class="stat-label">Kamar Tersedia</div>
            </div>
            <div class="stat-item">
                <div class="stat-value">{{ $data['rooms']->sum('bookings_count') }}</div>
                <div class="stat-label">Total Booking</div>
            </div>
            <div class="stat-item">
                <div class="stat-value">Rp {{ number_format($data['rooms']->sum('bookings_sum_total_price'), 0, ',', '.') }}</div>
                <div class="stat-label">Total Revenue</div>
            </div>
        </div>
    </div>

    <table>
        <thead>
            <tr>
                <th>No</th>
                <th>Nama Kamar</th>
                <th>Harga/Malam</th>
                <th>Kapasitas</th>
                <th>Ukuran</th>
                <th>Status</th>
                <th>Total Booking</th>
                <th>Revenue</th>
                <th>Rating</th>
            </tr>
        </thead>
        <tbody>
            @foreach($data['rooms'] as $index => $room)
            <tr>
                <td class="text-center">{{ $index + 1 }}</td>
                <td>{{ $room->name }}</td>
                <td class="text-right">Rp {{ number_format($room->price, 0, ',', '.') }}</td>
                <td class="text-center">{{ $room->capacity }} orang</td>
                <td class="text-center">{{ $room->size }} m²</td>
                <td class="text-center">
                    <span class="status status-{{ $room->status }}">
                        {{ $room->status === 'available' ? 'Tersedia' : 'Tidak Tersedia' }}
                    </span>
                </td>
                <td class="text-center">{{ $room->bookings_count ?: 0 }}</td>
                <td class="text-right">Rp {{ number_format($room->bookings_sum_total_price ?: 0, 0, ',', '.') }}</td>
                <td class="text-center">{{ $room->rating ?: 0 }}/5</td>
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