<?php

namespace Peac36\Whcc\Service;

use Peac36\Whcc\Utils;
use GuzzleHttp\RequestOptions;
use GuzzleHttp\ClientInterface;
use Peac36\Whcc\Contracts\Request;
use Peac36\Whcc\Contracts\OrderService as OrderServiceContract;

class OrderService implements OrderServiceContract
{
    protected $client;

    public function __construct(ClientInterface $client)
    {
        $this->client = $client;
    }

    public function getCatalog()
    {
        return Utils::parseJson($this->client->get('catalog')->getBody()->getContents());
    }

    public function createOrder(Request $request)
    {
        $response = $this->client->post('OrderImport', [
            RequestOptions::FORM_PARAMS => $request->toArray(),
        ]);
        return Utils::parseJson($response->getBody()->getContents());
    }

    public function confirmOrder(string $confirmation)
    {
        $response = $this->client->post("OrderImport/Submit/{$confirmation}");
        return Utils::parseJson($response->getBody()->getContents());
    }
}