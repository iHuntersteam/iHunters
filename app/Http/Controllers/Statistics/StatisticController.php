<?php

namespace App\Http\Controllers\Statistics;

use App\Helpers\PageHelpers;
use App\Helpers\RequestHelpers;
use App\Helpers\ResponseHelper;
use App\Models\Person;
use App\Models\Site;
use Carbon\Carbon;
use Illuminate\Http\Request;

use App\Http\Requests;
use App\Http\Controllers\Controller;

class StatisticController extends Controller
{
    private $request;

    /**
     * StatisticController constructor.
     * @param $request
     */
    public function __construct(Request $request)
    {
        $this->request = $request;
    }

    public function show()
    {
        if ($errors = RequestHelpers::checkNeededParams($this->request, [
            'type',
            'person_id',
        ])
        ) {
            return ResponseHelper::makeResponse([
                'errorMessage' => $errors,
            ]);
        }

        switch ($this->request->get('type')) {
            case 'common_for_site':
                return $this->commonForSite();
            case 'daily':
                return $this->dailyStat();
            case 'common':
                return $this->commonForAllSites();
            default:
                return $this->commonForAllSites();
        }
    }

    private function commonForSite()
    {
        if ($errors = RequestHelpers::checkNeededParams($this->request, [
                'site_id',
            ]
        )
        ) {
            return ResponseHelper::makeResponse([
                'errorMessage' => $errors,
            ]);
        }

        try {
            /** @var Person $person */
            $person = Person::findOrFail($this->request->get('person_id'))
                ->with(['pages'])->first();
            $pages = $person->pages()->where('site_id', $this->request->get('site_id'))
                ->get();

            $commonRank = 0;
            foreach ($pages as $page) {
                $commonRank += $person->rank($page);
            }

            return ResponseHelper::makeResponse([
                'data' => [
                    'person_name' => $person->name,
                    'rank'        => $commonRank,
                ],
            ]);
        } catch (\Exception $e) {
            return ResponseHelper::makeResponse([
                'errorMessage' => $e->getMessage(),
            ]);
        }
    }

    private function commonForAllSites()
    {
        try {
            /** @var Person $person */
            $person = Person::with(['pages'])->findOrFail($this->request->get('person_id'));
            $sites = Site::with(['pages'])->get();

            $result = [];
            foreach ($sites as $site) {
                $sitePages = $site->pages;
                $sitePagesIds = [];
                foreach ($sitePages as $sitePage) {
                    $sitePagesIds[] = $sitePage->id;
                }


                $personPages = $person->pages()->whereIn('page_id', $sitePagesIds)->get()->toArray();
                $result[] = [
                    'site_id'     => $site->id,
                    'site_name'   => $site->name,
                    'total_pages' => count($personPages),
                    'pages'       => $personPages,
                ];
            }


            return ResponseHelper::makeResponse([
                'data' => [
                    'person_name' => $person->name,
                    'sites'       => $result,
                ],
            ]);
        } catch (\Exception $e) {
            return ResponseHelper::makeResponse([
                'errorMessage' => $e->getMessage(),
            ]);
        }
    }

    private function dailyStat()
    {
        if ($errors = RequestHelpers::checkNeededParams($this->request, [
            'start_date',
            'finish_date',
            'site_id',
        ])
        ) {
            return ResponseHelper::makeResponse([
                'errorMessage' => $errors,
            ]);
        }

        try {
            /** @var Person $person */
            $person = Person::findOrFail($this->request->get('person_id'));
            $startDate = Carbon::createFromFormat('d.m.y H', $this->request->get('start_date') . " 0");
            $finishDate = Carbon::createFromFormat('d.m.y H', $this->request->get('finish_date') . " 0");
            if ($finishDate < $startDate) {
                throw new \Exception("finish_date должен быть новее start_date");
            }
            $pages = $person->pages()
                ->where('site_id', $this->request->get('site_id'))
                ->where('last_scan_date', '>=', $startDate)
                ->where('last_scan_date', '<=', $finishDate)
                ->get();
            $structuredPages = PageHelpers::structurePagesByDays($startDate, $finishDate, $pages);

            return ResponseHelper::makeResponse(['data' => $structuredPages]);
        } catch (\Exception $e) {
            return ResponseHelper::makeResponse(['errorMessage' => $e->getMessage()]);
        }
    }
}
