<?php

namespace Peac36\Whcc\Decorators;

use GuzzleHttp\RequestOptions;
use GuzzleHttp\ClientInterface;
use Psr\Http\Message\RequestInterface;
use Psr\Http\Message\ResponseInterface;
use GuzzleHttp\Promise\PromiseInterface;

class AuthHttpClientDecorator implements ClientInterface
{
    const API_RESPONSE_TOKEN_KEY = 'accessToken';

    const API_AUTHORIZATION_HEADER = 'Authorization';

    const ACCOUNT_ID_KEY = 'whcc_account_id';

    /**
     * @var GuzzleHttp\ClientInterface
     */
    protected $client;

    protected $key;

    protected $string;

    public function __construct(ClientInterface $client, string $key, string $secret)
    {
        $this->client = $client;
        $this->accessKey = $key;
        $this->accessSecret = $secret;
    }

    public function send(RequestInterface $request, array $options = []): ResponseInterface
    {
        return $this->client->{__METHOD__}(...func_get_args());
    }

    public function sendAsync(RequestInterface $request, array $options = []): PromiseInterface
    {
        return $this->client->{__METHOD__}(...func_get_args());
    }

    public function request(string $method, $uri, array $options = []): ResponseInterface
    {
        $headers = array_key_exists(RequestOptions::HEADERS, $options) ? $options[RequestOptions::HEADERS]  : [];

        if(array_key_exists(self::ACCOUNT_ID_KEY, $options)) {
            $accountId = $options[self::ACCOUNT_ID_KEY];
            $token = $this->generateAccessTokenWithAccountID($accountId);
            unset($options[self::ACCOUNT_ID_KEY]);
        } else {
            $token = $this->generateAccessToken();
        }

        $headers[self::API_AUTHORIZATION_HEADER] = $token;
        $options[RequestOptions::HEADERS] = $headers;
        return $this->client->request($method, $uri, $options);
    }

    public function requestAsync(string $method, $uri, array $options = []): PromiseInterface
    {
        return $this->client->{__METHOD__}(...func_get_args());
    }

    public function getConfig(?string $option = null)
    {
        return $this->client->{__METHOD__}(...func_get_args());
    }

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

     /**
     * Generate an access token in order to access the API
     *
     * @return  Object
     */
    private function generateAccessToken()
    {
        $response = $this->client->post('auth/access-token', [
            'body' => json_encode([
                'key' => $this->accessKey,
                'secret' => $this->accessSecret,
            ]),
        ]);

        $response = json_decode($response->getBody()->getContents(), true);
        return is_array($response) && array_key_exists(self::API_RESPONSE_TOKEN_KEY, $response) ? $response[self::API_RESPONSE_TOKEN_KEY] : null;
    }

     /**
     * Generate an access token in order to access the API
     *
     * @return  Object
     */
    public function generateAccessTokenWithAccountID($accountID)
    {
        $response = $this->client->post('auth/access-token', [
            'body' => json_encode([
                'key' => $this->accessKey,
                'secret' => $this->accessSecret,
                'claims' => [
                    'accountId' => $accountID
                ]
            ]),
        ]);

        $response = json_decode($response->getBody()->getContents(), true);

        return is_array($response) && array_key_exists(self::API_RESPONSE_TOKEN_KEY, $response) ? $response[self::API_RESPONSE_TOKEN_KEY] : null;
    }
}