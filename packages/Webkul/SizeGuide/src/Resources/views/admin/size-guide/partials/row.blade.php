@php
    $inp = 'w-full rounded border border-gray-300 px-1 py-1 dark:border-gray-600 dark:bg-gray-800 dark:text-white text-xs focus:outline-none focus:border-blue-400';
    $n = "rows[$i]";
@endphp
<tr class="border-t border-gray-100 dark:border-gray-800">
    <td class="px-1 py-1">
        <input name="{{ $n }}[label]" class="{{ $inp }}" placeholder="S,M,L..."
               value="{{ $row['label'] ?? '' }}" required>
    </td>
    @foreach(['eu_size','uk_size','us_size'] as $f)
    <td class="px-1 py-1">
        <input name="{{ $n }}[{{ $f }}]" class="{{ $inp }}" placeholder="—"
               value="{{ $row[$f] ?? '' }}">
    </td>
    @endforeach
    @foreach(['chest','waist','hips','height'] as $f)
    <td class="px-1 py-1">
        <input name="{{ $n }}[{{ $f }}_min]" type="number" step="0.1" class="{{ $inp }}" placeholder="min"
               value="{{ $row[$f.'_min'] ?? '' }}">
    </td>
    <td class="px-1 py-1">
        <input name="{{ $n }}[{{ $f }}_max]" type="number" step="0.1" class="{{ $inp }}" placeholder="max"
               value="{{ $row[$f.'_max'] ?? '' }}">
    </td>
    @endforeach
    @foreach(['product_chest','product_waist','product_length','product_shoulder'] as $f)
    <td class="px-1 py-1">
        <input name="{{ $n }}[{{ $f }}]" type="number" step="0.1" class="{{ $inp }}" placeholder="cm"
               value="{{ $row[$f] ?? '' }}">
    </td>
    @endforeach
    <td class="px-1 py-1">
        <button type="button" class="remove-row text-red-400 hover:text-red-600 text-lg leading-none">×</button>
    </td>
</tr>
