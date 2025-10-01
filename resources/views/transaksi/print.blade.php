<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Nota Transaksi #{{ str_pad($transaksi->id, 6, '0', STR_PAD_LEFT) }}</title>
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            font-family: 'Courier New', monospace;
            font-size: 12px;
            line-height: 1.3;
            color: #000;
            background: #f0f0f0;
            padding: 10px;
        }

        .receipt-container {
            width: 80mm;
            max-width: 300px;
            margin: 0 auto;
            background: white;
            padding: 8px;
            border: 1px solid #ccc;
            box-shadow: 0 2px 5px rgba(0,0,0,0.1);
        }

        /* Header Toko */
        .store-header {
            text-align: center;
            border-bottom: 1px dashed #000;
            padding-bottom: 8px;
            margin-bottom: 8px;
        }

        .store-name {
            font-size: 16px;
            font-weight: bold;
            margin-bottom: 2px;
        }

        .store-info {
            font-size: 10px;
            margin: 1px 0;
        }

        /* Transaction Header */
        .transaction-header {
            text-align: center;
            margin-bottom: 8px;
            padding-bottom: 8px;
            border-bottom: 1px dashed #000;
        }

        .transaction-id {
            font-size: 14px;
            font-weight: bold;
            margin-bottom: 4px;
        }

        .transaction-status {
            font-size: 10px;
            font-weight: bold;
        }

        /* Info Section */
        .info-section {
            margin-bottom: 8px;
            font-size: 10px;
        }

        .info-line {
            display: flex;
            justify-content: space-between;
            margin-bottom: 1px;
        }

        .info-line .label {
            font-weight: bold;
        }

        /* Products Table */
        .products-section {
            margin-bottom: 8px;
        }

        .section-title {
            font-size: 11px;
            font-weight: bold;
            text-align: center;
            margin-bottom: 6px;
            padding: 2px 0;
            border-top: 1px dashed #000;
            border-bottom: 1px dashed #000;
        }

        .product-item {
            margin-bottom: 4px;
            border-bottom: 1px dotted #ccc;
            padding-bottom: 2px;
        }

        .product-name {
            font-weight: bold;
            font-size: 11px;
            margin-bottom: 1px;
        }

        .product-details {
            display: flex;
            justify-content: space-between;
            font-size: 10px;
        }

        .qty-price {
            display: flex;
            gap: 10px;
        }

        /* Summary */
        .summary-section {
            border-top: 1px dashed #000;
            padding-top: 6px;
            margin-bottom: 8px;
        }

        .summary-line {
            display: flex;
            justify-content: space-between;
            margin-bottom: 2px;
            font-size: 11px;
        }

        .summary-line.total {
            font-weight: bold;
            font-size: 12px;
            border-top: 1px solid #000;
            border-bottom: 1px solid #000;
            padding: 2px 0;
            margin: 4px 0;
        }

        .discount-line {
            color: #666;
        }

        /* Footer */
        .receipt-footer {
            text-align: center;
            border-top: 1px dashed #000;
            padding-top: 8px;
            font-size: 10px;
        }

        .thank-you {
            font-weight: bold;
            margin-bottom: 4px;
        }

        .print-time {
            font-size: 9px;
            color: #666;
        }

        /* Print Styles */
        @media print {
            body {
                padding: 0;
                margin: 0;
                background: white;
            }
            
            .receipt-container {
                box-shadow: none;
                border: none;
                width: 80mm;
                max-width: none;
            }

            @page {
                margin: 5mm;
                size: 80mm auto;
            }
        }

        /* Utility classes */
        .text-center {
            text-align: center;
        }

        .text-right {
            text-align: right;
        }

        .bold {
            font-weight: bold;
        }

        .small {
            font-size: 9px;
        }
    </style>
</head>
<body>
    <div class="receipt-container">
        <!-- Store Header -->
        <div class="store-header">
            <div class="store-name">Sebelas coffee</div>
            <div class="store-info">Jl. Nologaten, Nologaten</div>
            <div class="store-info">Kabupaten Sleman, Daerah Istimewa Yogyakarta</div>
            <div class="store-info">55281</div>
            <div class="store-info">Telp : 082117549291</div>
        </div>

        @php
            // Perhitungan diskon
            $subtotalKeseluruhan = $transaksi->detailTransaksi->sum('subtotal');
            $totalSetelahDiskon = $transaksi->total;
            $jumlahDiskon = $subtotalKeseluruhan - $totalSetelahDiskon;
            $persenDiskon = $subtotalKeseluruhan > 0 ? ($jumlahDiskon / $subtotalKeseluruhan) * 100 : 0;
            $membership = $transaksi->pelanggan->membership ?? null;
        @endphp

        <!-- Transaction Header -->
        <div class="transaction-header">
            <div class="transaction-id">NOTA #{{ str_pad($transaksi->id, 6, '0', STR_PAD_LEFT) }}</div>
            <div class="transaction-status">{{ \Carbon\Carbon::parse($transaksi->tanggal_transaksi)->locale('id')->isoFormat('DD/MM/YYYY HH:mm') }}</div>
        </div>

        <!-- Transaction Info -->
        <div class="info-section">
            <div class="info-line">
                <span class="label">Kasir:</span>
                <span>{{ $transaksi->user->nama ?? 'N/A' }}</span>
            </div>
            <div class="info-line">
                <span class="label">Pelanggan:</span>
                <span>{{ $transaksi->pelanggan->nama ?? 'Umum' }}</span>
            </div>
            @if($transaksi->pelanggan)
            <div class="info-line">
                <span class="label">HP:</span>
                <span>{{ $transaksi->pelanggan->nomor_hp ?? '-' }}</span>
            </div>
            @endif
            <div class="info-line">
                <span class="label">Pembayaran:</span>
                <span class="bold">{{ strtoupper($transaksi->metode_pembayaran) }}</span>
            </div>
        </div>

        <!-- Products Section -->
        <div class="products-section">
            <div class="section-title">DETAIL PESANAN ({{ $transaksi->detailTransaksi->count() }} item)</div>
            
            @forelse($transaksi->detailTransaksi as $detail)
            <div class="product-item">
                <div class="product-name">{{ $detail->produk->nama ?? 'Produk Dihapus' }}</div>
                <div class="product-details">
                    <div class="qty-price">
                        <span>{{ $detail->jumlah }}x</span>
                        <span>{{ number_format($detail->harga, 0, ',', '.') }}</span>
                    </div>
                    <div class="text-right bold">{{ number_format($detail->subtotal, 0, ',', '.') }}</div>
                </div>
            </div>
            @empty
            <div class="text-center" style="padding: 10px;">
                Tidak ada produk
            </div>
            @endforelse
        </div>

        <!-- Summary Section -->
        <div class="summary-section">
            <div class="summary-line">
                <span>Subtotal:</span>
                <span>{{ number_format($subtotalKeseluruhan, 0, ',', '.') }}</span>
            </div>
            
            @if($jumlahDiskon > 0)
            <div class="summary-line discount-line">
                <span>Diskon Membership ({{ number_format($persenDiskon, 1) }}%):</span>
                <span>-{{ number_format($jumlahDiskon, 0, ',', '.') }}</span>
            </div>
            @endif
            
            <div class="summary-line total">
                <span>TOTAL:</span>
                <span>Rp {{ number_format($totalSetelahDiskon, 0, ',', '.') }}</span>
            </div>
        </div>

        <!-- Footer -->
        <div class="receipt-footer">
            <div class="thank-you">Terima Kasih!</div>
            <div class="small">Selamat menikmati kopi Anda</div>
            <div style="margin: 6px 0;">
                <div class="small">Follow us:</div>
                <div class="small">@balcos.co</div>
            </div>
            <div class="print-time">
                {{ now()->locale('id')->isoFormat('DD/MM/YYYY') }}
            </div>
        </div>
    </div>

    <script>
        // Auto print when page loads
        window.onload = function() {
            window.print();
        };
    </script>
</body>
</html>