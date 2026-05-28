<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Picking List – {{ now()->format('d M Y') }}</title>
    <style>
        * { margin: 0; padding: 0; box-sizing: border-box; }
        body { font-family: Arial, sans-serif; font-size: 13px; color: #111; padding: 24px; }
        .header { display: flex; justify-content: space-between; align-items: flex-start; border-bottom: 2px solid #111; padding-bottom: 12px; margin-bottom: 20px; }
        .logo { font-size: 22px; font-weight: bold; letter-spacing: 2px; }
        .title { font-size: 18px; font-weight: bold; text-align: right; }
        .title small { display: block; font-size: 12px; font-weight: normal; color: #555; margin-top: 4px; }
        table { width: 100%; border-collapse: collapse; margin-bottom: 24px; }
        thead tr { background: #111; color: #fff; }
        th { padding: 8px 10px; text-align: left; font-size: 11px; text-transform: uppercase; letter-spacing: 0.5px; }
        td { padding: 8px 10px; border-bottom: 1px solid #eee; vertical-align: top; }
        tbody tr:nth-child(even) { background: #f9f9f9; }
        .check { width: 24px; height: 24px; border: 2px solid #999; border-radius: 4px; display: inline-block; }
        .orders-list { font-size: 11px; color: #555; }
        .orders-list a { color: #555; text-decoration: none; }
        .opt { font-size: 11px; color: #666; }
        .total-qty { font-size: 15px; font-weight: bold; }
        .footer { margin-top: 24px; border-top: 1px solid #ddd; padding-top: 10px; font-size: 11px; color: #888; display: flex; justify-content: space-between; }
        .summary { background: #f5f5f5; border-radius: 6px; padding: 12px 16px; margin-bottom: 20px; display: flex; gap: 32px; font-size: 13px; }
        .summary strong { display: block; font-size: 18px; }
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
    <a href="{{ route('admin.sales.orders.index') }}" style="margin-left:8px;padding:8px 16px;border:1px solid #ccc;background:#fff;border-radius:4px;cursor:pointer;font-size:14px;text-decoration:none;color:#111;">← Back to Orders</a>
</div>

<div class="header">
    <div class="logo">{{ config('app.name', 'AVEN') }}</div>
    <div class="title">
        Picking List
        <small>{{ now()->format('d M Y, H:i') }} &nbsp;·&nbsp; {{ $orders->count() }} order(s)</small>
    </div>
</div>

<div class="summary">
    <div><strong>{{ $orders->count() }}</strong> Orders</div>
    <div><strong>{{ $grouped->count() }}</strong> SKUs</div>
    <div><strong>{{ $grouped->flatten(1)->sum('qty') }}</strong> Total Items</div>
</div>

@if($grouped->isEmpty())
    <p style="color:#888;padding:32px;text-align:center;">No pending or processing orders to pick.</p>
@else
<table>
    <thead>
        <tr>
            <th style="width:32px;">✓</th>
            <th>Product / SKU</th>
            <th>Options</th>
            <th style="text-align:center;">Total Qty</th>
            <th>Orders</th>
        </tr>
    </thead>
    <tbody>
        @foreach($grouped as $sku => $lines)
            @php $totalQty = $lines->sum('qty'); @endphp
            <tr>
                <td><span class="check"></span></td>
                <td>
                    <div>{{ $lines->first()['product_name'] }}</div>
                    <div style="font-size:11px;color:#888;margin-top:2px;">{{ $sku }}</div>
                </td>
                <td>
                    @foreach($lines as $line)
                        @if(!empty($line['options']))
                            <div class="opt">
                                @foreach($line['options'] as $opt)
                                    <span>{{ $opt['attribute_name'] }}: <strong>{{ $opt['option_label'] }}</strong></span>
                                @endforeach
                                &nbsp;× {{ $line['qty'] }}
                            </div>
                        @endif
                    @endforeach
                </td>
                <td style="text-align:center;"><span class="total-qty">{{ $totalQty }}</span></td>
                <td class="orders-list">
                    @foreach($lines as $line)
                        <div>
                            <a href="{{ route('admin.sales.orders.view', $line['order_db_id']) }}" target="_blank">
                                #{{ $line['order_id'] }}
                            </a>
                            <span style="color:#aaa;">({{ $line['status'] }}, qty {{ $line['qty'] }})</span>
                        </div>
                    @endforeach
                </td>
            </tr>
        @endforeach
    </tbody>
</table>
@endif

<div class="footer">
    <span>{{ config('app.name') }} &nbsp;·&nbsp; Picking List &nbsp;·&nbsp; Printed {{ now()->format('d M Y H:i') }}</span>
    <span>{{ $orders->count() }} orders &nbsp;·&nbsp; {{ $grouped->count() }} SKUs</span>
</div>

</body>
</html>
