@php
    $heading      = core()->getConfigData('general.content.coming_soon.heading')      ?? 'Coming Soon';
    $subtext      = core()->getConfigData('general.content.coming_soon.subtext')      ?? 'We are working on something amazing. Stay tuned!';
    $videoUrl     = core()->getConfigData('general.content.coming_soon.video_url')    ?? '';
    $logoUrl      = core()->getConfigData('general.content.coming_soon.logo_url')     ?? '';
    $logoSize     = core()->getConfigData('general.content.coming_soon.logo_size')     ?? '120px';
    $headingSize  = core()->getConfigData('general.content.coming_soon.heading_size') ?? '5rem';
    $subtextSize  = core()->getConfigData('general.content.coming_soon.subtext_size') ?? '1.1rem';
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
            background: #000;
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

        /* Dark overlay on video */
        .overlay {
            position: fixed;
            inset: 0;
            z-index: 1;
            background: rgba(0, 0, 0, 0.6);
        }

        /* Solid black background (no video fallback) */
        .bg-gradient {
            width: 100%;
            height: 100%;
            background: #000;
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
            background: radial-gradient(circle at 35% 35%, rgba(255,255,255,0.55), rgba(255,255,255,0.08));
            border: 1px solid rgba(255,255,255,0.4);
            animation: float linear infinite;
            animation-fill-mode: backwards;
        }

        .fashion-word {
            position: absolute;
            color: rgba(255,255,255,0.45);
            font-family: 'Raleway', sans-serif;
            font-size: 0.65rem;
            font-weight: 600;
            letter-spacing: 0.3em;
            text-transform: uppercase;
            white-space: nowrap;
            animation: float linear infinite;
            animation-fill-mode: backwards;
            pointer-events: none;
        }

        @keyframes float {
            0%   { transform: translateY(110vh); opacity: 0; }
            8%   { opacity: 1; }
            85%  { opacity: 1; }
            100% { transform: translateY(-10vh); opacity: 0; }
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
            max-height: {{ $logoSize }};
            max-width: 400px;
            width: auto;
            margin: 0 auto 2.5rem;
            display: block;
            filter: brightness(0) invert(1);
        }

        .divider {
            width: 80px;
            height: 1px;
            background: linear-gradient(90deg, transparent, rgba(255,255,255,0.8), transparent);
            margin: 1.8rem auto;
        }

        h1 {
            font-family: 'Raleway', sans-serif;
            font-size: clamp(2rem, 8vw, {{ $headingSize }});
            font-weight: 900;
            letter-spacing: 0.12em;
            text-transform: uppercase;
            line-height: 1.05;
            color: #ffffff;
            text-shadow: 0 0 60px rgba(255,255,255,0.12);
        }

        .subtext {
            font-family: 'Raleway', sans-serif;
            font-size: clamp(0.7rem, 2vw, {{ $subtextSize }});
            font-weight: 300;
            color: rgba(255,255,255,0.45);
            letter-spacing: 0.2em;
            text-transform: uppercase;
            line-height: 2;
            margin-top: 1.2rem;
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

        /* Admin edit bar */
        .admin-bar {
            position: fixed;
            top: 0;
            left: 0;
            right: 0;
            z-index: 9999;
            background: rgba(15, 23, 42, 0.95);
            backdrop-filter: blur(8px);
            border-bottom: 1px solid rgba(99, 102, 241, 0.4);
            display: flex;
            align-items: center;
            justify-content: space-between;
            padding: 0.6rem 1.5rem;
            font-family: 'Open Sans', sans-serif;
            font-size: 0.8rem;
        }
        .admin-bar-label {
            color: rgba(255,255,255,0.5);
            letter-spacing: 0.05em;
        }
        .admin-bar-label span {
            color: #818cf8;
            font-weight: 600;
        }
        .admin-bar-actions {
            display: flex;
            gap: 0.6rem;
            align-items: center;
        }
        .admin-btn {
            display: inline-flex;
            align-items: center;
            gap: 0.4rem;
            padding: 0.4rem 1rem;
            border-radius: 6px;
            font-size: 0.78rem;
            font-weight: 600;
            text-decoration: none;
            transition: all 0.2s;
            cursor: pointer;
            border: none;
        }
        .admin-btn-primary {
            background: #6366f1;
            color: #fff;
        }
        .admin-btn-primary:hover { background: #4f46e5; color: #fff; }
        .admin-btn-danger {
            background: rgba(239, 68, 68, 0.15);
            color: #fca5a5;
            border: 1px solid rgba(239, 68, 68, 0.3);
        }
        .admin-btn-danger:hover { background: rgba(239, 68, 68, 0.3); color: #fff; }
    </style>
</head>
<body>

    {{-- Admin bar (only for logged-in admins) --}}
    @if (auth()->guard('admin')->check())
        <div class="admin-bar">
            <div class="admin-bar-label">
                <span>Coming Soon</span> mode is active
            </div>
            <div class="admin-bar-actions">
                <a href="{{ route('admin.configuration.index', ['slug' => 'general', 'slug2' => 'content']) }}" class="admin-btn admin-btn-primary" target="_blank">
                    ✏️ Edit Settings
                </a>
                <form method="POST" action="{{ route('admin.configuration.store', ['slug' => 'general', 'slug2' => 'content']) }}" style="display:inline;">
                    @csrf
                    <input type="hidden" name="general[content][coming_soon][enabled]" value="0">
                    <button type="submit" class="admin-btn admin-btn-danger">
                        ✕ Disable
                    </button>
                </form>
            </div>
        </div>
    @endif

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
        @if ($logoUrl)
            <img src="{{ $logoUrl }}" alt="{{ config('app.name') }}" class="logo">
        @else
            <div style="font-family:'Raleway',sans-serif; font-size:2.8rem; font-weight:900; letter-spacing:0.35em; text-transform:uppercase; color:#fff; margin-bottom:2.5rem;">
                {{ config('app.name') }}
            </div>
        @endif

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
        const container = document.getElementById('particles');

        const fashionWords = [
            'Couture','Chic','Vogue','Elegance','Style','Luxe','Glamour',
            'Trend','Mode','Atelier','Runway','Bespoke','Capsule','Haute',
            'Minimal','Classic','Velvet','Silk','Satin','Tailored','Sheer',
            'Refined','Bold','Season','Edit','Drape','Fitted','Linen'
        ];

        // Bubbles — left (0–28%) and right (72–100%) only, centre stays clear
        const bubbleSlots = [2,8,15,22,5,12,19,   72,79,86,93,75,83,90];
        for (let i = 0; i < 14; i++) {
            const p = document.createElement('div');
            p.className = 'particle';
            const size = Math.random() * 60 + 20;
            p.style.cssText = `
                width:${size}px; height:${size}px;
                left:${bubbleSlots[i]}%;
                animation-duration:${Math.random() * 14 + 12}s;
                animation-delay:${i * 1.3}s;
            `;
            container.appendChild(p);
        }

        // Fashion words — LEFT (1%–22%) and RIGHT (76%–97%), 6 each side, staggered
        const wordSlots = [1, 8, 16, 23, 4, 11,   75, 84, 93, 78, 87, 96];
        for (let i = 0; i < 12; i++) {
            const w = document.createElement('div');
            w.className = 'fashion-word';
            w.textContent = fashionWords[i % fashionWords.length];
            const dur = 16 + (i % 3) * 3;
            w.style.cssText = `
                left:${wordSlots[i]}%;
                animation-duration:${dur}s;
                animation-delay:${i * 2.5}s;
            `;
            w.addEventListener('animationiteration', () => {
                w.textContent = fashionWords[Math.floor(Math.random() * fashionWords.length)];
            });
            container.appendChild(w);
        }
    </script>

</body>
</html>
