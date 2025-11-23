<?php

namespace App\Repositories;

use App\Contracts\LocationRepositoryInterface;
use App\Models\Location;
use Illuminate\Database\Eloquent\Collection;

class EloquentLocationRepository implements LocationRepositoryInterface
{
    public function all(): Collection
    {
        return Location::all();
    }

    public function findById(int $id): ?Location
    {
        return Location::findOrFail($id);
    }

    public function save(Location $location ): Location
    {
        $location->save();
        return $location;
    }

    public function update(Location $location): Location
    {
        $location->save();
        return $location;
    }

    public function delete(int $id): bool
    {
        return Location::destroy($id);
    }
}
