<?php

namespace Webkul\EgyptShipping\Http\Controllers\Admin;

use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Webkul\EgyptShipping\Repositories\EgyptGovernorateRepository;

class EgyptShippingController extends Controller
{
    public function __construct(protected EgyptGovernorateRepository $repository) {}

    public function index()
    {
        $governorates = $this->repository->all();

        $stats = [
            'total'    => $governorates->count(),
            'active'   => $governorates->where('is_active', true)->count(),
            'priced'   => $governorates->whereNotNull('rate')->count(),
        ];

        return view('egypt-shipping::admin.egypt-shipping.index', compact('governorates', 'stats'));
    }

    public function update(Request $request, int $id): JsonResponse
    {
        $data = $request->validate([
            'rate'      => 'nullable|numeric|min:0',
            'is_active' => 'boolean',
        ]);

        $this->repository->update($id, $data);

        return response()->json(['success' => true]);
    }

    public function bulkUpdate(Request $request): JsonResponse
    {
        $data = $request->validate([
            'ids'   => 'required|array|min:1',
            'ids.*' => 'integer',
            'rate'  => 'required|numeric|min:0',
        ]);

        $this->repository->bulkUpdateRate($data['ids'], $data['rate']);

        return response()->json(['success' => true, 'updated' => count($data['ids'])]);
    }
}
