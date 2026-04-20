<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Struk Transaksi - {{ $order->order_code }}</title>
    <style>
        body {
            font-family: 'Courier New', Courier, monospace;
            padding: 20px;
            max-width: 400px;
            margin: auto;
            color: #000;
        }
        .header {
            text-align: center;
            border-bottom: 2px dashed #000;
            padding-bottom: 10px;
            margin-bottom: 10px;
        }
        .info {
            font-size: 14px;
            margin-bottom: 10px;
        }
        .table {
            width: 100%;
            border-collapse: collapse;
            font-size: 13px;
        }
        .table th, .table td {
            text-align: left;
            padding: 5px 0;
        }
        .table .right {
            text-align: right;
        }
        .divider {
            border-top: 1px dashed #000;
            margin: 10px 0;
        }
        .summary {
            font-size: 14px;
        }
        .summary div {
            display: flex;
            justify-content: space-between;
            margin-bottom: 5px;
        }
        .total {
            font-weight: bold;
            font-size: 16px;
            border-top: 1px solid #000;
            padding-top: 5px;
        }
        .footer {
            text-align: center;
            margin-top: 20px;
            font-size: 12px;
        }
        @media print {
            .no-print {
                display: none;
            }
            body {
                padding: 0;
            }
        }
    </style>
</head>
<body>

    <button class="no-print" onclick="window.print()" style="margin-bottom: 20px; cursor: pointer; padding: 5px 15px;">Cetak Sekarang</button>

    <div class="header">
        <h2 style="margin: 0;">HUDA LAUNDRY</h2>
        <p style="margin: 5px 0;">Jl. Kebon Jeruk No. 123, Jakarta</p>
        <p style="margin: 0;">Telp: 0812-3456-7890</p>
    </div>

    <div class="info">
        <div><strong>Kode:</strong> {{ $order->order_code }}</div>
        <div><strong>Pelanggan:</strong> {{ $order->customer->customer_name }}</div>
        <div><strong>Tgl Order:</strong> {{ \Carbon\Carbon::parse($order->order_date)->format('d/m/Y') }}</div>
        <div><strong>Estimasi Selesai:</strong> {{ \Carbon\Carbon::parse($order->order_end_date)->format('d/m/Y') }}</div>
    </div>

    <div class="divider"></div>

    <table class="table">
        <thead>
            <tr>
                <th>Layanan</th>
                <th class="right">Qty</th>
                <th class="right">Total</th>
            </tr>
        </thead>
        <tbody>
            @foreach($order->details as $detail)
            <tr>
                <td>{{ $detail->service->service_name }}</td>
                <td class="right">{{ $detail->qty }} kg</td>
                <td class="right">{{ number_format($detail->subtotal, 0, ',', '.') }}</td>
            </tr>
            @endforeach
        </tbody>
    </table>

    <div class="divider"></div>

    <div class="summary">
        <div>
            <span>Subtotal:</span>
            <span>{{ number_format($order->total - $order->tax, 0, ',', '.') }}</span>
        </div>
        @if(false && $order->discount > 0)
        <div>
            <span>Diskon:</span>
            <span>-{{ number_format($order->discount, 0, ',', '.') }}</span>
        </div>
        @endif
        <div>
            <span>Pajak (10%):</span>
            <span>{{ number_format($order->tax, 0, ',', '.') }}</span>
        </div>
        <div class="total">
            <span>GRAND TOTAL:</span>
            <span>Rp {{ number_format($order->total, 0, ',', '.') }}</span>
        </div>
        <div class="divider"></div>
        <div>
            <span>Bayar:</span>
            <span>Rp {{ number_format($order->order_pay, 0, ',', '.') }}</span>
        </div>
        <div>
            <span>Kembali:</span>
            <span>Rp {{ number_format($order->order_change, 0, ',', '.') }}</span>
        </div>
        <div style="font-weight: bold; margin-top: 5px;">
            <span>Status Bayar:</span>
            <span>{{ $order->payment_status }}</span>
        </div>
    </div>

    <div class="footer">
        <p>*** Terima Kasih ***</p>
        <p>Pakaian bersih, hati pun senang!</p>
    </div>

    <script>
        // Auto print after load
        window.onload = function() {
            // window.print();
        }
    </script>
</body>
</html>
