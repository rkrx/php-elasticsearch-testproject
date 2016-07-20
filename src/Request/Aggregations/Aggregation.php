<?php
namespace ElasticSearch\Request\Aggregations;

interface Aggregation {
	/**
	 * @return string
	 */
	public function getAggregationKeyName();

	/**
	 * @param mixed $argument
	 * @return array
	 */
	public function getFilterData($argument);

	/**
	 * @param array $filterData
	 * @return array
	 */
	public function getAggregationData(array $filterData);

	/**
	 * @return callable
	 */
	public function getPostProcessor();
}
