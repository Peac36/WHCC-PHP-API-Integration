<?php

namespace Peac36\Whcc\Contracts;

use Peac36\Whcc\Requests\CreateOrderRequest;

interface OrderService
{
    public function getCatalog();
    public function createOrder(CreateOrderRequest $request);
    public function confirmOrder(string $confirmation);
}