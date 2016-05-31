<?php

namespace App\Helpers;

use App\Models\Site;
use Illuminate\Database\Eloquent\Collection;

class SiteHelpers
{
    /**
     * Рандомный id сайта
     * @return mixed
     */
    public static function randomSiteId()
    {
        $ids = Site::lists('id')->toArray();

        return $ids[ random_int(0, count($ids) - 1) ];
    }

    /**
     * Рандомный сайт
     * @return mixed
     */
    public static function randomSite()
    {
        $id = self::randomSiteId();

        return Site::find($id);
    }


}