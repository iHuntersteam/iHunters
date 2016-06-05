<?php

namespace App\Controller;

use App\Model as Model;

class Stats extends Controller
{
	private $stats, $sites, $persons, $daily, $lastScanDate;

	function __construct() {
		$this->stats = new Model\Sites();
		$this->sites = new Model\Sites();
		$this->persons = new Model\Persons();
		$this->daily = new Model\Sites();
		$this->lastScanDate = new Model\Sites();
	}

	public function showAll()
	{
		$this->stats->getAllStats($_POST['siteId']);
		$this->lastScanDate->getLastScanDate($_POST['siteId']);
		$this->sites->getAll();
		echo $this->view("commonStatistics.html", [
			'title' => "Общая статистика",
			'stats' => $this->stats->collection,
			'sites' => $this->sites->collection,
			'lastScanDates' => $this->lastScanDate->collection
			]);
	}
	
	public function index()
	{
		$this->sites->getAll();
		echo $this->view("commonStatistics.html", [
			'title' => "Общая статистика",
			'sites' => $this->sites->collection
			]);
	}

	public function showCommonStatistics()
	{
		$this->sites->getAll();
		echo $this->view("commonStatistics.html", [
			'title' => "Общая статистика",
			'sites' => $this->sites->collection
			]);
	}

	public function showDailyStatistics()
	{
		$this->sites->getAll();
		$this->persons->getAll();
		echo $this->view("dailyStatistics.html", [
			'title' => "Ежедневная статистика",
			'sites' => $this->sites->collection,
			'persons' => $this->persons->collection
			]);
	}

	public function showStatisticsPerDay()
	{
		
		$this->daily->getDailyStats();

		if($this->daily->collection){
			foreach($this->daily->collection as $value){
				$totalRank += $value->rank;
			}
		}

		$this->sites->getAll();
		$this->persons->getAll();
		echo $this->view("dailyStatistics.html", [
			'title' => "Ежедневная статистика",
			'sites' => $this->sites->collection,
			'persons' => $this->persons->collection,
			'daily' => $this->daily->collection,
			'totalRank' => $totalRank
			]);

	}
}