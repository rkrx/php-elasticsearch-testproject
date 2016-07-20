<?php
namespace ElasticSearch\Request\Criteria;

interface Criteria {
	/**
	 * @param array $arguments
	 * @return array
	 */
	public function getData(array $arguments);
}
