<?php

namespace Webkul\Admin\Http\Controllers;

use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Webkul\Admin\Repositories\ChannelAdSpendRepository;

class ChannelAdSpendController extends Controller
{
    public function __construct(protected ChannelAdSpendRepository $channelAdSpendRepository) {}

    public function index(): JsonResponse
    {
        return response()->json(
            $this->channelAdSpendRepository->with('channel')->orderByDesc('start_date')->get()
        );
    }

    public function store(Request $request): JsonResponse
    {
        $data = $request->validate([
            'channel_id' => 'required|exists:channels,id',
            'amount'     => 'required|numeric|min:0',
            'start_date' => 'required|date',
            'end_date'   => 'required|date|after_or_equal:start_date',
            'source'     => 'in:manual,auto',
            'notes'      => 'nullable|string|max:255',
        ]);

        $record = $this->channelAdSpendRepository->create($data);

        return response()->json($record->load('channel'), 201);
    }

    public function update(Request $request, int $id): JsonResponse
    {
        $data = $request->validate([
            'amount'     => 'required|numeric|min:0',
            'start_date' => 'required|date',
            'end_date'   => 'required|date|after_or_equal:start_date',
            'source'     => 'in:manual,auto',
            'notes'      => 'nullable|string|max:255',
        ]);

        $record = $this->channelAdSpendRepository->update($data, $id);

        return response()->json($record->load('channel'));
    }

    public function destroy(int $id): JsonResponse
    {
        $this->channelAdSpendRepository->delete($id);

        return response()->json(['message' => 'Deleted successfully']);
    }
}
