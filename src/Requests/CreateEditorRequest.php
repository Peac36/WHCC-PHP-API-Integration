<?php

namespace Peac36\Whcc\Requests;

use Peac36\Whcc\Contracts\Request;

class CreateEditorRequest implements Request
{
    protected $userId;

    protected $productId;

    protected $designId;

    protected $redirect = ['complete' => [], 'cancel' => []];

    protected $settings = [];

    protected $photos = [];

    public function toArray() : array
    {
        return [
            "userId" => $this->userId,
            "productId" => $this->productId,
            "designId" => $this->designId,
            "redirects" => [
                "complete" => $this->redirect['complete'],
                "cancel" => $this->redirect['cancel'],
            ],
            "settings" => $this->settings,
            "photos" => $this->photos,
        ];
    }

    public function setUserId($id)
    {
        $this->userId = $id;
        return $this;
    }

    public function getUserId()
    {
        return $this->userId;
    }

    public function setProductId($productId)
    {
        $this->productId = $productId;
        return $this;
    }

    public function getProductId()
    {
        return $this->productId;
    }

    public function setDesignId($designId)
    {
        $this->designId = $designId;
        return $this;
    }

    public function getDesignId()
    {
        return $this->designId;
    }

    public function setCompleteRedirect(array $redirect)
    {
        $this->redirect['complete'] = $redirect;
        return $this;
    }

    public function getCompleteRedirect()
    {
        return $this->redirect['complete'];
    }

    public function setCancelRedirect(array $redirect)
    {
        $this->redirect['cancel'] = $redirect;
        return $this;
    }

    public function getCancelRedirect()
    {
        return $this->redirect['cancel'];
    }

    public function setSettings(array $settings)
    {
        $this->settings = $settings;
        return $this;
    }

    public function getSettings()
    {
        return $this->settings;
    }

    public function setPhotos(array $photos)
    {
        $this->photos = $photos;
        return $this;
    }

    public function getPhotos()
    {
        return $this->photos;
    }
}