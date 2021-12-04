<?php

namespace Peace36\Whcc\Service;

use GuzzleHttp\RequestOptions;
use GuzzleHttp\ClientInterface;
use Peac36\Whcc\Requests\CreateEditorRequest;
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
        return json_decode($this->client->get('products')->getBody()->getContents(), true);
    }

    /**
     * @see https://developer.whcc.com/pages/editor-api/designs/
     *
     * @return various
     */
    public function getDesigns()
    {
        return json_decode($this->client->get('designs')->getBody()->getContents(), true);
    }

    /**
     * @see https://developer.whcc.com/pages/editor-api/create-editor/
     *
     * @param CreateEditorRequest $request
     * @return various
     */
    public function createEditor(CreateEditorRequest $request)
    {
        $response = $this->client->post('editors', [
            RequestOptions::BODY => json_encode($request->toArray()),
        ]);

        return json_decode($response->getBody()->getContents(), true);
    }

    /**
     * @see https://developer.whcc.com/pages/editor-api/complete/
     *
     * @param string $editorId
     * @return void
     */
    public function EditEditor(string $editorId)
    {
        return json_decode($this->client->post("/editors/{$editorId}/edit-link")->getBody()->getContents(), true);
    }

}