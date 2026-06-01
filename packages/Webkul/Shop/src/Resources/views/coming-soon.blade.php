@php
    $heading   = core()->getConfigData('general.content.coming_soon.heading')  ?? 'Coming Soon';
    $subtext   = core()->getConfigData('general.content.coming_soon.subtext')  ?? 'We are working on something amazing. Stay tuned!';
    $videoUrl  = core()->getConfigData('general.content.coming_soon.video_url') ?? '';
    $logoUrl   = core()->getConfigData('general.content.coming_soon.logo_url')  ?? '';
    $siteLogo  = core()->getCurrentChannel()->logo_url ?? bagisto_asset('images/logo.svg');
    $finalLogo = $logoUrl ?: $siteLogo;

    $isYoutube = $videoUrl && (str_contains($videoUrl, 'youtube.com') || str_contains($videoUrl, 'youtu.be'));
    $isMp4     = $videoUrl && ! $isYoutube;

    // Convert youtube watch URL to embed
    if ($isYoutube) {
        preg_match('/(?:v=|youtu\.be\/)([a-zA-Z0-9_-]{11})/', $videoUrl, $m);
        $ytId = $m[1] ?? '';
        $videoUrl = "https://www.youtube.com/embed/{$ytId}?autoplay=1&mute=1&loop=1&playlist={$ytId}&controls=0&showinfo=0&rel=0&playsinline=1";
    }
@endphp
<!DOCTYPE html>
<html lang="{{ app()->getLocale() }}" dir="{{ core()->getCurrentLocale()->direction ?? 'ltr' }}">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{ $heading }} — {{ config('app.name') }}</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link href="https://fonts.googleapis.com/css2?family=Raleway:wght@300;400;600;700&family=Open+Sans:wght@300;400&display=swap" rel="stylesheet">
    <style>
        *, *::before, *::after { box-sizing: border-box; margin: 0; padding: 0; }

        body {
            font-family: 'Open Sans', sans-serif;
            background: #0f0f0f;
            color: #fff;
            min-height: 100vh;
            overflow: hidden;
            display: flex;
            align-items: center;
            justify-content: center;
        }

        /* Background */
        .bg-layer {
            position: fixed;
            inset: 0;
            z-index: 0;
        }

        /* Video background */
        .bg-video-mp4 {
            width: 100%;
            height: 100%;
            object-fit: cover;
            opacity: 0.55;
        }

        .bg-video-yt {
            width: 100vw;
            height: 56.25vw; /* 16:9 */
            min-height: 100vh;
            min-width: 177.78vh;
            position: absolute;
            top: 50%;
            left: 50%;
            transform: translate(-50%, -50%);
            border: none;
            pointer-events: none;
            opacity: 0.55;
        }

        /* Gradient overlay */
        .overlay {
            position: fixed;
            inset: 0;
            z-index: 1;
            background: linear-gradient(
                135deg,
                rgba(10, 10, 30, 0.82) 0%,
                rgba(10, 10, 30, 0.55) 50%,
                rgba(10, 10, 30, 0.82) 100%
            );
        }

        /* Animated gradient (no video fallback) */
        .bg-gradient {
            width: 100%;
            height: 100%;
            background: linear-gradient(135deg, #0f0c29, #302b63, #24243e);
            background-size: 400% 400%;
            animation: gradMove 12s ease infinite;
        }

        @keyframes gradMove {
            0%   { background-position: 0% 50%; }
            50%  { background-position: 100% 50%; }
            100% { background-position: 0% 50%; }
        }

        /* Floating particles */
        .particles {
            position: fixed;
            inset: 0;
            z-index: 2;
            overflow: hidden;
            pointer-events: none;
        }

        .particle {
            position: absolute;
            border-radius: 50%;
            background: rgba(255,255,255,0.06);
            animation: float linear infinite;
        }

        @keyframes float {
            0%   { transform: translateY(110vh) scale(0); opacity: 0; }
            10%  { opacity: 1; }
            90%  { opacity: 1; }
            100% { transform: translateY(-10vh) scale(1); opacity: 0; }
        }

        /* Content */
        .content {
            position: relative;
            z-index: 10;
            text-align: center;
            padding: 2rem;
            max-width: 700px;
            width: 100%;
            animation: fadeUp 1.2s ease forwards;
            opacity: 0;
        }

        @keyframes fadeUp {
            from { opacity: 0; transform: translateY(30px); }
            to   { opacity: 1; transform: translateY(0); }
        }

        .logo {
            max-height: 64px;
            max-width: 220px;
            width: auto;
            margin: 0 auto 2.5rem;
            display: block;
            filter: brightness(0) invert(1);
        }

        .divider {
            width: 60px;
            height: 2px;
            background: linear-gradient(90deg, transparent, #fff, transparent);
            margin: 1.5rem auto;
        }

        h1 {
            font-family: 'Raleway', sans-serif;
            font-size: clamp(2.5rem, 8vw, 5rem);
            font-weight: 700;
            letter-spacing: 0.15em;
            text-transform: uppercase;
            line-height: 1;
            background: linear-gradient(135deg, #ffffff 0%, #a8b8d8 100%);
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
            background-clip: text;
        }

        .subtext {
            font-family: 'Open Sans', sans-serif;
            font-size: clamp(0.9rem, 2.5vw, 1.1rem);
            font-weight: 300;
            color: rgba(255,255,255,0.7);
            line-height: 1.7;
            margin-top: 1rem;
            max-width: 480px;
            margin-left: auto;
            margin-right: auto;
        }

        /* Animated dots */
        .dots {
            display: flex;
            justify-content: center;
            gap: 10px;
            margin-top: 2.5rem;
        }

        .dot {
            width: 8px;
            height: 8px;
            border-radius: 50%;
            background: rgba(255,255,255,0.3);
            animation: pulse 1.5s ease-in-out infinite;
        }

        .dot:nth-child(2) { animation-delay: 0.3s; }
        .dot:nth-child(3) { animation-delay: 0.6s; }

        @keyframes pulse {
            0%, 100% { background: rgba(255,255,255,0.3); transform: scale(1); }
            50%       { background: rgba(255,255,255,0.9); transform: scale(1.3); }
        }

        /* Brand name watermark */
        .brand-watermark {
            position: fixed;
            bottom: 1.5rem;
            left: 50%;
            transform: translateX(-50%);
            z-index: 10;
            font-family: 'Raleway', sans-serif;
            font-size: 0.7rem;
            letter-spacing: 0.3em;
            text-transform: uppercase;
            color: rgba(255,255,255,0.25);
        }
    </style>
</head>
<body>

    {{-- Background --}}
    <div class="bg-layer">
        @if ($isMp4)
            <video class="bg-video-mp4" autoplay muted loop playsinline>
                <source src="{{ $videoUrl }}" type="video/mp4">
            </video>
        @elseif ($isYoutube)
            <iframe class="bg-video-yt" src="{{ $videoUrl }}" allow="autoplay; encrypted-media" allowfullscreen></iframe>
        @else
            <div class="bg-gradient"></div>
        @endif
    </div>

    {{-- Overlay --}}
    @if ($videoUrl)
        <div class="overlay"></div>
    @endif

    {{-- Particles --}}
    <div class="particles" id="particles"></div>

    {{-- Main content --}}
    <div class="content">
        <img src="{{ $finalLogo }}" alt="{{ config('app.name') }}" class="logo">

        <div class="divider"></div>

        <h1>{{ $heading }}</h1>

        @if ($subtext)
            <p class="subtext">{{ $subtext }}</p>
        @endif

        <div class="dots">
            <div class="dot"></div>
            <div class="dot"></div>
            <div class="dot"></div>
        </div>
    </div>

    <div class="brand-watermark">{{ config('app.name') }}</div>

    <script>
        // Generate floating particles
        const container = document.getElementById('particles');
        for (let i = 0; i < 18; i++) {
            const p = document.createElement('div');
            p.className = 'particle';
            const size = Math.random() * 60 + 20;
            p.style.cssText = `
                width: ${size}px;
                height: ${size}px;
                left: ${Math.random() * 100}%;
                animation-duration: ${Math.random() * 15 + 10}s;
                animation-delay: ${Math.random() * 10}s;
            `;
            container.appendChild(p);
        }
    </script>

</body>
</html>
