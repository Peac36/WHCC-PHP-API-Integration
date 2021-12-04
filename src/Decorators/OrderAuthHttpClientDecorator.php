<?php

namespace Peac36\Whcc\Decorators;

use GuzzleHttp\RequestOptions;
use GuzzleHttp\ClientInterface;
use Psr\Http\Message\RequestInterface;
use Psr\Http\Message\ResponseInterface;
use GuzzleHttp\Promise\PromiseInterface;

class OrderAuthHttpClientDecorator implements ClientInterface
{
    const API_RESPONSE_TOKEN_KEY = 'Token';

    const API_AUTHORIZATION_HEADER = 'Authorization';

    /**
     * @var ClientInterface
     */
    protected $client;

    protected $key;

    protected $secret;

    protected $type;


    public function __construct(ClientInterface $client, string $key, string $secret, string $type = 'consumer_credentials')
    {
        $this->client = $client;
        $this->key = $key;
        $this->secret = $secret;
        $this->type = $type;
    }

    public function send(RequestInterface $request, array $options = []) : ResponseInterface
    {
        return $this->client->{__METHOD__}(...func_get_args());
    }

    public function sendAsync(RequestInterface $request, array $options = []) : PromiseInterface
    {
        return $this->client->{__METHOD__}(...func_get_args());
    }

    public function request($method, $uri, array $options = []) : ResponseInterface
    {
        $token = $this->getAuthToken();
        $headers = array_key_exists(RequestOptions::HEADERS, $options) ? $options[RequestOptions::HEADERS]  : [];
        $headers[self::API_AUTHORIZATION_HEADER] = "Bearer {$token}";
        $options[RequestOptions::HEADERS] = $headers;

        return $this->client->request($method, $uri, $options);
    }

    public function requestAsync($method, $uri, array $options = []) : PromiseInterface
    {
        return $this->client->{__METHOD__}(...func_get_args());
    }

    public function getConfig($option = null)
    {
        return $this->client->{__METHOD__}(...func_get_args());
    }

    /**
     * @param string $method
     * @param array  $args
     *
     * @return Promise\PromiseInterface
     */
    public function __call($method, $args)
    {
        if (count($args) < 1) {
            throw new \InvalidArgumentException('Magic request methods require a URI and optional options array');
        }

        $uri = $args[0];
        $opts = isset($args[1]) ? $args[1] : [];
        return substr($method, -5) === 'Async'
            ? $this->requestAsync(substr($method, 0, -5), $uri, $opts)
            : $this->request($method, $uri, $opts);
    }

    protected function getAuthToken()
    {
        $response = $this->client->request( 'GET' ,'/api/AccessToken', [
            RequestOptions::FORM_PARAMS => [
                'grant_type' => $this->type,
                'consumer_key' => $this->key,
                'consumer_secret' => $this->secret,
            ]
        ]);

        $response = json_decode($response->getBody()->getContents(), true);
        return is_array($response) && array_key_exists(self::API_RESPONSE_TOKEN_KEY, $response) ? $response[self::API_RESPONSE_TOKEN_KEY] : null;;
    }
}