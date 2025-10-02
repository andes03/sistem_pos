<!DOCTYPE html>
<html>
<head>
    <title>Laporan Transaksi</title>
    <style>
        /* BASE STYLES */
        body {
            font-family: 'Helvetica', Arial, sans-serif;
            font-size: 11px;
            color: #333;
            line-height: 1.5;
        }

        /* HEADER */
        .report-title {
            text-align: center;
            font-size: 24px;
            color: #1F2937; /* Dark Gray */
            margin-bottom: 5px;
            padding-bottom: 5px;
            border-bottom: 3px solid #3B82F6; /* Blue Accent */
        }
        
        .header-info {
            margin-bottom: 20px;
            font-size: 12px;
            color: #4B5563; 
            padding-left: 5px; 
        }

        /* STYLING TABEL INFO HEADER */
        .info-table {
            width: 40%; /* Menjaga lebar tabel informasi */
            border-collapse: collapse;
            margin: 0;
        }
        .info-table td {
            padding: 1px 0;
            border: none;
            font-size: 12px;
        }
        .info-table .label {
            width: 50%;
            text-align: left;
            padding-right: 5px;
        }
        .info-table .value {
            font-weight: normal;
        }
        /* END STYLING INFO TABLE */

        /* TABLE STYLES (UTAMA) */
        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 20px;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.05);
        }
        
        thead tr {
            background-color: #3B82F6;
            color: #FFFFFF;
        }
        th {
            padding: 10px 8px;
            text-align: left;
            border: none;
            font-size: 12px;
            font-weight: bold;
        }

        /* Table Body */
        td {
            padding: 8px;
            text-align: left;
            border: none;
            border-bottom: 1px solid #E5E7EB;
        }
        tbody tr:nth-child(even) { 
            background-color: #F9FAFB;
        }
        tbody tr:last-child td {
            border-bottom: none;
        }

        /* FOOTER (TOTAL ROW) */
        tfoot tr.total-row td {
            background-color: #EBF8FF;
            color: #1F2937;
            font-weight: bold;
            font-size: 13px;
            padding: 12px 8px;
            border-top: 2px solid #3B82F6;
        }
        tfoot td:last-child {
            color: #B91C1C;
            font-size: 14px;
        }
        tfoot td:first-child {
             text-align: right !important;
        }
    </style>
</head>
<body>

    <h1 class="report-title">LAPORAN TRANSAKSI</h1>
    
    <div class="header-info">
        <table class="info-table">
            {{-- PERIODE --}}
            <tr>
                <td class="label">Periode</td>
                <td>: 
                @if($from_date && $to_date)
                    {{ \Carbon\Carbon::parse($from_date)->format('d F Y') }} s/d {{ \Carbon\Carbon::parse($to_date)->format('d F Y') }}
                @elseif($from_date)
                    Mulai Tanggal {{ \Carbon\Carbon::parse($from_date)->format('d F Y') }}
                @elseif($to_date)
                    Sampai Tanggal {{ \Carbon\Carbon::parse($to_date)->format('d F Y') }}
                @else
                    Semua Tanggal
                @endif
                </td>
            </tr>
            
            {{-- KOLOM MEMBERSHIP DI HILANGKAN DARI HEADER INFO --}}
            {{-- <tr>
                <td class="label">Membership</td>
                <td>: {{ $membership_name }}</td>
            </tr> --}}
            
            {{-- METODE PEMBAYARAN --}}
            <tr>
                <td class="label">Metode Pembayaran</td>
                <td>: {{ $metodePembayaranLabel ?? 'Semua Metode' }}</td>
            </tr>
            {{-- DICETAK OLEH --}}
            <tr>
                <td class="label">Dicetak oleh</td>
                <td>: {{ $user_name ?? 'N/A' }}</td>
            </tr>
        </table>
    </div>

    <table>
        <thead>
            <tr>
                <th style="width: 5%;">No.</th>
                <th style="width: 20%;">Tanggal</th>
                <th style="width: 30%;">Pelanggan</th>
                {{-- KOLOM MEMBERSHIP DI HILANGKAN --}}
                {{-- <th style="width: 15%;">Membership</th> --}}
                <th style="width: 25%;">Metode Pembayaran</th>
                <th style="width: 20%; text-align: right;">Total Harga</th>
            </tr>
        </thead>
        <tbody>
            @forelse ($transaksis as $transaksi)
            <tr>
                <td>{{ $loop->iteration }}</td>
                <td>{{ \Carbon\Carbon::parse($transaksi->tanggal_transaksi)->format('d/m/Y') }}</td>
                <td>{{ $transaksi->pelanggan->nama ?? 'N/A' }}</td>
                {{-- DATA MEMBERSHIP DI HILANGKAN --}}
                {{-- <td>{{ $transaksi->pelanggan->membership->nama ?? 'N/A' }}</td> --}}
                <td>
                    {{ ucfirst($transaksi->metode_pembayaran == 'ewallet' ? 'E-Wallet' : $transaksi->metode_pembayaran) }}
                </td>
                <td style="text-align: right;">Rp {{ number_format($transaksi->total, 0, ',', '.') }}</td>
            </tr>
            @empty
            <tr>
                <td colspan="5" style="text-align: center; padding: 15px;">Tidak ada data transaksi untuk filter ini.</td>
            </tr>
            @endforelse
        </tbody>
        <tfoot>
            <tr class="total-row">
                {{-- colspan dikurangi dari 5 menjadi 4 karena 1 kolom hilang (Membership) --}}
                <td colspan="4">Total Keseluruhan:</td>
                <td style="text-align: right;">Rp {{ number_format($totalTransaksi, 0, ',', '.') }}</td>
            </tr>
        </tfoot>
    </table>

</body>
</html>