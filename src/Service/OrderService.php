<?php

namespace Peace36\Whcc\Service;

use GuzzleHttp\ClientInterface;
use GuzzleHttp\RequestOptions;
use Peac36\Whcc\Contracts\OrderService as OrderServiceContract;
use Peac36\Whcc\Contracts\Request;

class OrderService implements OrderServiceContract
{
    protected $client;

    public function __construct(ClientInterface $client)
    {
        $this->client = $client;
    }

    public function getCatalog()
    {
        return json_decode($this->client->get('catalog')->getBody()->getContents(), true);
    }

    public function createOrder(Request $request)
    {
        return $this->client->post('OrderImport', [
            RequestOptions::BODY => $request->toArray(),
        ]);
    }

    public function confirmOrder(string $confirmation)
    {
        return $this->client->post("OrderImport/Submit/{$confirmation}");
    }
}