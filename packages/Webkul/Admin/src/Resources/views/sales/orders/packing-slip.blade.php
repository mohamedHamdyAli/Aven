<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Packing Slip – Order #{{ $order->increment_id }}</title>
    <style>
        * { margin: 0; padding: 0; box-sizing: border-box; }
        body { font-family: Arial, sans-serif; font-size: 13px; color: #111; padding: 24px; }
        .header { display: flex; justify-content: space-between; align-items: flex-start; border-bottom: 2px solid #111; padding-bottom: 12px; margin-bottom: 20px; }
        .logo { font-size: 22px; font-weight: bold; letter-spacing: 2px; }
        .title { font-size: 18px; font-weight: bold; text-align: right; }
        .title small { display: block; font-size: 12px; font-weight: normal; color: #555; margin-top: 4px; }
        .grid { display: grid; grid-template-columns: 1fr 1fr 1fr; gap: 16px; margin-bottom: 20px; }
        .box { border: 1px solid #ddd; border-radius: 6px; padding: 12px; }
        .box h4 { font-size: 11px; text-transform: uppercase; letter-spacing: 1px; color: #666; margin-bottom: 8px; border-bottom: 1px solid #eee; padding-bottom: 4px; }
        .box p { margin: 3px 0; line-height: 1.5; }
        table { width: 100%; border-collapse: collapse; margin-bottom: 16px; }
        thead tr { background: #111; color: #fff; }
        th { padding: 8px 10px; text-align: left; font-size: 11px; text-transform: uppercase; letter-spacing: 0.5px; }
        td { padding: 8px 10px; border-bottom: 1px solid #eee; vertical-align: top; }
        tbody tr:nth-child(even) { background: #f9f9f9; }
        .opt { font-size: 11px; color: #555; margin-top: 2px; }
        .totals { margin-left: auto; width: 260px; }
        .totals table { margin-bottom: 0; }
        .totals td { border: none; padding: 4px 10px; }
        .totals .grand { font-weight: bold; font-size: 15px; border-top: 2px solid #111; }
        .footer { margin-top: 24px; border-top: 1px solid #ddd; padding-top: 10px; font-size: 11px; color: #888; display: flex; justify-content: space-between; }
        @media print {
            body { padding: 0; }
            .no-print { display: none !important; }
            @page { margin: 15mm; }
        }
    </style>
</head>
<body>

<div class="no-print" style="margin-bottom:16px;">
    <button onclick="window.print()" style="background:#111;color:#fff;border:none;padding:8px 20px;border-radius:4px;cursor:pointer;font-size:14px;">🖨 Print</button>
    <button onclick="window.close()" style="margin-left:8px;padding:8px 16px;border:1px solid #ccc;background:#fff;border-radius:4px;cursor:pointer;font-size:14px;">Close</button>
</div>

<div class="header">
    <div class="logo">{{ config('app.name', 'AVEN') }}</div>
    <div class="title">
        Packing Slip
        <small>Order #{{ $order->increment_id }} &nbsp;·&nbsp; {{ $order->created_at->format('d M Y') }}</small>
    </div>
</div>

<div class="grid">
    {{-- Billing Address --}}
    <div class="box">
        <h4>Bill To</h4>
        @if($order->billing_address)
            <p><strong>{{ $order->billing_address->first_name }} {{ $order->billing_address->last_name }}</strong></p>
            @if($order->billing_address->company_name)
                <p>{{ $order->billing_address->company_name }}</p>
            @endif
            @foreach((array)$order->billing_address->address as $line)
                @if($line)<p>{{ $line }}</p>@endif
            @endforeach
            <p>{{ $order->billing_address->city }}{{ $order->billing_address->state ? ', '.$order->billing_address->state : '' }}</p>
            <p>{{ $order->billing_address->country }}{{ $order->billing_address->postcode ? ' '.$order->billing_address->postcode : '' }}</p>
            @if($order->billing_address->phone)
                <p>📞 {{ $order->billing_address->phone }}</p>
            @endif
        @endif
    </div>

    {{-- Shipping Address --}}
    <div class="box">
        <h4>Ship To</h4>
        @php $sa = $order->shipping_address ?? $order->billing_address; @endphp
        @if($sa)
            <p><strong>{{ $sa->first_name }} {{ $sa->last_name }}</strong></p>
            @foreach((array)$sa->address as $line)
                @if($line)<p>{{ $line }}</p>@endif
            @endforeach
            <p>{{ $sa->city }}{{ $sa->state ? ', '.$sa->state : '' }}</p>
            <p>{{ $sa->country }}{{ $sa->postcode ? ' '.$sa->postcode : '' }}</p>
            @if($sa->phone)
                <p>📞 {{ $sa->phone }}</p>
            @endif
        @endif
    </div>

    {{-- Order Info --}}
    <div class="box">
        <h4>Order Info</h4>
        <p><strong>Status:</strong> {{ ucfirst($order->status) }}</p>
        <p><strong>Payment:</strong> {{ $order->payment->method_title ?? $order->payment->method }}</p>
        @if($order->shipping_title)
            <p><strong>Shipping:</strong> {{ $order->shipping_title }}</p>
        @endif
        <p><strong>Customer:</strong> {{ $order->customer_first_name }} {{ $order->customer_last_name }}</p>
        <p><strong>Email:</strong> {{ $order->customer_email }}</p>
    </div>
</div>

{{-- Items Table --}}
<table>
    <thead>
        <tr>
            <th>#</th>
            <th>Product</th>
            <th>SKU</th>
            <th>Options</th>
            <th style="text-align:center;">Qty</th>
            <th style="text-align:right;">Unit Price</th>
            <th style="text-align:right;">Total</th>
        </tr>
    </thead>
    <tbody>
        @foreach($order->items as $i => $item)
            <tr>
                <td>{{ $i + 1 }}</td>
                <td>{{ $item->name }}</td>
                <td style="font-size:11px;color:#666;">{{ $item->sku }}</td>
                <td>
                    @if(!empty($item->additional['attributes']))
                        @foreach($item->additional['attributes'] as $attr)
                            <div class="opt"><strong>{{ $attr['attribute_name'] }}:</strong> {{ $attr['option_label'] }}</div>
                        @endforeach
                    @endif
                </td>
                <td style="text-align:center;">{{ (int)$item->qty_ordered }}</td>
                <td style="text-align:right;">{{ core()->formatPrice($item->price) }}</td>
                <td style="text-align:right;">{{ core()->formatPrice($item->total) }}</td>
            </tr>
        @endforeach
    </tbody>
</table>

{{-- Totals --}}
<div class="totals">
    <table>
        <tr><td>Subtotal</td><td style="text-align:right;">{{ core()->formatPrice($order->sub_total) }}</td></tr>
        @if($order->discount_amount > 0)
            <tr><td>Discount</td><td style="text-align:right;">− {{ core()->formatPrice($order->discount_amount) }}</td></tr>
        @endif
        @if($order->shipping_amount > 0)
            <tr><td>Shipping</td><td style="text-align:right;">{{ core()->formatPrice($order->shipping_amount) }}</td></tr>
        @endif
        @if($order->tax_amount > 0)
            <tr><td>Tax</td><td style="text-align:right;">{{ core()->formatPrice($order->tax_amount) }}</td></tr>
        @endif
        <tr class="grand"><td>Grand Total</td><td style="text-align:right;">{{ core()->formatPrice($order->grand_total) }}</td></tr>
    </table>
</div>

<div class="footer">
    <span>{{ config('app.name') }} &nbsp;·&nbsp; Printed {{ now()->format('d M Y H:i') }}</span>
    <span>Order #{{ $order->increment_id }}</span>
</div>

</body>
</html>
