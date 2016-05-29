<?php

namespace App\Controller;

use App\Model as Model;

class Stats extends Controller
{
	private $stats, $sites, $persons;

	function __construct() {
		$this->stats = new Model\Sites();
		$this->sites = new Model\Sites();
		$this->persons = new Model\Persons();
	}

	public function showAll()
	{
		$this->stats->getAllStats($_POST['siteId']);
		$this->sites->getAll();
		echo $this->view("commonStatistics.html", [
			'title' => "Общая статистика",
			'stats' => $this->stats->collection,
			'sites' => $this->sites->collection
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
}