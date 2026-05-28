<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <style>
        body { font-family: Arial, sans-serif; background: #f5f5f5; margin: 0; padding: 20px; }
        .container { max-width: 600px; margin: 0 auto; background: white; border-radius: 8px; overflow: hidden; }
        .header { background: #1a1a2e; color: white; padding: 20px 30px; }
        .body { padding: 30px; color: #333; line-height: 1.6; }
        .footer { background: #f9f9f9; padding: 15px 30px; font-size: 12px; color: #999; text-align: center; }
    </style>
</head>
<body>
    <div class="container">
        <div class="header">
            <h2 style="margin:0">{{ core()->getConfigData('general.general.information.name') ?? config('app.name') }} Support</h2>
        </div>
        <div class="body">
            <p>{{ $replyText }}</p>
        </div>
        <div class="footer">
            <p>This is an automated message from our support system.</p>
        </div>
    </div>
</body>
</html>
