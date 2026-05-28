<!DOCTYPE html>
<html lang="ar" dir="rtl">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{ $shareholder->name }} — بوابة الشركاء</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link href="https://fonts.googleapis.com/css2?family=Cairo:wght@400;500;600;700;800&display=swap" rel="stylesheet">
    <style>
        body { font-family: 'Cairo', sans-serif; }
        .stat-card { @apply rounded-2xl bg-white border border-gray-100 shadow-sm p-6; }
    </style>
</head>
<body class="min-h-screen bg-gray-50" dir="rtl">

    {{-- Navbar --}}
    <header class="bg-white border-b border-gray-200 shadow-sm sticky top-0 z-10">
        <div class="max-w-5xl mx-auto px-4 sm:px-6 py-4 flex items-center justify-between">
            <div class="flex items-center gap-3">
                <div class="flex h-10 w-10 items-center justify-center rounded-xl bg-blue-600 text-white text-lg font-bold">
                    {{ strtoupper(substr($shareholder->name, 0, 1)) }}
                </div>
                <div>
                    <p class="font-bold text-gray-800 leading-tight">{{ $shareholder->name }}</p>
                    <p class="text-xs text-gray-400">بوابة الشركاء</p>
                </div>
            </div>
            <form method="POST" action="{{ route('shareholder.portal.logout') }}">
                @csrf
                <button type="submit" class="rounded-xl border border-gray-200 px-4 py-2 text-sm text-gray-500 hover:bg-gray-50 transition">
                    خروج
                </button>
            </form>
        </div>
    </header>

    <main class="max-w-5xl mx-auto px-4 sm:px-6 py-8 flex flex-col gap-8">

        {{-- Welcome --}}
        <div>
            <h1 class="text-2xl font-bold text-gray-800">أهلاً، {{ explode(' ', $shareholder->name)[0] }} 👋</h1>
            <p class="text-gray-500 text-sm mt-1">
                شريك منذ {{ $shareholder->joined_at?->format('M Y') ?? 'غير محدد' }}
                @if($shareholder->email)
                    · {{ $shareholder->email }}
                @endif
            </p>
        </div>

        {{-- Stats Grid --}}
        <div class="grid grid-cols-2 gap-4 sm:grid-cols-4">

            {{-- Ownership % --}}
            <div class="rounded-2xl bg-gradient-to-br from-blue-500 to-blue-700 p-6 text-white shadow-md col-span-2 sm:col-span-1">
                <p class="text-blue-100 text-xs font-semibold mb-2">نسبة ملكيتك</p>
                <p class="text-4xl font-extrabold">{{ number_format($shareholder->percentage, 1) }}<span class="text-2xl">%</span></p>
                <div class="mt-3 h-1.5 w-full rounded-full bg-blue-400/40">
                    <div class="h-1.5 rounded-full bg-white/80" style="width: {{ min($shareholder->percentage, 100) }}%"></div>
                </div>
            </div>

            {{-- Total Earned --}}
            <div class="rounded-2xl bg-gradient-to-br from-green-500 to-emerald-600 p-6 text-white shadow-md col-span-2 sm:col-span-1">
                <p class="text-green-100 text-xs font-semibold mb-2">إجمالي أرباحك</p>
                <p class="text-2xl font-extrabold">{{ core()->currency($totalEarned) }}</p>
                <p class="text-green-200 text-xs mt-2">من {{ $items->count() }} توزيع</p>
            </div>

            {{-- Best Distribution --}}
            <div class="rounded-2xl bg-white border border-gray-100 shadow-sm p-6">
                <p class="text-gray-400 text-xs font-semibold mb-2">أعلى توزيع</p>
                @if($bestDistribution)
                    <p class="text-xl font-bold text-gray-800">{{ core()->currency($bestDistribution->amount) }}</p>
                    <p class="text-gray-400 text-xs mt-2">{{ $bestDistribution->distribution?->period_from?->format('M Y') }}</p>
                @else
                    <p class="text-gray-400 text-sm">—</p>
                @endif
            </div>

            {{-- Last Distribution --}}
            <div class="rounded-2xl bg-white border border-gray-100 shadow-sm p-6">
                <p class="text-gray-400 text-xs font-semibold mb-2">آخر توزيع</p>
                @if($lastDistribution)
                    <p class="text-xl font-bold text-gray-800">{{ core()->currency($lastDistribution->amount) }}</p>
                    <p class="text-gray-400 text-xs mt-2">{{ $lastDistribution->distribution?->period_to?->format('M j, Y') }}</p>
                @else
                    <p class="text-gray-400 text-sm">لا يوجد بعد</p>
                @endif
            </div>
        </div>

        {{-- Distributions Table --}}
        <div class="rounded-2xl bg-white border border-gray-100 shadow-sm overflow-hidden">
            <div class="px-6 py-5 border-b border-gray-100 flex items-center justify-between">
                <p class="font-bold text-gray-800">سجل التوزيعات</p>
                <span class="rounded-full bg-blue-50 px-3 py-1 text-xs font-semibold text-blue-600">{{ $items->count() }} توزيع</span>
            </div>

            @if($items->isEmpty())
                <div class="p-12 text-center text-gray-400">
                    لا توجد توزيعات حتى الآن
                </div>
            @else
                <div class="overflow-x-auto">
                    <table class="w-full text-sm">
                        <thead class="bg-gray-50 text-xs text-gray-500 uppercase">
                            <tr>
                                <th class="px-6 py-3 text-right">الفترة</th>
                                <th class="px-6 py-3 text-left">صافي ربح الشركة</th>
                                <th class="px-6 py-3 text-left">نسبتك</th>
                                <th class="px-6 py-3 text-left">نصيبك</th>
                                <th class="px-6 py-3 text-left">الحالة</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-gray-50">
                            @foreach($items as $item)
                                @php $dist = $item->distribution; @endphp
                                <tr class="hover:bg-gray-50 transition">
                                    <td class="px-6 py-4 text-right">
                                        <p class="font-semibold text-gray-800">
                                            {{ $dist?->period_from?->format('M j') }} – {{ $dist?->period_to?->format('M j, Y') }}
                                        </p>
                                        <p class="text-xs text-gray-400 mt-0.5">{{ $dist?->created_at?->format('M j, Y') }}</p>
                                    </td>
                                    <td class="px-6 py-4">
                                        <p class="font-medium text-gray-700">{{ core()->currency($dist?->net_profit) }}</p>
                                        @if($dist?->net_profit > 0)
                                            <p class="text-xs mt-0.5 {{ $dist->net_profit >= 0 ? 'text-green-500' : 'text-red-500' }}">
                                                {{ $dist->net_profit >= 0 ? '▲ ربح' : '▼ خسارة' }}
                                            </p>
                                        @endif
                                    </td>
                                    <td class="px-6 py-4">
                                        <span class="rounded-full bg-blue-50 px-3 py-1 text-xs font-semibold text-blue-600">
                                            {{ number_format($item->percentage, 2) }}%
                                        </span>
                                    </td>
                                    <td class="px-6 py-4">
                                        <p class="text-lg font-bold {{ $item->amount >= 0 ? 'text-green-600' : 'text-red-600' }}">
                                            {{ core()->currency($item->amount) }}
                                        </p>
                                    </td>
                                    <td class="px-6 py-4">
                                        <span class="rounded-full {{ $item->amount >= 0 ? 'bg-green-50 text-green-700' : 'bg-red-50 text-red-700' }} px-3 py-1 text-xs font-semibold">
                                            {{ $item->amount >= 0 ? 'ربح' : 'خسارة' }}
                                        </span>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                        <tfoot class="bg-gray-50 font-bold">
                            <tr>
                                <td colspan="3" class="px-6 py-4 text-right text-gray-600">الإجمالي</td>
                                <td class="px-6 py-4 text-lg font-extrabold {{ $totalEarned >= 0 ? 'text-green-600' : 'text-red-600' }}">
                                    {{ core()->currency($totalEarned) }}
                                </td>
                                <td></td>
                            </tr>
                        </tfoot>
                    </table>
                </div>
            @endif
        </div>

        {{-- Notes --}}
        @if($shareholder->notes)
            <div class="rounded-2xl bg-amber-50 border border-amber-200 p-5">
                <p class="text-xs font-semibold text-amber-600 uppercase mb-1">ملاحظات</p>
                <p class="text-sm text-amber-800">{{ $shareholder->notes }}</p>
            </div>
        @endif

    </main>

    <footer class="text-center py-8 text-xs text-gray-400">
        بوابة الشركاء — {{ config('app.name') }}
    </footer>

</body>
</html>
