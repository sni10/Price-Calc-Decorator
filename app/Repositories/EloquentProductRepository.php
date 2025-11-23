<?php

namespace App\Repositories;

use App\Contracts\ProductRepositoryInterface;
use Illuminate\Database\Eloquent\Collection;
use App\Models\Product;

class EloquentProductRepository implements ProductRepositoryInterface
{
    public function all(): Collection
    {
        return Product::all();
    }

    public function findById(int $id): ?Product
    {
        return Product::findOrFail($id);
    }

    public function save(Product $product): Product
    {
        $product->save();
        return $product;
    }

    public function update(Product $product): Product
    {
        $product->save();
        return $product;
    }

    public function delete(int $id): bool
    {
        return Product::destroy($id);
    }
}
