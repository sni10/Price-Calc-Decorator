<?php

namespace App\Contracts;

use App\Models\Product;
use Illuminate\Database\Eloquent\Collection;

interface ProductRepositoryInterface
{
    public function all(): Collection;

    public function findById(int $id): ?Product;

    public function save(Product $product): Product;

    public function update(Product $product): Product;

    public function delete(int $id): bool;

}
