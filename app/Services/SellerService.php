<?php

namespace App\Services;

use App\Contracts\SellerRepositoryInterface;
use App\Contracts\SellerServiceInterface;
use App\Models\Seller;
use Illuminate\Contracts\Auth\Authenticatable;
use Illuminate\Database\Eloquent\Collection;

class SellerService implements SellerServiceInterface
{
    protected SellerRepositoryInterface $sellerRepository;

    public function __construct(SellerRepositoryInterface $sellerRepository)
    {
        $this->sellerRepository = $sellerRepository;
    }

    public function createSeller(Authenticatable $user): ?Seller
    {
        return $this->sellerRepository->create($user);
    }

    public function updateSeller($sellerId, array $data): Seller
    {
        return $this->sellerRepository->update($sellerId, $data);
    }

    public function deleteSeller($sellerId): ?bool
    {
        return $this->sellerRepository->delete($sellerId);
    }

    public function getAllSellers(): Collection
    {
        return $this->sellerRepository->all();
    }

    public function getSellerById($sellerId): ?Seller
    {
        return $this->sellerRepository->findById($sellerId);
    }
}
