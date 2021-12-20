<?php

use GuzzleHttp\Client;
use GuzzleHttp\Middleware;
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

        $service = $this->mockAPIResponses([
            new Response(200, [], json_encode($response_body)),
        ]);

        $products = $service->getProducts();

        $this->assertEquals($response_body, $products);
    }

    /** @test  */
    public function it_can_fetch_designs()
    {
        $response_body = ['design_key' => 'design_value'];

        $service = $this->mockAPIResponses([
            new Response(200, [], json_encode($response_body)),
        ]);

        $products = $service->getProducts();

        $this->assertEquals($response_body, $products);
    }

    /** @test  */
    public function it_can_generate_edit_editor_url()
    {
        $response_body = ['url' => '#'];

        $service = $this->mockAPIResponses([
            new Response(200, [], json_encode($response_body)),
        ]);

        $url = $service->editEditor(5);

        $this->assertEquals($response_body, $url);
    }

    /** @test  */
    public function it_can_export_orders()
    {
        $response_body = ['example_body' => 'example_body'];

        $service = $this->mockAPIResponses([
            new Response(200, [], json_encode($response_body)),
        ]);

        $orders = $service->exportOrders(1, [1,2,3,4]);

        $this->assertEquals($response_body ,$orders);
    }

    /** @test  */
    public function it_format_editors_ids_whenever_export_orders()
    {
        $service = $this->storeAPIRequest([
            new Response(200, [],'test-response'),
        ]);

        $service->exportOrders(1, [1,2,3,4,5]);
        $expected_data = ['editors'   => [
            ['editorId' => 1],
            ['editorId' => 2],
            ['editorId' => 3],
            ['editorId' => 4],
            ['editorId' => 5],

        ]];
        $formatted_data = $this->container[0]['request']->getBody()->getContents();

        $this->assertEquals($expected_data, json_decode($formatted_data, true));
    }

    public function storeAPIRequest(array $responses)
    {
        $this->container = [];
        $history = Middleware::history($this->container);

        $responses = new MockHandler($responses);

        $handlerStack = HandlerStack::create($responses);
        $handlerStack->push($history);
        $client = new Client(['handler' => $handlerStack]);

        return new EditorService($client);
    }

    public function mockAPIResponses(array $responses)
    {
        $mock = new MockHandler($responses);
        $handlerStack = HandlerStack::create($mock);
        $client = new Client(['handler' => $handlerStack]);
        return new EditorService($client);
    }
}