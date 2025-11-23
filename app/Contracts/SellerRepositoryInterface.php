<?php

namespace App\Contracts;

use App\Models\Seller;
use App\Models\User;
use Illuminate\Database\Eloquent\Collection;

interface SellerRepositoryInterface
{
    public function all(): Collection;

    public function create(User $user): Seller;

    public function findById(int $id): ?Seller;

    public function save(Seller $seller): Seller;

    public function update(Seller $seller): Seller;

    public function delete(int $id): bool;

}
