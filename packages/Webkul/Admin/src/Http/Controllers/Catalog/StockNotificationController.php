<?php

namespace Webkul\Admin\Http\Controllers\Catalog;

use Illuminate\Http\JsonResponse;
use Webkul\Admin\DataGrids\Catalog\StockNotificationDataGrid;
use Webkul\Admin\Http\Controllers\Controller;
use Webkul\Product\Models\StockNotification;

class StockNotificationController extends Controller
{
    public function index()
    {
        if (request()->ajax()) {
            return app(StockNotificationDataGrid::class)->toJson();
        }

        return view('admin::catalog.stock-notifications.index');
    }

    public function destroy(int $id): JsonResponse
    {
        StockNotification::findOrFail($id)->delete();

        return new JsonResponse(['message' => 'Subscription deleted.']);
    }

    public function massDestroy(): JsonResponse
    {
        $ids = explode(',', request()->input('indices', ''));

        StockNotification::whereIn('id', $ids)->delete();

        return new JsonResponse(['message' => count($ids).' subscriptions deleted.']);
    }
}
