<?php

namespace App\Http\Controllers\Statistics;

use App\Helpers\PageHelpers;
use App\Helpers\RequestHelpers;
use App\Helpers\ResponseHelper;
use App\Helpers\SiteHelpers;
use App\Http\Controllers\Controller;
use App\Models\Site;
use Illuminate\Http\Request;

class StatisticsCrawlerController extends Controller
{
    private $request;

    /**
     * StatisticsCrawlerController constructor.
     * @param $request
     */
    public function __construct(Request $request)
    {
        $this->request = $request;
    }

    public function show()
    {
        if (!is_null($errorMessage = RequestHelpers::checkNeededParams($this->request, ['type']))) {
            return ResponseHelper::makeResponse(['errorMessage' => $errorMessage]);
        }

        switch ($this->request->get('type')) {
            case 'common':
                return $this->commonStat();
            case 'for_site':
                return $this->forSite();
            default:
                return $this->commonStat();
        }
    }

    private function commonStat()
    {
        $sites = Site::with(['pages'])->get()->toArray();
        foreach ($sites as &$site) {
            $site['pages'] = PageHelpers::pagesDatesWithoutTime($site['pages']);
        }
        $result = [];
        foreach ($sites as $site) {
            $notScannedPages = PageHelpers::notScannedPages($site['pages']);
            $scannedPages = PageHelpers::scannedPages($site['pages']);
            $result[] = [
                'site_id'                 => $site['id'],
                'site_name'               => $site['name'],
                'not_scanned_pages_count' => count($notScannedPages),
                'not_scanned_pages'       => $notScannedPages,
                'scanned_page_count'      => count($scannedPages),
                'scanned_pages'           => $scannedPages,
                'all_pages_count'         => count($site['pages']),
                'all_pages'               => $site['pages'],
            ];
        }

        return ResponseHelper::makeResponse(['data' => $result]);
    }

    private function forSite()
    {
        if (!is_null($errorMessage = RequestHelpers::checkNeededParams($this->request, ['site_id']))) {
            return ResponseHelper::makeResponse(['errorMessage' => $errorMessage]);
        }

        try {
            $site = Site::findOrFail($this->request->get('site_id'));
            $site->pages = PageHelpers::pagesDatesWithoutTime($site->pages);

            $notScannedPages = PageHelpers::notScannedPages($site['pages']);

            $scannedPages =PageHelpers::scannedPages($site['pages']);

            $result = [
                'site_id'                 => $site['id'],
                'site_name'               => $site['name'],
                'not_scanned_pages_count' => count($notScannedPages),
                'not_scanned_pages'       => $notScannedPages,
                'scanned_page_count'      => count($scannedPages),
                'scanned_pages'           => $scannedPages,
                'all_pages_count'         => count($site['pages']),
                'all_pages'               => $site['pages'],
            ];

            return ResponseHelper::makeResponse(['data' => $result]);

        } catch (\Exception $e) {
            return ResponseHelper::makeResponse(['errorMessage' => $e->getMessage()]);
        }
    }
}
