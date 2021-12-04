<?php

namespace Tests\Requests;

use PHPUnit\Framework\TestCase;
use Peac36\Whcc\Requests\CreateEditorRequest;

class CreateEditorRequestTest extends TestCase
{
    protected $request;

    protected function setUp(): void
    {
        $this->request = new CreateEditorRequest;
    }

    /** @test */
    public function it_can_set_user_id()
    {
        $this->request->setUserId("test");
        $this->assertEquals("test", $this->request->getUserId());
    }

    /** @test */
    public function it_can_set_product_id()
    {
        $this->request->setProductId("test");
        $this->assertEquals("test", $this->request->getProductId());
    }

    /** @test */
    public function it_can_set_design_id()
    {
        $this->request->setDesignId("test");
        $this->assertEquals("test", $this->request->getDesignId());
    }

    /** @test */
    public function it_can_set_complete_redirect()
    {
        $this->request->setCompleteRedirect(["test"]);
        $this->assertEquals(["test"], $this->request->getCompleteRedirect());
    }

    /** @test */
    public function it_can_set_cancel_redirect()
    {
        $this->request->setCancelRedirect(["test"]);
        $this->assertEquals(["test"], $this->request->getCancelRedirect());
    }

    /** @test */
    public function it_can_set_settings()
    {
        $this->request->setSettings(["test"]);
        $this->assertEquals(["test"], $this->request->getSettings());
    }

    public function it_can_set_photos()
    {
        $this->request->setPhotos(["test"]);
        $this->assertEquals(["test"], $this->request->getPhotos());
    }
}