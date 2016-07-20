<?php
namespace ElasticSearch\Request\Aggregations;

use ElasticSearch\Request\Helpers\AggregationPostProcessorTrait;

class NumericRangeAggregation implements Aggregation {
	use AggregationPostProcessorTrait;

	/**
	 * @return string
	 */
	public function getAggregationKeyName() {
		// TODO: Implement getFacetName() method.
	}

	/**
	 * @param mixed $argument
	 * @return array
	 */
	public function getFilterData($argument) {
		// TODO: Implement getData() method.
		return ['rlf' => 123];
	}

	/**
	 * @param array $filterData
	 * @return array
	 */
	public function getAggregationData(array $filterData) {
		// TODO: Implement getAggregationData() method.
	}
}
