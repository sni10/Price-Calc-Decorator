<?php

namespace App\Contracts;

use App\Models\Seller;
use Illuminate\Contracts\Auth\Authenticatable;
use Illuminate\Database\Eloquent\Collection;

interface SellerServiceInterface
{
    public function createSeller(Authenticatable $user): ?Seller;

    public function updateSeller($sellerId, array $data): Seller;

    public function deleteSeller($sellerId): ?bool;

    public function getAllSellers(): Collection;

    public function getSellerById($sellerId): ?Seller;
}
