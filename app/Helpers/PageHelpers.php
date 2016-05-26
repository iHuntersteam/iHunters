<?php

namespace App\Helpers;

use App\Models\Page;

class PageHelpers
{
    public static function randomPageId()
    {
        $ids = Page::lists('id')->toArray();

        return $ids[ random_int(0, count($ids) - 1) ];
    }

    public static function randomPage()
    {
        return Page::find(self::randomPageId());
    }
}