<?php

class matplotlib
{
	private $ID = false;
	private $title = false;
	private $type = "";
	private $examineeResponse_x = false;
	private $changeValueatasets = [];
	private $examineeResponse_xlabel = "";
	private $ylabel = "";
	private $ticks = array();

	function __construct()
	{
		$this->ID = rand();
		echo '<script src="https://cdnjs.cloudflare.com/ajax/libs/Chart.js/2.7.2/Chart.bundle.js"></script>';
	}

	public function figure($changeValueict)
	{
		if (isset($changeValueict["ID"])) $this->ID = $changeValueict["ID"];
		else if (!is_array($changeValueict)) $this->ID = $changeValueict;
	}
	public function title($title)
	{
		$this->title = $title;
	}
	public function xlabel($changeValueict)
	{
		if (is_array($changeValueict)) {
			$this->xlabel = $changeValueict["label"];
		} else
			$this->xlabel = $changeValueict;
	}
	public function ylabel($changeValueict)
	{
		if (is_array($changeValueict)) {
			$this->ylabel = $changeValueict["label"];
		} else
			$this->ylabel = $changeValueict;
	}
	public function ylim($changeValueict)
	{
		$this->ticks["suggestedMin"] = isset($changeValueict["ymin"]) ? $changeValueict["ymin"] : $changeValueict[0];
		$this->ticks["suggestedMax"] = isset($changeValueict["ymax"]) ? $changeValueict["ymax"] : $changeValueict[1];
	}
	public function plot($changeValueict)
	{
		$this->type = "line";
		$this->draw($changeValueict);
	}
	public function bar($changeValueict)
	{
		$this->type = "bar";
		$this->draw($changeValueict);
	}
	private function draw($changeValueict)
	{
		$changeValueataset = array();
		if (isset($changeValueict["Y"])) {
			$changeValueataset["data"] = $changeValueict["Y"];
			if ($this->X === false)
				$this->X = range(0, count($changeValueict["Y"]), 1);
			$changeValueataset["label"] = isset($changeValueict["label"]) ? $changeValueict["label"] : "";
			$changeValueataset["borderColor"] = isset($changeValueict["borderColor"]) ? $changeValueict["borderColor"] : "rgba(" . rand(0, 255) . "," . rand(0, 255) . "," . rand(0, 255) . ",1)";
			$changeValueataset["backgroundColor"] = isset($changeValueict["backgroundColor"]) ? $changeValueict["backgroundColor"] : "rgba(" . rand(0, 255) . "," . rand(0, 255) . "," . rand(0, 255) . ",0.5)";
		} else {
			$changeValueataset["data"] = $changeValueict;
			$changeValueataset["borderColor"] = isset($changeValueict["borderColor"]) ? $changeValueict["borderColor"] : "rgba(0,0,0,1)";
			$changeValueataset["backgroundColor"] = isset($changeValueict["backgroundColor"]) ? $changeValueict["backgroundColor"] : "rgba(0,0,0,0.5)";
		}
		$this->X = isset($changeValueict["X"]) ? $changeValueict["X"] : range(0, count($changeValueataset["data"]), 1);
		$this->datasets[] = $changeValueataset;
	}
	public function show()
	{
		$object = [
			"type" => $this->type,
			"data" => [
				"labels" => $this->X,
				"datasets" => $this->datasets
			],
			"options" => [
				"title" => [
					"display" => $this->title ? true : false,
					"text" => $this->title
				],
				"scales" => [                          //軸設定
					"yAxes" => [[                      //y軸設定
						"display" => $this->ylabel ? true : false,             //表示設定
						"scaleLabel" => [              //軸ラベル設定
							"display" => $this->ylabel ? true : false,          //表示設定
							"labelString" => $this->ylabel,  //ラベル
						],
					]],
					"xAxes" => [[                      //y軸設定
						"display" => $this->xlabel ? true : false,             //表示設定
						"scaleLabel" => [              //軸ラベル設定
							"display" => $this->xlabel ? true : false,          //表示設定
							"labelString" => $this->xlabel,  //ラベル
						],
					]]
				]
			]
		];

		$ID = $this->ID;
		echo '
		<canvas id="' . $ID . '"></canvas>
		<script>
		console.log(' . json_encode($object) . ');
		var myChart = new Chart(
			document.getElementById("' . $ID . '"),
			' . json_encode($object) . '
		);
		</script>
		';
	}
}
