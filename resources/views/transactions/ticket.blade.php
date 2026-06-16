<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <style>
        @page {
            margin: 8mm;
        }
        body {
            font-family: Helvetica, Arial, sans-serif;
            font-size: 11px;
            color: #333;
            text-align: center;
            margin: 0;
            padding: 0;
        }
        .header {
            font-size: 14px;
            font-weight: bold;
            margin-bottom: 4px;
            letter-spacing: 1px;
        }
        .address {
            font-size: 9px;
            color: #666;
            margin-bottom: 16px;
            line-height: 1.4;
        }
        .title {
            font-size: 16px;
            font-weight: bold;
            margin: 16px 0 8px;
            letter-spacing: 2px;
        }
        .info {
            font-size: 12px;
            margin: 4px 0;
            font-weight: bold;
        }
        .ticket-number {
            font-size: 13px;
            font-weight: bold;
            color: #e1008c;
            margin: 12px 0 4px;
        }
        .date-info {
            font-size: 11px;
            color: #e1008c;
            font-weight: bold;
        }
        .divider {
            border-top: 1px dashed #999;
            margin: 16px 0;
        }
        .warning {
            font-size: 9px;
            font-weight: bold;
            color: #333;
            line-height: 1.5;
            text-transform: uppercase;
            margin-top: 16px;
        }
    </style>
</head>
<body>
    <div class="header">SIJA PARKING</div>
    <div class="address">
        Jl. Raya Karadenan No.7, Karadenan,<br>
        Kec. Cibinong, Kabupaten Bogor, Jawa Barat 16111
    </div>

    <div class="divider"></div>

    <div class="title">TIKET PARKIR</div>
    <div class="info">{{ $transaction->location->location_name }}</div>
    <div class="info">{{ ucfirst($transaction->vehicleType->jenis) }}</div>

    <div class="divider"></div>

    <div class="ticket-number">No Tiket : {{ $transaction->no_tiket }}</div>
    <div class="date-info">Tanggal : {{ $transaction->masuk->format('Y-m-d H:i:s') }}</div>

    <div class="divider"></div>

    <div class="warning">
        Jangan meninggalkan tiket dan barang<br>
        berharga di dalam kendaraan
    </div>
</body>
</html>
