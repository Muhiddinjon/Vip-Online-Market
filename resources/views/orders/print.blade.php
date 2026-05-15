<!DOCTYPE html>
<html lang="uz">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Buyurtma #{{ $order->id }}</title>
    <style>
        * { margin: 0; padding: 0; box-sizing: border-box; }
        body { font-family: 'Courier New', Courier, monospace; font-size: 13px; color: #000; background: #fff; padding: 10px; }
        .receipt { max-width: 380px; margin: 0 auto; padding: 10px; }
        .header { text-align: center; border-bottom: 2px dashed #000; padding-bottom: 10px; margin-bottom: 10px; }
        .header h1 { font-size: 18px; font-weight: bold; }
        .header p { font-size: 12px; margin-top: 3px; }
        .section { margin-bottom: 10px; }
        .section-title { font-weight: bold; font-size: 11px; text-transform: uppercase; color: #555; margin-bottom: 4px; }
        .row { display: flex; justify-content: space-between; margin-bottom: 2px; }
        .row .label { color: #333; }
        .row .value { font-weight: bold; }
        .divider { border-top: 1px dashed #999; margin: 8px 0; }
        .items-table { width: 100%; border-collapse: collapse; }
        .items-table th { text-align: left; font-size: 11px; color: #555; border-bottom: 1px solid #ccc; padding: 3px 0; }
        .items-table td { padding: 4px 0; vertical-align: top; }
        .items-table .name { width: 50%; }
        .items-table .qty { width: 15%; text-align: center; }
        .items-table .price { width: 20%; text-align: right; }
        .items-table .total { width: 15%; text-align: right; font-weight: bold; }
        .totals { border-top: 2px dashed #000; padding-top: 8px; margin-top: 8px; }
        .totals .grand-total { font-size: 16px; font-weight: bold; }
        .status-badge { display: inline-block; padding: 2px 8px; border: 1px solid #000; border-radius: 3px; font-size: 11px; margin-top: 4px; }
        .footer { text-align: center; margin-top: 15px; font-size: 11px; color: #666; border-top: 1px dashed #999; padding-top: 8px; }
        @media print {
            .no-print { display: none !important; }
            body { padding: 0; }
        }
    </style>
</head>
<body>
    <div class="receipt">

        <div class="header">
            <h1>{{ $order->restaurant->name ?? 'VIP Online Market' }}</h1>
            <p>Buyurtma #{{ $order->id }}</p>
            <p>{{ $order->created_at->format('d.m.Y H:i') }}</p>
        </div>

        <div class="section">
            <div class="section-title">Mijoz ma'lumotlari</div>
            <div class="row">
                <span class="label">Ism:</span>
                <span class="value">{{ $order->customer->user->name ?? '—' }}</span>
            </div>
            <div class="row">
                <span class="label">Telefon:</span>
                <span class="value">{{ $order->customer->user->phone ?? '—' }}</span>
            </div>
            @if($order->delivery_address)
            <div class="row">
                <span class="label">Manzil:</span>
                <span class="value" style="max-width:200px;text-align:right;">{{ $order->delivery_address }}</span>
            </div>
            @endif
            @if($order->note)
            <div class="row">
                <span class="label">Izoh:</span>
                <span class="value">{{ $order->note }}</span>
            </div>
            @endif
        </div>

        <div class="divider"></div>

        <div class="section">
            <div class="section-title">Mahsulotlar</div>
            <table class="items-table">
                <thead>
                    <tr>
                        <th class="name">Nomi</th>
                        <th class="qty">Soni</th>
                        <th class="price">Narxi</th>
                        <th class="total">Jami</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($order->items as $item)
                    <tr>
                        <td class="name">{{ $item->name }}</td>
                        <td class="qty">{{ $item->quantity }} {{ $item->unit }}</td>
                        <td class="price">{{ number_format($item->price, 0, '.', ' ') }}</td>
                        <td class="total">{{ number_format($item->price * $item->quantity, 0, '.', ' ') }}</td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>

        <div class="totals">
            <div class="row">
                <span class="label">Mahsulotlar:</span>
                <span>{{ number_format($order->subtotal, 0, '.', ' ') }} so'm</span>
            </div>
            <div class="row">
                <span class="label">Yetkazish:</span>
                <span>{{ number_format($order->delivery_fee, 0, '.', ' ') }} so'm</span>
            </div>
            <div class="divider"></div>
            <div class="row grand-total">
                <span>JAMI:</span>
                <span>{{ number_format($order->total, 0, '.', ' ') }} so'm</span>
            </div>
            <div class="row" style="margin-top:4px;">
                <span class="label">To'lov:</span>
                <span class="value">{{ $order->payment_method === 'cash' ? 'Naqd' : 'Karta' }}</span>
            </div>
        </div>

        <div class="footer">
            Rahmat! • viponlinemarket.uz
        </div>
    </div>

    <div class="no-print" style="text-align:center;margin-top:20px;">
        <button onclick="window.print()" style="padding:8px 20px;cursor:pointer;font-size:14px;">🖨️ Chop etish</button>
    </div>

    <script>
        window.onload = function() { window.print(); };
    </script>
</body>
</html>
