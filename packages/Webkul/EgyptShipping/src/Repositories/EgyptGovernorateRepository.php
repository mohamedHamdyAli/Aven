<?php

namespace Webkul\EgyptShipping\Repositories;

use Webkul\EgyptShipping\Models\EgyptGovernorate;

class EgyptGovernorateRepository
{
    public function all(): \Illuminate\Database\Eloquent\Collection
    {
        return EgyptGovernorate::orderBy('name_en')->get();
    }

    public function find(int $id): ?EgyptGovernorate
    {
        return EgyptGovernorate::find($id);
    }

    public function update(int $id, array $data): bool
    {
        return (bool) EgyptGovernorate::where('id', $id)->update($data);
    }

    public function findByCode(string $code): ?EgyptGovernorate
    {
        return EgyptGovernorate::where('code', $code)->first();
    }

    public function bulkUpdateRate(array $ids, float $rate): void
    {
        EgyptGovernorate::whereIn('id', $ids)->update(['rate' => $rate]);
    }
}
