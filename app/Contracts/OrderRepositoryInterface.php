<?php

namespace App\Contracts;

use App\Models\Order;
use Illuminate\Database\Eloquent\Collection;

interface OrderRepositoryInterface
{
    public function all(): Collection;

    public function findById(int $id): ?Order;

    public function save(Order $order): Order;

    public function update(Order $order, array $data): Order;

    public function delete(Order $order): bool;
}
