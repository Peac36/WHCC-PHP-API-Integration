<?php

use GuzzleHttp\Client;
use GuzzleHttp\HandlerStack;
use GuzzleHttp\Psr7\Response;
use PHPUnit\Framework\TestCase;
use GuzzleHttp\Handler\MockHandler;
use Peac36\Whcc\Service\EditorService;

class EditorServiceTest extends TestCase
{
    /** @test  */
    public function it_can_fetch_products()
    {
        $response_body = ['product_key' => 'product_value'];

        $service = $this->prepareTheService([
            new Response(200, [], json_encode($response_body)),
        ]);

        $products = $service->getProducts();

        $this->assertEquals($response_body, $products);
    }

    /** @test  */
    public function it_can_fetch_designs()
    {
        $response_body = ['design_key' => 'design_value'];

        $service = $this->prepareTheService([
            new Response(200, [], json_encode($response_body)),
        ]);

        $products = $service->getProducts();

        $this->assertEquals($response_body, $products);
    }

    public function prepareTheService(array $responses)
    {
        $mock = new MockHandler($responses);
        $handlerStack = HandlerStack::create($mock);
        $client = new Client(['handler' => $handlerStack]);
        return new EditorService($client);
    }
}