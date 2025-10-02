<!DOCTYPE html>
<html>
<head>
 	<title>Laporan Transaksi</title>
 	<style>
 		body {
 			font-family: Arial, sans-serif;
 			font-size: 12px;
 		}
 		h1, h2 {
 			text-align: center;
 			margin-bottom: 5px;
 		}
 		.header-info {
 			margin-bottom: 20px;
 			text-align: center;
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
 		}
 		th {
 			background-color: #f2f2f2;
 		}
 		tfoot td {
 			font-weight: bold;
 			background-color: #f8f8f8;
 		}
 		.total-row td {
 			border-top: 2px solid #000;
 		}
 		.badge {
 			padding: 2px 6px;
 			border-radius: 12px;
 			font-size: 10px;
 			font-weight: bold;
 		}
 		.badge-tunai {
 			background-color: #d1fae5;
 			color: #065f46;
 		}
 		.badge-transfer {
 			background-color: #dbeafe;
 			color: #1e40af;
 		}
 		.badge-ewallet {
 			background-color: #e9d5ff;
 			color: #7c2d12;
 		}
 	</style>
</head>
<body>

 	<h1>Laporan Transaksi</h1>
 	
 	<div class="header-info">
 		@if($from_date && $to_date)
 		 	<p>Periode: {{ \Carbon\Carbon::parse($from_date)->format('d/m/Y') }} s/d {{ \Carbon\Carbon::parse($to_date)->format('d/m/Y') }}</p>
 		@elseif($from_date)
 		 	<p>Periode: Mulai Tanggal {{ \Carbon\Carbon::parse($from_date)->format('d/m/Y') }}</p>
 		@elseif($to_date)
 		 	<p>Periode: Sampai Tanggal {{ \Carbon\Carbon::parse($to_date)->format('d/m/Y') }}</p>
 		@else
 		 	<p>Periode: Semua Tanggal</p>
 		@endif
 		<p>Membership: {{ $membership_name }}</p>
 		<p>Metode Pembayaran: {{ $metodePembayaranLabel ?? 'Semua Metode' }}</p>
 	</div>

 	<table>
 		<thead>
 			<tr>
 			 	<th>No.</th>
 			 	<th>Tanggal</th>
 			 	<th>Pelanggan</th>
 			 	<th>Membership</th>
 			 	<th>Metode Pembayaran</th>
 			 	<th>Total Harga</th>
 			</tr>
 		</thead>
 		<tbody>
 			@forelse ($transaksis as $transaksi)
 			<tr>
 			 	<td>{{ $loop->iteration }}</td>
 			 	{{-- PERBAIKAN: Menggunakan kolom tanggal_transaksi --}}
 			 	<td>{{ \Carbon\Carbon::parse($transaksi->tanggal_transaksi)->format('d/m/Y') }}</td>
 			 	<td>{{ $transaksi->pelanggan->nama ?? 'N/A' }}</td>
 			 	<td>{{ $transaksi->pelanggan->membership->nama ?? 'N/A' }}</td>
 			 	<td>
 			 	 	<span class="badge 
 			 	 	 	{{ $transaksi->metode_pembayaran == 'tunai' ? 'badge-tunai' : 
 			 	 	 	 	($transaksi->metode_pembayaran == 'transfer' ? 'badge-transfer' : 'badge-ewallet') }}">
 			 	 	 	{{ ucfirst($transaksi->metode_pembayaran == 'ewallet' ? 'E-Wallet' : $transaksi->metode_pembayaran) }}
 			 	 	</span>
 			 	</td>
 			 	<td>Rp {{ number_format($transaksi->total, 0, ',', '.') }}</td>
 			</tr>
 			@empty
 			<tr>
 			 	<td colspan="6" style="text-align: center;">Tidak ada data transaksi.</td>
 			</tr>
 			@endforelse
 		</tbody>
 		<tfoot>
 			<tr class="total-row">
 			 	<td colspan="5" style="text-align: right;">Total Keseluruhan:</td>
 			 	<td>Rp {{ number_format($totalTransaksi, 0, ',', '.') }}</td>
 			</tr>
 		</tfoot>
 	</table>

</body>
</html>