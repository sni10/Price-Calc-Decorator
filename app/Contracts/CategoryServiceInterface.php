<?php

namespace App\Contracts;

use App\Models\Category;
use Illuminate\Database\Eloquent\Collection;

interface CategoryServiceInterface
{
    public function createCategory(array $data): ?Category;

    public function updateCategory($categoryId, array $data): Category;

    public function deleteCategory($categoryId): ?bool;

    public function getAllCategories(): Collection;

    public function getCategoryById($categoryId): ?Category;
}
