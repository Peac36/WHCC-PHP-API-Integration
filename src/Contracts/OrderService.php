<?php

namespace Peac36\Whcc\Contracts;

interface OrderService
{
    public function getCatalog();
    public function createOrder(Request $request);
    public function confirmOrder(string $confirmation);
}