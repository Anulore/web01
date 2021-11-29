<?php

function checkTriangleHit($x, $y, $r) {
	if ($y <= -$x + $r / 2)
		return "YES";
	return "NO";
}

function checkCircleHit ($x, $y, $r) {
	if (sqrt($x*$x + $y*$y) <= $r)
		return "YES";
	return "NO";
}

function checkRectangleHit($x, $y, $r) {
	if ($x <= $r && abs($y) <= $r)
		return "YES";
	return "NO";
}

function validateX() {
	if (!isset($_GET["x"]))
		return false;
	if(!is_numeric($_GET["x"]))
		return false;
	if ($_GET["x"] >= 5 || $_GET["x"] <= -5)
		return false;
	return true;
}

function validateY () {
	if(!isset($_GET["y"]))
		return false;
	if (!is_numeric($_GET["y"]))
		return false;
	if (!in_array($_GET["y"], array(-3, -2, -1, 0, 1, 2 , 3, 4, 5)))
		return false;
	return true;
}

function validateR () {
	if(!isset($_GET["r"]))
		return false;
	if (!is_numeric($_GET["r"]))
		return false;
	if (!in_array($_GET["r"], array(1, 1.5, 2, 2.5, 3)))
		return false;
	return true;
}

function validateAllNumbers() {
	if (validateX() && validateY() && validateR())
		return true;
	return false;
}

function checkHit($x, $y, $r) {
	$result = "NO";
	if ($x >= 0 && $y >= 0) {
		$result = checkTriangleHit($x, $y, $r);
	}
	if($x <= 0 && $y >= 0 ) {
		$result = checkCircleHit($x, $y, $r);
	}
	if ($x >= 0 && $y <= 0) {
		$result = checkRectangleHit($x, $y, $r);
	}
	return $result; 
}

function makeJson($array) {
	$json = "{";
	foreach($array as $key => $value) {
		$json .= "\"" . $key . "\"" . ": " . "\"" . $value . "\"" . ",";
	}
	$json = substr($json, 0, -1);
	$json .= "}";
	return $json;
}

function getJsonResultsHistory() {
	$jsonData = "[";
	foreach($_SESSION["history"] as $object) {
		$jsonData .= makeJson($object);
		$jsonData .= ",";
	}
	$jsonData = substr($jsonData, 0, -1);
	$jsonData .= "]";
	return $jsonData;
}



session_start();
date_default_timezone_set('Europe/Moscow');

if ($_GET["reload"] == true) {
	if (!empty($_SESSION['history']))
		echo(getJsonResultsHistory());
	else 
		echo("[]");
	exit();
}

if ($_GET["delete"] == true && isset($_SESSION['history'])) {
	$_SESSION['history'] = array();
	exit();
}

$currentTime = date("H:i:s");
$startTime = microtime(true);

if (!validateAllNumbers()) {
	header("Status: 400 Bad Request", true, 400);
	exit();
}

$x = $_GET['x'];
$y = $_GET['y'];
$r = $_GET['r'];

$answer = checkHit($x, $y, $r);


$totalTime = microtime(true) - $startTime;

$result = array(
	"x" => $x,
	'y' => $y,
	"r" => $r,
	"answer" => $answer,
	"currentTime" => $currentTime,
	"totalTime" => number_format($totalTime, 10, ".", "") . " sec"
);

if (!isset($_SESSION["history"])) {
	$_SESSION["history"] = array();
}
array_push($_SESSION["history"], $result);



echo(getJsonResultsHistory());


?>