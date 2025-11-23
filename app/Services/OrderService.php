<?php

namespace App\Services;

use App\Contracts\OrderRepositoryInterface;
use App\Contracts\OrderServiceInterface;
use App\Models\Order;
use App\Models\Seller;
use Illuminate\Database\Eloquent\Collection;

class OrderService implements OrderServiceInterface
{
    protected OrderRepositoryInterface $orderRepository;

    public function __construct(OrderRepositoryInterface $orderRepository)
    {
        $this->orderRepository = $orderRepository;
    }

    public function createOrder(Seller $seller): ?Order
    {
        return $this->orderRepository->create($seller);
    }

    public function updateOrder($orderId, array $data): Order
    {
        $order = $this->orderRepository->findById($orderId);
        return $this->orderRepository->update($order, $data);
    }

    public function deleteOrder($orderId): ?bool
    {
        $order = $this->orderRepository->findById($orderId);
        return $this->orderRepository->delete($order);
    }

    public function getAllOrder(): Collection
    {
        return $this->orderRepository->all();
    }

    public function getOrderById($orderId): ?Order
    {
        $orderId = (int) $orderId;
        return $this->orderRepository->findById($orderId);
    }
}
