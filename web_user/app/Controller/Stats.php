<?php

namespace App\Controller;

use App\Model as Model;

class Stats extends Controller
{
	private $stats, $sites;

	function __construct() {
		$this->stats = new Model\Sites();
		$this->sites = new Model\Sites();
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
}