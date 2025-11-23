<?php

namespace App\Contracts;

use App\Models\Order;
use App\Models\Seller;
use Illuminate\Database\Eloquent\Collection;

interface OrderServiceInterface
{
    public function createOrder(Seller $seller): ?Order;

    public function updateOrder($orderId, array $data): Order;

    public function deleteOrder($orderId): ?bool;

    public function getAllOrder(): Collection;

    public function getOrderById($orderId): ?Order;
}
