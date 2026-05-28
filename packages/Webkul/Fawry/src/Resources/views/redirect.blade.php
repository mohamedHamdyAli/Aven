<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Fawry Payment</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            display: flex;
            justify-content: center;
            align-items: center;
            min-height: 100vh;
            margin: 0;
            background-color: #f5f5f5;
        }
        .container {
            background: #fff;
            padding: 40px;
            border-radius: 8px;
            box-shadow: 0 2px 12px rgba(0,0,0,0.1);
            text-align: center;
            max-width: 480px;
            width: 100%;
        }
        .fawry-logo {
            font-size: 2rem;
            font-weight: bold;
            color: #f15a22;
            margin-bottom: 16px;
        }
        .ref-box {
            background: #fff8f0;
            border: 2px solid #f15a22;
            border-radius: 6px;
            padding: 20px;
            margin: 24px 0;
        }
        .ref-label {
            font-size: 0.9rem;
            color: #666;
            margin-bottom: 8px;
        }
        .ref-number {
            font-size: 2rem;
            font-weight: bold;
            color: #f15a22;
            letter-spacing: 4px;
        }
        .instructions {
            color: #444;
            line-height: 1.6;
            margin-bottom: 24px;
        }
        .btn {
            display: inline-block;
            padding: 12px 28px;
            background: #f15a22;
            color: #fff;
            text-decoration: none;
            border-radius: 4px;
            font-size: 1rem;
        }
    </style>
</head>
<body>
    <div class="container">
        <div class="fawry-logo">Fawry</div>

        @if ($fawryRef)
            <h2>Your Payment Reference</h2>
            <div class="ref-box">
                <div class="ref-label">Reference Number</div>
                <div class="ref-number">{{ $fawryRef }}</div>
            </div>
            <p class="instructions">
                Take this reference number to any Fawry branch or kiosk to complete your payment.
                You can also pay through Fawry mobile app, MyFawry app, or any partnered outlet.
                <br><br>
                <strong>Order #{{ $order->increment_id }}</strong><br>
                Amount: {{ number_format($order->grand_total, 2) }} {{ $order->order_currency_code }}
            </p>
            <a href="{{ route('shop.checkout.onepage.success') }}" class="btn">Done</a>
        @else
            <h2>Redirecting to Fawry...</h2>
            <p class="instructions">Redirecting to Fawry payment page. Please wait...</p>
        @endif
    </div>
</body>
</html>
