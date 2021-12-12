<?php

use Peac36\Whcc\Requests\CreateOrderRequest;
use PHPUnit\Framework\TestCase;

class CreateOrderRequestTest extends TestCase
{
    protected $request;

    protected function setUp(): void
    {
        $this->request = new CreateOrderRequest;
    }

    /** @test */
    public function it_can_set_entity_id()
    {
        $this->request->setEntityId('test');
        $this->assertEquals("test", $this->request->getEntityId());
    }

    /** @test */
    public function it_can_append_order_item()
    {
        $this->request->appendOrderItem(['test']);
        $this->request->appendOrderItem(['test1']);
        $this->assertCount(2, $this->request->getOrderItems());
    }

    /** @test */
    public function it_can_remove_order_item()
    {
        $this->request->appendOrderItem(['SequenceNumber' => 'test']);
        $this->request->appendOrderItem(['SequenceNumber' => 'test1']);
        $this->request->appendOrderItem(['SequenceNumber' => 'test3']);

        $this->request->removeOrderItem('test1');

        $items = $this->request->getOrderItems();

        $this->assertCount(2, $this->request->getOrderItems());
        $this->assertEquals('test', $items[0]['SequenceNumber']);
        $this->assertEquals('test3', $items[1]['SequenceNumber']);
    }
}