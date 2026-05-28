<?php

namespace Webkul\AiSupport\Http\Controllers\Admin;

use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Routing\Controller;
use Webkul\AiSupport\DataGrids\KnowledgeBaseDataGrid;
use Webkul\AiSupport\Repositories\AiKnowledgeBaseRepository;

class KnowledgeBaseController extends Controller
{
    public function __construct(protected AiKnowledgeBaseRepository $kbRepository) {}

    public function index(): mixed
    {
        if (request()->ajax()) {
            return app(KnowledgeBaseDataGrid::class)->toJson();
        }

        return view('ai-support::admin.knowledge-base.index');
    }

    public function store(): RedirectResponse
    {
        request()->validate([
            'question'   => 'required|string|max:500',
            'answer'     => 'required|string',
            'sort_order' => 'nullable|integer|min:0',
        ]);

        $this->kbRepository->create([
            'question'   => request('question'),
            'answer'     => request('answer'),
            'is_active'  => (bool) request('is_active', true),
            'sort_order' => (int) request('sort_order', 0),
        ]);

        session()->flash('success', 'Knowledge base entry created.');

        return redirect()->route('admin.ai-support.knowledge-base.index');
    }

    public function update(int $id): RedirectResponse
    {
        request()->validate([
            'question'   => 'required|string|max:500',
            'answer'     => 'required|string',
            'sort_order' => 'nullable|integer|min:0',
        ]);

        $this->kbRepository->update([
            'question'   => request('question'),
            'answer'     => request('answer'),
            'is_active'  => (bool) request('is_active', true),
            'sort_order' => (int) request('sort_order', 0),
        ], $id);

        session()->flash('success', 'Knowledge base entry updated.');

        return redirect()->route('admin.ai-support.knowledge-base.index');
    }

    public function destroy(int $id): JsonResponse
    {
        $this->kbRepository->delete($id);

        return response()->json(['message' => 'Entry deleted.']);
    }
}
