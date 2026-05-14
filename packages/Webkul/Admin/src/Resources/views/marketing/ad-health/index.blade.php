<x-admin::layouts>
    <x-slot:title>Ads & Tracking Health</x-slot>

    @php
        $categories = [
            'pixels'    => ['label' => 'Tracking Pixels', 'icon' => 'icon-eye'],
            'meta'      => ['label' => 'Meta Tags', 'icon' => 'icon-share'],
            'schema'    => ['label' => 'Structured Data', 'icon' => 'icon-sort'],
            'technical' => ['label' => 'Technical SEO', 'icon' => 'icon-settings'],
        ];
        $priorities = [
            'critical' => ['label' => 'Critical', 'bg' => '#FEE2E2', 'color' => '#DC2626'],
            'high'     => ['label' => 'High',     'bg' => '#FEF3C7', 'color' => '#D97706'],
            'medium'   => ['label' => 'Medium',   'bg' => '#DBEAFE', 'color' => '#2563EB'],
            'low'      => ['label' => 'Low',      'bg' => '#F3F4F6', 'color' => '#6B7280'],
        ];

        $scoreColor = $score >= 80 ? '#16A34A' : ($score >= 50 ? '#D97706' : '#DC2626');
        $scoreBg    = $score >= 80 ? '#DCFCE7' : ($score >= 50 ? '#FEF3C7' : '#FEE2E2');
    @endphp

    {{-- ── Header ── --}}
    <div style="display:flex;align-items:center;justify-content:space-between;margin-bottom:24px;flex-wrap:wrap;gap:12px">
        <div>
            <h1 style="font-size:20px;font-weight:700;color:#111827;margin:0">Ads & Tracking Health</h1>
            <p style="font-size:13px;color:#6B7280;margin:4px 0 0">فحص تلقائي لكل أدوات التتبع والإعلانات</p>
        </div>
        <div style="display:flex;align-items:center;gap:8px">
            <span style="font-size:12px;color:#9CA3AF">آخر فحص: {{ now()->format('d M Y — H:i') }}</span>
            <a href="{{ route('admin.marketing.ad-health.index') }}"
               style="display:inline-flex;align-items:center;gap:6px;border-radius:8px;background:#F3F4F6;border:1px solid #E5E7EB;padding:7px 14px;font-size:12px;font-weight:600;color:#374151;text-decoration:none">
                ↻ إعادة الفحص
            </a>
        </div>
    </div>

    {{-- ── Score Card ── --}}
    <div style="border-radius:16px;border:1px solid #E5E7EB;background:#fff;padding:24px;margin-bottom:24px;display:flex;align-items:center;gap:24px;flex-wrap:wrap">

        {{-- Score Circle --}}
        <div style="position:relative;width:110px;height:110px;flex-shrink:0">
            <svg viewBox="0 0 36 36" style="width:110px;height:110px;transform:rotate(-90deg)">
                <circle cx="18" cy="18" r="15.9" fill="none" stroke="#F3F4F6" stroke-width="3"/>
                <circle cx="18" cy="18" r="15.9" fill="none"
                        stroke="{{ $scoreColor }}" stroke-width="3"
                        stroke-dasharray="{{ $score }}, 100"
                        stroke-linecap="round"/>
            </svg>
            <div style="position:absolute;inset:0;display:flex;flex-direction:column;align-items:center;justify-content:center">
                <span style="font-size:22px;font-weight:900;color:{{ $scoreColor }};line-height:1">{{ $score }}%</span>
                <span style="font-size:10px;font-weight:600;color:#9CA3AF;margin-top:2px">Score</span>
            </div>
        </div>

        {{-- Summary --}}
        <div style="flex:1;min-width:200px">
            <p style="font-size:18px;font-weight:700;color:#111827;margin:0 0 4px">
                @if($score >= 80) ✅ الـ tracking مضبوط كويس
                @elseif($score >= 50) ⚠️ في حاجات محتاج تضبطها
                @else 🔴 في مشاكل حرجة لازم تتحل الأول
                @endif
            </p>
            <p style="font-size:13px;color:#6B7280;margin:0">
                {{ $passed }} من {{ $total }} عنصر شغال — {{ $total - $passed }} محتاج اهتمام
            </p>

            {{-- Mini stat pills --}}
            <div style="display:flex;flex-wrap:wrap;gap:8px;margin-top:12px">
                @foreach(['critical' => 'حرجة', 'high' => 'عالية', 'medium' => 'متوسطة', 'low' => 'منخفضة'] as $p => $pLabel)
                    @php
                        $cnt = collect($checks)->where('priority', $p)->where('status', false)->count();
                    @endphp
                    @if($cnt > 0)
                    <span style="border-radius:999px;padding:3px 10px;font-size:11px;font-weight:700;
                                 background:{{ $priorities[$p]['bg'] }};color:{{ $priorities[$p]['color'] }}">
                        {{ $cnt }} {{ $pLabel }}
                    </span>
                    @endif
                @endforeach
                @if($passed === $total)
                <span style="border-radius:999px;padding:3px 10px;font-size:11px;font-weight:700;background:#DCFCE7;color:#16A34A">
                    كل حاجة شغالة 🎉
                </span>
                @endif
            </div>
        </div>

        {{-- Quick stats --}}
        <div style="display:grid;grid-template-columns:repeat(2,1fr);gap:12px;min-width:180px">
            @foreach($categories as $catKey => $cat)
            @php
                $catChecks = $byCategory->get($catKey, collect());
                $catPassed = $catChecks->where('status', true)->count();
                $catTotal  = $catChecks->count();
                $catPct    = $catTotal > 0 ? (int)round($catPassed / $catTotal * 100) : 0;
                $catColor  = $catPct >= 80 ? '#16A34A' : ($catPct >= 50 ? '#D97706' : '#DC2626');
            @endphp
            <div style="border-radius:10px;background:#F9FAFB;border:1px solid #F3F4F6;padding:10px 12px;text-align:center">
                <div style="font-size:18px;font-weight:800;color:{{ $catColor }}">{{ $catPct }}%</div>
                <div style="font-size:10px;font-weight:600;color:#6B7280;margin-top:2px">{{ $cat['label'] }}</div>
            </div>
            @endforeach
        </div>
    </div>

    {{-- ── Sections ── --}}
    @foreach($categories as $catKey => $cat)
    @php $catChecks = $byCategory->get($catKey, collect()); @endphp
    @if($catChecks->isNotEmpty())
    <div style="border-radius:14px;border:1px solid #E5E7EB;background:#fff;margin-bottom:20px;overflow:hidden">

        {{-- Section header --}}
        <div style="padding:14px 20px;background:#F9FAFB;border-bottom:1px solid #F3F4F6;display:flex;align-items:center;justify-content:space-between">
            <div style="display:flex;align-items:center;gap:8px">
                <span style="font-size:13px;font-weight:700;color:#111827">{{ $cat['label'] }}</span>
            </div>
            @php
                $secPassed = $catChecks->where('status', true)->count();
                $secTotal  = $catChecks->count();
            @endphp
            <span style="font-size:11px;font-weight:600;color:#6B7280">{{ $secPassed }}/{{ $secTotal }} شغالين</span>
        </div>

        {{-- Check rows --}}
        @foreach($catChecks as $i => $check)
        <div style="display:flex;align-items:center;gap:14px;padding:14px 20px;{{ $i < $catChecks->count()-1 ? 'border-bottom:1px solid #F9FAFB;' : '' }}">

            {{-- Status dot --}}
            <div style="width:32px;height:32px;border-radius:50%;flex-shrink:0;display:flex;align-items:center;justify-content:center;font-size:14px;
                        background:{{ $check['status'] ? '#DCFCE7' : '#FEE2E2' }};
                        color:{{ $check['status'] ? '#16A34A' : '#DC2626' }}">
                {{ $check['status'] ? '✓' : '✗' }}
            </div>

            {{-- Label + message --}}
            <div style="flex:1;min-width:0">
                <div style="display:flex;align-items:center;gap:8px;flex-wrap:wrap">
                    <span style="font-size:13px;font-weight:600;color:#111827">{{ $check['label'] }}</span>
                    @if(!$check['status'])
                    <span style="border-radius:999px;padding:2px 8px;font-size:10px;font-weight:700;
                                 background:{{ $priorities[$check['priority']]['bg'] }};
                                 color:{{ $priorities[$check['priority']]['color'] }}">
                        {{ $priorities[$check['priority']]['label'] }}
                    </span>
                    @endif
                </div>
                <p style="font-size:11px;color:{{ $check['status'] ? '#6B7280' : '#9CA3AF' }};margin:3px 0 0">
                    {{ $check['message'] }}
                </p>
            </div>

            {{-- Fix button --}}
            @if(!$check['status'] && $check['fix_url'])
            <a href="{{ $check['fix_url'] }}"
               style="flex-shrink:0;border-radius:8px;border:1px solid #E5E7EB;background:#F9FAFB;padding:6px 14px;font-size:11px;font-weight:600;color:#374151;text-decoration:none;white-space:nowrap">
                {{ $check['fix_label'] ?? 'إصلاح' }} →
            </a>
            @elseif($check['status'])
            <span style="flex-shrink:0;border-radius:8px;padding:6px 14px;font-size:11px;font-weight:600;color:#16A34A;background:#F0FDF4">
                ✓ مضبوط
            </span>
            @else
            <span style="flex-shrink:0;border-radius:8px;padding:6px 14px;font-size:11px;font-weight:600;color:#9CA3AF;background:#F9FAFB">
                يحتاج تطوير
            </span>
            @endif

        </div>
        @endforeach
    </div>
    @endif
    @endforeach

    {{-- ── Legend ── --}}
    <div style="border-radius:12px;border:1px solid #E5E7EB;background:#F9FAFB;padding:16px 20px;margin-top:4px">
        <p style="font-size:11px;font-weight:700;color:#6B7280;margin:0 0 8px;text-transform:uppercase;letter-spacing:.05em">
            الأولويات
        </p>
        <div style="display:flex;flex-wrap:wrap;gap:12px">
            @foreach($priorities as $p => $pData)
            <div style="display:flex;align-items:center;gap:6px">
                <span style="width:10px;height:10px;border-radius:50%;background:{{ $pData['color'] }};flex-shrink:0"></span>
                <span style="font-size:12px;color:#374151;font-weight:500">{{ $pData['label'] }}</span>
            </div>
            @endforeach
        </div>
        <p style="font-size:11px;color:#9CA3AF;margin:10px 0 0">
            "يحتاج تطوير" = الإصلاح محتاج تعديل في الكود مش بس في الإعدادات.
            تواصل مع المطور أو افتح تذكرة.
        </p>
    </div>

</x-admin::layouts>
