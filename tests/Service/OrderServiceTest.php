<?php

use GuzzleHttp\Client;
use GuzzleHttp\HandlerStack;
use GuzzleHttp\Psr7\Response;
use PHPUnit\Framework\TestCase;
use GuzzleHttp\Handler\MockHandler;
use Peac36\Whcc\Requests\CreateOrderRequest;
use Peac36\Whcc\Service\OrderService;

class OrderServiceTest extends TestCase
{
    /** @test */
    public function it_can_get_catalog_data()
    {
        $expected_response = ['test' => 'test'];
        $service =$this->mockAPIResponses([
            new Response(200, [], json_encode($expected_response)),
        ]);
        $response = $service->getCatalog();

        $this->assertEquals($expected_response, $response);
    }

    /** @test */
    public function it_can_create_an_order()
    {
        $expected_response = ['test' => 'order_created'];

        $service =$this->mockAPIResponses([
            new Response(200, [], json_encode($expected_response)),
        ]);

        $response = $service->createOrder(new CreateOrderRequest);

        $this->assertEquals($expected_response, $response);
    }

    /** @test */
    public function it_can_confirm_an_order()
    {
        $expected_response = ['test' => 'order_confirmed'];
        $service =$this->mockAPIResponses([
            new Response(200, [], json_encode($expected_response)),
        ]);

        $response = $service->confirmOrder('test');

        $this->assertEquals($expected_response, $response);
    }

    public function mockAPIResponses(array $responses)
    {
        $mock = new MockHandler($responses);
        $handlerStack = HandlerStack::create($mock);
        $client = new Client(['handler' => $handlerStack]);
        return new OrderService($client);
    }
}