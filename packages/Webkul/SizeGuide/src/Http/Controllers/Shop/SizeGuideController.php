<?php

namespace Webkul\SizeGuide\Http\Controllers\Shop;

use Illuminate\Contracts\View\View;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Webkul\Product\Repositories\ProductRepository;
use Webkul\SizeGuide\Repositories\SizeChartRepository;

class SizeGuideController extends Controller
{
    public function __construct(
        protected SizeChartRepository $repo,
        protected ProductRepository $productRepo,
    ) {}

    public function page(int $productId): View|\Illuminate\Http\RedirectResponse
    {
        $product = $this->productRepo->find($productId);
        $chart   = $this->repo->forProduct($productId);

        if (! $product || ! $chart) {
            return redirect()->back();
        }

        return view('size-guide::shop.size-guide-page', compact('product', 'chart'));
    }

    public function show(int $productId): JsonResponse
    {
        $chart = $this->repo->forProduct($productId);

        if (! $chart) {
            return response()->json(['found' => false]);
        }

        return response()->json([
            'found' => true,
            'chart' => [
                'id'     => $chart->id,
                'name'   => $chart->name,
                'gender' => $chart->gender,
                'type'   => $chart->type,
                'rows'   => $chart->rows->map(fn($r) => [
                    'label'   => $r->label,
                    'eu'      => $r->eu_size,
                    'uk'      => $r->uk_size,
                    'us'      => $r->us_size,
                    'chest'   => ['min' => $r->chest_min,  'max' => $r->chest_max],
                    'waist'   => ['min' => $r->waist_min,  'max' => $r->waist_max],
                    'hips'    => ['min' => $r->hips_min,   'max' => $r->hips_max],
                    'height'  => ['min' => $r->height_min, 'max' => $r->height_max],
                    'p_chest' => $r->product_chest,
                    'p_waist' => $r->product_waist,
                    'p_length'=> $r->product_length,
                    'p_shoulder'=> $r->product_shoulder,
                ]),
            ],
        ]);
    }

    /** Returns recommended size based on customer measurements */
    public function recommend(Request $request, int $productId): JsonResponse
    {
        $request->validate([
            'chest'  => 'nullable|numeric|min:40|max:200',
            'waist'  => 'nullable|numeric|min:40|max:200',
            'hips'   => 'nullable|numeric|min:40|max:200',
        ]);

        $chart = $this->repo->forProduct($productId);
        if (! $chart) return response()->json(['size' => null]);

        $chest = (float) $request->chest;
        $waist = (float) $request->waist;
        $hips  = (float) $request->hips;

        $best = null;
        $bestScore = PHP_INT_MAX;

        foreach ($chart->rows as $row) {
            $score = 0;
            $matches = 0;

            foreach (['chest', 'waist', 'hips'] as $field) {
                $val = $$field;
                $min = $row->{$field . '_min'};
                $max = $row->{$field . '_max'};
                if ($val && $min !== null) {
                    $mid = ($min + ($max ?? $min)) / 2;
                    $score += abs($val - $mid);
                    $matches++;
                }
            }

            if ($matches === 0) continue;
            if ($score < $bestScore) {
                $bestScore = $score;
                $best = $row;
            }
        }

        return response()->json([
            'size' => $best?->label,
            'eu'   => $best?->eu_size,
        ]);
    }
}
