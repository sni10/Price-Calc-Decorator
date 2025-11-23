<?php

namespace App\Repositories;

use App\Contracts\OrderRepositoryInterface;
use App\Models\Order;
use App\Models\Seller;
use Illuminate\Database\Eloquent\Collection;

class EloquentOrderRepository implements OrderRepositoryInterface
{
    public function all(): Collection
    {
        return Order::all();
    }

    public function create(Seller $seller): Order
    {
        $order = Order::create();
        $order->seller()->associate($seller);
        $order->save();
        return $order;
    }

    public function findById(int $id): ?Order
    {
        return Order::findOrFail($id);
    }

    public function save(Order $order): Order
    {
        $order->save();
        return $order;
    }

    public function update(Order $order, array $data): Order
    {
        $order->update($data);
        return $order;
    }

    public function delete(Order $order): bool
    {
        return $order->delete();
    }

}
