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