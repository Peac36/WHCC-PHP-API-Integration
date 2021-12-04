<?php

namespace Peac36\Whcc\Contracts;

use Peac36\Whcc\Requests\CreateEditorRequest;

interface EditorService {
    public function getProducts();
    public function getDesigns();
    public function createEditor(CreateEditorRequest $request);
    public function CompleteEditor(string $editorId);
}