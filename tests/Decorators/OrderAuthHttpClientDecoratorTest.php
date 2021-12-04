<?php

namespace Tests\Decorators;

use GuzzleHttp\Client;
use GuzzleHttp\Middleware;
use GuzzleHttp\HandlerStack;
use GuzzleHttp\Psr7\Response;
use PHPUnit\Framework\TestCase;
use GuzzleHttp\Handler\MockHandler;
use Peac36\Whcc\Decorators\OrderAuthHttpClientDecorator;

class OrderAuthHttpClientTest extends TestCase
{
    protected $client;

    protected $container;

    /** @test */
    public function it_set_authentication_token_on_api_request()
    {
        $test_token = 'test_token';

        $this->setTestClient([
            new Response(200, [], json_encode([OrderAuthHttpClientDecorator::API_RESPONSE_TOKEN_KEY => $test_token])),
            new Response(200),
        ]);

        (new OrderAuthHttpClientDecorator($this->client, 'test', 'test'))->get('/');

        $this->assertEquals("Bearer {$test_token}", $this->container[1]['request']->getHeaderLine('Authorization'));
    }


    public function setTestClient(array $responses)
    {
        $this->container = [];
        $history = Middleware::history($this->container);

        $responses = new MockHandler($responses);

        $handlerStack = HandlerStack::create($responses);
        $handlerStack->push($history);
        $this->client = new Client(['handler' => $handlerStack]);
    }
}