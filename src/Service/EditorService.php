<?php

namespace Peac36\Whcc\Service;

use Peac36\Whcc\Utils;
use GuzzleHttp\RequestOptions;
use GuzzleHttp\ClientInterface;
use Peac36\Whcc\Contracts\Request;
use Peac36\Whcc\Decorators\AuthHttpClientDecorator;
use Peac36\Whcc\Contracts\EditorService as EditorServiceContract;

class EditorService implements EditorServiceContract
{
    protected $client;

    public function __construct(ClientInterface $client)
    {
        $this->client = $client;
    }

    /**
     * @see https://developer.whcc.com/pages/editor-api/products/
     *
     * @return various
     */
    public function getProducts()
    {
        return Utils::parseJson($this->client->get('products')->getBody()->getContents());
    }

    /**
     * @see https://developer.whcc.com/pages/editor-api/designs/
     *
     * @return various
     */
    public function getDesigns()
    {
        return Utils::parseJson($this->client->get('designs')->getBody()->getContents());
    }

    /**
     * @see https://developer.whcc.com/pages/editor-api/create-editor/
     *
     * @param Request $request
     * @return various
     */
    public function createEditor(Request $request)
    {
        $response = $this->client->post('editors', [
            RequestOptions::BODY => json_encode($request->toArray()),
        ]);

        return Utils::parseJson($response->getBody()->getContents());
    }

    /**
     * @see https://developer.whcc.com/pages/editor-api/complete/
     *
     * @param string $editorId
     * @return void
     */
    public function editEditor(string $editorId)
    {
        return Utils::parseJson($this->client->post("/editors/{$editorId}/edit-link")->getBody()->getContents());
    }

    /**
     * @see https://developer.whcc.com/pages/editor-api/order-export/
     *
     * @param string $accountId
     * @param array $editorsIds Example ["editor1","editor2", "editor3"]
     * @return various
     */
    public function exportOrders($accountId, $editorsIds)
    {
        $payload = array_values(array_map(function($editorId) {
            return ['editorId' => $editorId];
        }, $editorsIds));

        $response = $this->client->put('oas/editors/export', [
            RequestOptions::JSON => ['editors' => $payload],
            AuthHttpClientDecorator::ACCOUNT_ID_KEY => $accountId,
        ]);

        return Utils::parseJson($response->getBody()->getContents());
    }

}