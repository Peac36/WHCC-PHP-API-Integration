<?php

namespace Peac36\Whcc;

class Utils
{
    public static function parseJson($content)
    {
        return json_decode($content, true);
    }
}