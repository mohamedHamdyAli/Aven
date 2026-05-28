<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Redirecting to Valu...</title>
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <style>
        body { font-family: sans-serif; display: flex; align-items: center; justify-content: center; min-height: 100vh; background: #f5f5f5; margin: 0; }
        .card { background: white; border-radius: 12px; padding: 40px; text-align: center; box-shadow: 0 4px 20px rgba(0,0,0,.08); max-width: 400px; width: 100%; }
        .logo { font-size: 2rem; margin-bottom: 1rem; }
        h2 { color: #1a1a2e; margin-bottom: .5rem; }
        p { color: #666; font-size: .9rem; margin-bottom: 1.5rem; }
        .spinner { width: 32px; height: 32px; border: 3px solid #e2e8f0; border-top-color: #1a1a2e; border-radius: 50%; animation: spin .8s linear infinite; margin: 0 auto 1rem; }
        @keyframes spin { to { transform: rotate(360deg); } }
        .btn { display: inline-block; background: #1a1a2e; color: white; padding: 10px 24px; border-radius: 8px; text-decoration: none; font-size: .9rem; }
    </style>
</head>
<body>
    <div class="card">
        <div class="logo">&#x1F4B3;</div>
        <h2>Redirecting to Valu</h2>
        <p>You'll be redirected to Valu's secure payment page to complete your installment plan (&#x062A;&#x0642;&#x0633;&#x064A;&#x0637;). Please wait...</p>
        <div class="spinner"></div>
        <a href="<?php echo e($valuUrl); ?>" class="btn" id="redirect-btn">Continue to Valu &rarr;</a>
    </div>
    <script>
        setTimeout(function() { window.location.href = '<?php echo e($valuUrl); ?>'; }, 2000);
    </script>
</body>
</html>
<?php /**PATH D:\aven\packages\Webkul\Valu\src\Resources\views\redirect.blade.php ENDPATH**/ ?>