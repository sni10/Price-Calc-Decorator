<?php

namespace App\Contracts;

use App\Models\Location;
use Illuminate\Database\Eloquent\Collection;

interface LocationRepositoryInterface
{
    public function all(): Collection;

    public function findById(int $id): ?Location;

    public function save(Location $location): Location;

    public function update(Location $location): Location;

    public function delete(int $id): bool;
}
