<?php

namespace Webkul\StoreLocator\Repositories;

use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Webkul\Core\Eloquent\Repository;

class StoreLocatorRepository extends Repository
{
    public function model(): string
    {
        return 'Webkul\StoreLocator\Contracts\StoreLocator';
    }

    public function create(array $data): mixed
    {
        if (isset($data['image']) && $data['image'] instanceof UploadedFile) {
            $data['image'] = $data['image']->store('store-locator', 'public');
        } else {
            unset($data['image']);
        }

        return parent::create($data);
    }

    public function update(array $data, $id): mixed
    {
        $branch = $this->findOrFail($id);

        if (isset($data['image']) && $data['image'] instanceof UploadedFile) {
            if ($branch->image) {
                Storage::disk('public')->delete($branch->image);
            }

            $data['image'] = $data['image']->store('store-locator', 'public');
        } else {
            unset($data['image']);
        }

        return parent::update($data, $id);
    }
}
