<?php
namespace ElasticSearch;

function linkTo(array $args) {
	return http_build_query($args);
}

function linkToSelf(array $args) {
	$args = array_merge($_GET, $args);
	return http_build_query($args);
}

function getParamArray($key) {
	$values = [];
	if(array_key_exists($key, $_GET)) {
		$values = $_GET[$key];
	}
	if(!is_array($values)) {
		$values = [];
	}
	return $values;
}

function hasValueInParam($key, $value) {
	$values = getParamArray($key);
	return in_array($value, $values);
}

function addToParam($key, array $addValues) {
	$values = getParamArray($key);
	foreach($addValues as $value) {
		$values[] = $value;
	}
	return $values;
}

function removeToParam($key, array $removeValues) {
	$values = getParamArray($key);
	$values = array_diff($values, $removeValues);
	return $values;
}

