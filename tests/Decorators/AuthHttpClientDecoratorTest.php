<?php

namespace Tests\Decorators;

use GuzzleHttp\Client;
use GuzzleHttp\Middleware;
use GuzzleHttp\HandlerStack;
use GuzzleHttp\Psr7\Response;
use PHPUnit\Framework\TestCase;
use GuzzleHttp\Handler\MockHandler;
use Peac36\Whcc\Decorators\AuthHttpClientDecorator;

class AuthHttpDecoratorTest extends TestCase
{
    /** @test */
    public function it_set_authentication_token_on_api_request()
    {
        $api_token = 'test_token';

        $this->setTestClient([
            new Response(200, [], json_encode([AuthHttpClientDecorator::API_RESPONSE_TOKEN_KEY => $api_token])),
            new Response(200),
        ]);

        $authClient = new AuthHttpClientDecorator($this->client, 'test', 'test');

        $authClient->post('/test');
        $this->assertEquals($api_token, $this->container[1]['request']->getHeaderLine('Authorization'));
    }

    /** @test */
    public function it_set_authentication_account_token_on_api_request()
    {
        $api_token = 'account_token';

        $this->setTestClient([
            new Response(200, [], json_encode([AuthHttpClientDecorator::API_RESPONSE_TOKEN_KEY => $api_token])),
            new Response(200),
        ]);

        $authClient = new AuthHttpClientDecorator($this->client, 'test', 'test');

        $authClient->post('/test', [
            AuthHttpClientDecorator::ACCOUNT_ID_KEY => $api_token
        ]);
        $this->assertEquals($api_token, $this->container[1]['request']->getHeaderLine('Authorization'));
    }

    /** @test */
    public function it_removes_the_account_flag_from_api_request()
    {
        $api_token = 'account_token';

        $this->setTestClient([
            new Response(200,[], json_encode([AuthHttpClientDecorator::API_RESPONSE_TOKEN_KEY => $api_token])),
            new Response(200),
        ]);

        $authClient = new AuthHttpClientDecorator($this->client, 'test', 'test');

        $authClient->post('/test',[
                AuthHttpClientDecorator::ACCOUNT_ID_KEY => $api_token
        ]);

        //dump($this->container[1]['request']->getHeaders());
        $this->assertArrayNotHasKey(AuthHttpClientDecorator::ACCOUNT_ID_KEY, $this->container[1]['request']->getHeaders());
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
