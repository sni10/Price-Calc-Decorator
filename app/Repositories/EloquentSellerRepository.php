<?php

namespace App\Repositories;

use App\Contracts\SellerRepositoryInterface;
use Illuminate\Contracts\Auth\Authenticatable;
use Illuminate\Database\Eloquent\Collection;
use App\Models\Seller;

class EloquentSellerRepository implements SellerRepositoryInterface
{
    public function all(): Collection
    {
        return Seller::all();
    }

    public function create(Authenticatable $user): Seller
    {
        return Seller::create(
            [
                'user_id' => $user->getAuthIdentifier(),
                'name' => $user->name,
                'personal_discount' => 11  // конечно мы можем получать это извне
            ]
        );
    }

    public function findById(int $id): ?Seller
    {
        return Seller::findOrFail($id);
    }

    public function save(Seller $seller): Seller
    {
        $seller->save();
        return $seller;
    }

    public function update(Seller $seller): Seller
    {
        $seller->save();
        return $seller;
    }

    public function delete(int $id): bool
    {
        return Seller::destroy($id);
    }
}
