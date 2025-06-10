<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Voucher - {{ $order->order_number }}</title>
    <style>
        body {
            font-family: 'DejaVu Sans', sans-serif;
            color: #333;
            font-size: 13px;
            margin: 0;
            padding: 0;
        }
        .page {
            page-break-after: always;
            padding: 40px;
        }
        .voucher-box {
            border: 2px dashed #666;
            border-radius: 10px;
            padding: 25px;
            width: 100%;
            box-sizing: border-box;
        }
        .header {
            text-align: center;
            margin-bottom: 25px;
        }
        .header h2 {
            margin: 0;
            font-size: 22px;
            color: #444;
        }
        .info-table {
            width: 100%;
            margin-top: 10px;
        }
        .info-table td {
            padding: 6px 4px;
        }
        .label {
            font-weight: bold;
            width: 30%;
        }
        .divider {
            border-bottom: 1px solid #ccc;
            margin: 30px 0;
        }
        .footer {
            font-size: 11px;
            color: #777;
            text-align: center;
            margin-top: 30px;
        }
    </style>
</head>
<body>

@foreach ($order->orderItems as $item)
    @php $voucher = $item->voucher; @endphp
    <div class="page">
        <div class="voucher-box">
            <div class="header">
                <h2>Voucher {{ $voucher->name }}</h2>
            </div>

            <table class="info-table">
                <tr>
                    <td class="label">Kode Voucher:</td>
                    <td>{{ $voucher->voucher_code }}</td>
                </tr>
                <tr>
                    <td class="label">Nama Pelanggan:</td>
                    <td>{{ $order->customer_name }}</td>
                </tr>
                <tr>
                    <td class="label">Email:</td>
                    <td>{{ $order->customer_email }}</td>
                </tr>
                <tr>
                    <td class="label">Durasi:</td>
                    <td>{{ $voucher->duration }} Hari</td>
                </tr>
                <tr>
                    <td class="label">Ukuran:</td>
                    <td>{{ $voucher->size }}</td>
                </tr>
                <tr>
                    <td class="label">Harga:</td>
                    <td>Rp{{ number_format($voucher->price, 0, ',', '.') }}</td>
                </tr>
                <tr>
                    <td class="label">Tanggal Order:</td>
                    <td>{{ \Carbon\Carbon::parse($order->order_date)->translatedFormat('d F Y') }}</td>
                </tr>
            </table>
        </div>

        <div class="footer">
            Voucher ini hanya berlaku untuk 1x penggunaan. Simpan baik-baik kode Anda.
        </div>
    </div>
@endforeach

</body>
</html>
