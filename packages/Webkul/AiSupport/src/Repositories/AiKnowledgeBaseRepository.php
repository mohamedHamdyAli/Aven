<?php

namespace Webkul\AiSupport\Repositories;

use Webkul\Core\Eloquent\Repository;
use Webkul\AiSupport\Contracts\AiKnowledgeBase;

class AiKnowledgeBaseRepository extends Repository
{
    public function model(): string
    {
        return AiKnowledgeBase::class;
    }

    public function getActiveEntries(): \Illuminate\Database\Eloquent\Collection
    {
        return $this->model
            ->where('is_active', true)
            ->orderBy('sort_order')
            ->orderBy('id')
            ->get();
    }
}
