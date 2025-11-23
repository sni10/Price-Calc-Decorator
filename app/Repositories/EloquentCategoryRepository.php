<?php

namespace App\Repositories;

use App\Contracts\CategoryRepositoryInterface;
use App\Models\Category;
use Illuminate\Database\Eloquent\Collection;

class EloquentCategoryRepository implements CategoryRepositoryInterface
{
    public function all(): Collection
    {
        return Category::all();
    }

    public function create(array $data): Category
    {
        return Category::create($data);
    }

    public function findById(int $id): ?Category
    {
        return Category::findOrFail($id);
    }

    public function save(Category $category): Category
    {
        $category->save();
        return $category;
    }

    public function update(Category $category): Category
    {
        $category->save();
        return $category;
    }

    public function delete(int $id): bool
    {
        return Category::destroy($id);
    }
}
