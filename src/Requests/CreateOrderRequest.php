<?php

namespace Peac36\Whcc\Requests;

use Peac36\Whcc\Contracts\Request;

class CreateOrderRequest implements Request
{
    protected $entityId;

    protected $items = array();

    public function setEntityId($id)
    {
        $this->entityId = $id;;
        return $this;
    }

    public function getEntityId()
    {
        return $this->entityId;
    }

    public function appendOrderItem(array $item)
    {
        $this->items[] = $item;
        return $this;
    }

    public function removeOrderItem($seqNumber)
    {
        $this->items = array_values(array_filter($this->items, function($item) use($seqNumber) {
            return $item['SequenceNumber'] != $seqNumber;
        }));
    }

    public function getOrderItems()
    {
        return $this->items;
    }

    public function toArray(): array
    {
        return [];
    }
}