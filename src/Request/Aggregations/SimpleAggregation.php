<?php

namespace ElasticSearch\Request\Aggregations;

class SimpleAggregation implements Aggregation {
	/** @var string */
	private $aggregationType;
	/** @var string */
	private $aggregationKeyName;
	/** @var string */
	private $fieldName;

	/**
	 * @param string $aggregationType
	 * @param string $aggregationKeyName
	 * @param string $fieldName
	 */
	public function __construct($aggregationType, $aggregationKeyName, $fieldName) {
		$this->aggregationKeyName = $aggregationKeyName;
		$this->fieldName = $fieldName;
		$this->aggregationType = $aggregationType;
	}

	/**
	 * @return string
	 */
	public function getAggregationKeyName() {
		return $this->aggregationKeyName;
	}

	/**
	 * @param mixed $argument
	 * @return array
	 */
	public function getFilterData($argument) {
		if(is_array($argument)) {
			$genList = function () use ($argument) {
				$result = [];
				foreach($argument as $value) {
					$result[] = [
						'match' => [
							$this->fieldName => $value
						]
					];
				}
				return $result;
			};
			return [
				'bool' => [
					'should' => $genList()
				]
			];
		}
		return null;
	}

	/**
	 * @param array $filterData
	 * @return array
	 */
	public function getAggregationData(array $filterData) {
		return [
			'filter' => [
				'bool' => [
					'must' => $this->filterKey($filterData, $this->getAggregationKeyName())
				],
			],
			'aggs' => [
				$this->getAggregationKeyName() => [
					$this->aggregationType => [
						'field' => $this->fieldName
					]
				]
			]
		];
	}

	/**
	 * @return callable
	 */
	public function getPostProcessor() {
		return function ($data) { return $data; };
	}

	/**
	 * @param array $filterData
	 * @param string $filterKey
	 * @return array
	 */
	private function filterKey($filterData, $filterKey) {
		$result = [];
		foreach($filterData as $key => $filter) {
			if($key !== $filterKey) {
				$result[] = $filter;
			}
		}
		return $result;
	}
}
