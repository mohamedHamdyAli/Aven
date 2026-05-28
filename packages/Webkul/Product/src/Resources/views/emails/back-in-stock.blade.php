<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <title>{{ $product->name }} is back in stock!</title>
    <style>
        body { font-family: Arial, sans-serif; background: #f4f4f4; margin: 0; padding: 0; }
        .container { max-width: 600px; margin: 30px auto; background: #fff; border-radius: 8px; overflow: hidden; }
        .header { background: #1a202c; padding: 24px 32px; }
        .header h1 { color: #fff; margin: 0; font-size: 20px; }
        .body { padding: 32px; color: #333; }
        .body h2 { font-size: 22px; color: #1a202c; margin-top: 0; }
        .body p { font-size: 15px; line-height: 1.6; }
        .btn { display: inline-block; margin-top: 20px; padding: 12px 28px; background: #1a202c; color: #fff; text-decoration: none; border-radius: 6px; font-size: 15px; }
        .footer { padding: 16px 32px; background: #f4f4f4; text-align: center; font-size: 12px; color: #888; }
    </style>
</head>
<body>
    <div class="container">
        <div class="header">
            <h1>{{ core()->getConfigData('general.store_information.name') ?? config('app.name') }}</h1>
        </div>
        <div class="body">
            <h2>Great news! Your item is back.</h2>
            <p><strong>{{ $product->name }}</strong> is now back in stock and available to order.</p>
            <p>Hurry — stock is limited and other customers are watching this product too!</p>
            <a href="{{ url('/') }}/{{ $product->url_key }}" class="btn">Shop Now</a>
        </div>
        <div class="footer">
            You received this because you subscribed to back-in-stock alerts on
            {{ core()->getConfigData('general.store_information.name') ?? config('app.name') }}.
        </div>
    </div>
</body>
</html>
