<?php
namespace ElasticSearch\Helpers;

class Helpers {
	/**
	 * @param array $data
	 * @param array $generatedData
	 * @return array
	 */
	public static function ifNotEmpty(array $data, array $generatedData) {
		if(is_array($data) && count($data)) {
			return $generatedData;
		}
		return [];
	}
}
