<?php

namespace App\Contracts;

use App\Models\Category;
use Illuminate\Database\Eloquent\Collection;

interface CategoryRepositoryInterface
{
    public function all(): Collection;

    public function findById(int $id): ?Category;

    public function save(Category $category): Category;

    public function update(Category $category): Category;

    public function create(array $data): Category;

    public function delete(int $id): bool;
}
