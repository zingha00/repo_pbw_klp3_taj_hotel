<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Laporan Revenue</title>
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
            border-left: 4px solid #28a745;
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
            color: #28a745;
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
        .amount {
            font-weight: bold;
            color: #28a745;
        }
    </style>
</head>
<body>
    <div class="header">
        <h1>Grand Azure Hotel</h1>
        <p>Laporan Revenue</p>
        <p>Periode: {{ \Carbon\Carbon::parse($startDate)->format('d M Y') }} - {{ \Carbon\Carbon::parse($endDate)->format('d M Y') }}</p>
    </div>

    <div class="info-box">
        <h3>Ringkasan Revenue</h3>
        <div class="stats">
            <div class="stat-item">
                <div class="stat-value">Rp {{ number_format($data['total_revenue'], 0, ',', '.') }}</div>
                <div class="stat-label">Total Revenue</div>
            </div>
            <div class="stat-item">
                <div class="stat-value">{{ number_format($data['total_transactions']) }}</div>
                <div class="stat-label">Total Transaksi</div>
            </div>
            <div class="stat-item">
                <div class="stat-value">Rp {{ $data['total_transactions'] > 0 ? number_format($data['total_revenue'] / $data['total_transactions'], 0, ',', '.') : '0' }}</div>
                <div class="stat-label">Rata-rata per Transaksi</div>
            </div>
        </div>
    </div>

    <table>
        <thead>
            <tr>
                <th>No</th>
                <th>Tanggal</th>
                <th>Kode Booking</th>
                <th>Nama Tamu</th>
                <th>Kamar</th>
                <th>Metode Pembayaran</th>
                <th>Jumlah</th>
                <th>Status</th>
            </tr>
        </thead>
        <tbody>
            @foreach($data['payments'] as $index => $payment)
            <tr>
                <td class="text-center">{{ $index + 1 }}</td>
                <td>{{ $payment->created_at->format('d/m/Y H:i') }}</td>
                <td>{{ $payment->booking->booking_code }}</td>
                <td>{{ $payment->booking->guest_name }}</td>
                <td>{{ $payment->booking->room->name }}</td>
                <td class="text-center">{{ $payment->payment_method === 'bank' ? 'Transfer Bank' : 'E-Wallet' }}</td>
                <td class="text-right amount">Rp {{ number_format($payment->amount, 0, ',', '.') }}</td>
                <td class="text-center">Terverifikasi</td>
            </tr>
            @endforeach
        </tbody>
        <tfoot>
            <tr style="background-color: #f8f9fa; font-weight: bold;">
                <td colspan="6" class="text-right">TOTAL REVENUE:</td>
                <td class="text-right amount">Rp {{ number_format($data['total_revenue'], 0, ',', '.') }}</td>
                <td></td>
            </tr>
        </tfoot>
    </table>

    <div class="footer">
        <p>Laporan digenerate pada {{ now()->format('d M Y H:i') }} WIB</p>
        <p>Grand Azure Hotel - Sistem Manajemen Hotel</p>
    </div>
</body>
</html>