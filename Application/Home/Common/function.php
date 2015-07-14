<?php

function parseType($type) {
	$str = "";
	if ($type == "radio") {
		$str = "单选题";
	} else if ($type == "checkbox") {
		$str = "多选题";
	} else if ($type == "text") {
		$str = "填空题";
	} else if ($type == "score") {
		$str = "评分题";
	}
	return $str;
}

function parseIsnecessary($isnecessary) {
	$str = "";
	if ($isnecessary == 1) {
		$str = "*";
	} else if ($isnecessary == 0) {
		$str = "";
	}
	return $str;
}

function parsePercentage($percentage) {
	$str = "primary";
	if ($percentage <= 25) {
		$str = "info";
	} else if ($percentage <= 50) {
		$str = "success";
	} else if ($percentage <= 75) {
		$str = "warning";
	} else {
		$str = "danger";
	}
	return $str;
}

function filterZero($num) {
	return $num == 0 ? 100 : $num;
}

function textOutline($text) {
	if (mb_strlen($text) > 13) {
		return mb_substr($text, 0, 5, 'UTF-8') . "...";
	}
	return mb_substr($text, 0, 5, 'UTF-8');
}
?>