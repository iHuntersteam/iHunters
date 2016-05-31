<?php

namespace App\Helpers;

use App\Models\Page;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Support\Debug\Dumper;

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

    /**
     * @param Carbon $startDate
     * @param Carbon $finishDate
     * @param $pages
     * @return array
     */
    public static function structurePagesByDays(Carbon $startDate, Carbon $finishDate, $pages)
    {
        $result = [];
        foreach ($pages as $page) {
            $page->last_scan_date = $page->last_scan_date->toDateString();
        }
        $pages = $pages->toArray();
        for ($iterationDate = $startDate; $iterationDate <= $finishDate; $iterationDate->addDay()) {
            $result[ $iterationDate->toDateString() ] = array_values(array_filter($pages, function ($page) use ($iterationDate) {
                return $page['last_scan_date'] == $iterationDate->toDateTimeString();
            }));
        }

        return $result;
    }

    /**
     * @param Collection|array $pages
     * @return array
     */
    public static function notScannedPages($pages)
    {
        if (!is_array($pages)) {
            $pages = $pages->toArray();
        }

        return array_values(array_filter($pages, function ($page) {
            $foundDate = Carbon::parse($page['found_date_time']);
            $scanDate = Carbon::parse($page['last_scan_date']);

            return $scanDate < $foundDate;
        }));
    }

    public static function scannedPages($pages)
    {
        if (!is_array($pages)) {
            $pages = $pages->toArray();
        }

        return array_values(array_filter($pages, function ($page) {
            $foundDate = Carbon::parse($page['found_date_time']);
            $scanDate = Carbon::parse($page['last_scan_date']);

            return $scanDate >= $foundDate;
        }));
    }

    /**
     * @param Collection|array $page
     * @return array|Collection
     */
    public static function pageDatesWithoutTime($page)
    {
        if (!is_array($page)) {
            $page = $page->toArray();
        }

        $page['found_date_time'] = explode(" ", $page['found_date_time'])[0];
        $page['last_scan_date'] = explode(" ", $page['last_scan_date'])[0];

        return $page;
    }

    public static function pagesDatesWithoutTime($pages)
    {
        if (!is_array($pages)) {
            $pages = $pages->toArray();
        }
        foreach ($pages as &$page) {
            $page = self::pageDatesWithoutTime($page);
        }

        return $pages;
    }
}