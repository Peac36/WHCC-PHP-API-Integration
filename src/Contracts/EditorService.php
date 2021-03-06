<?php

namespace Peac36\Whcc\Contracts;

interface EditorService {
    public function getProducts();
    public function getDesigns();
    public function createEditor(Request $request);
    public function editEditor(string $editorId);
    public function exportOrders($accountId, $editorsIds);
}