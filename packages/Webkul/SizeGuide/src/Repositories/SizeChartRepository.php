<?php

namespace Webkul\SizeGuide\Repositories;

use Webkul\SizeGuide\Models\SizeChart;
use Webkul\SizeGuide\Models\SizeChartRow;

class SizeChartRepository
{
    public function all()
    {
        return SizeChart::withCount('rows')->latest()->get();
    }

    public function find(int $id): ?SizeChart
    {
        return SizeChart::with('rows')->find($id);
    }

    public function create(array $data, ?string $imagePath = null): SizeChart
    {
        $overlays = isset($data['image_overlays']) && $data['image_overlays'] !== ''
            ? json_decode($data['image_overlays'], true)
            : null;

        $chart = SizeChart::create([
            'name'           => $data['name'],
            'gender'         => $data['gender'],
            'type'           => $data['type'],
            'image'          => $imagePath,
            'image_overlays' => $overlays,
            'column_headers' => isset($data['column_headers']) && is_array($data['column_headers'])
                ? array_map('strval', $data['column_headers'])
                : null,
        ]);

        $this->syncRows($chart, $data['rows'] ?? []);

        return $chart;
    }

    public function update(int $id, array $data, ?string $imagePath = null, bool $clearImage = false): SizeChart
    {
        $chart = SizeChart::findOrFail($id);
        $decoded = (isset($data['image_overlays']) && $data['image_overlays'] !== '')
            ? json_decode($data['image_overlays'], true)
            : null;
        $overlays = ($decoded && !empty($decoded)) ? $decoded : $chart->image_overlays;

        $chart->update([
            'name'           => $data['name'],
            'gender'         => $data['gender'],
            'type'           => $data['type'],
            'image'          => $clearImage ? null : ($imagePath ?? $chart->image),
            'image_overlays' => $clearImage ? null : $overlays,
            'column_headers' => isset($data['column_headers']) && is_array($data['column_headers'])
                ? array_map('strval', $data['column_headers'])
                : $chart->column_headers,
        ]);

        $this->syncRows($chart, $data['rows'] ?? []);

        return $chart;
    }

    public function delete(int $id): void
    {
        SizeChart::findOrFail($id)->delete();
    }

    public function assignedChartId(int $productId): ?int
    {
        $row = \DB::table('product_size_chart')->where('product_id', $productId)->first();
        return $row ? (int) $row->size_chart_id : null;
    }

    /** Return products currently linked to a chart (id + name + sku) */
    public function assignedProducts(int $chartId): array
    {
        return \DB::table('product_size_chart as psc')
            ->join('products as p', 'p.id', '=', 'psc.product_id')
            ->join('product_flat as pf', function ($j) {
                $j->on('pf.product_id', '=', 'p.id')
                  ->where('pf.locale', config('app.locale', 'en'))
                  ->whereNull('p.parent_id');
            })
            ->where('psc.size_chart_id', $chartId)
            ->select('p.id', 'pf.name', 'p.sku')
            ->get()
            ->map(fn($r) => ['id' => (int)$r->id, 'name' => $r->name, 'sku' => $r->sku])
            ->all();
    }

    /** Replace all product assignments for a chart */
    public function syncProducts(int $chartId, array $productIds): void
    {
        \DB::table('product_size_chart')->where('size_chart_id', $chartId)->delete();
        foreach (array_unique(array_filter($productIds)) as $pid) {
            \DB::table('product_size_chart')->insert([
                'product_id'    => (int) $pid,
                'size_chart_id' => $chartId,
            ]);
        }
    }

    public function forProduct(int $productId): ?SizeChart
    {
        return SizeChart::whereHas('products', fn($q) => $q->where('products.id', $productId))
            ->with('rows')
            ->first();
    }

    public function assignToProduct(int $productId, ?int $chartId): void
    {
        \DB::table('product_size_chart')->where('product_id', $productId)->delete();
        if ($chartId) {
            \DB::table('product_size_chart')->insert([
                'product_id'    => $productId,
                'size_chart_id' => $chartId,
            ]);
        }
    }

    private function syncRows(SizeChart $chart, array $rows): void
    {
        $chart->rows()->delete();

        foreach (array_values($rows) as $i => $row) {
            if (empty($row['label'])) continue;

            SizeChartRow::create(array_merge(
                ['size_chart_id' => $chart->id, 'sort_order' => $i],
                array_map(fn($v) => $v === '' ? null : $v, $row)
            ));
        }
    }
}
