<?php
namespace ElasticSearch\Request\Aggregations;

use ElasticSearch\Request\Helpers\AggregationPostProcessorTrait;

class DynamicTermListAggregation implements Aggregation {
	use AggregationPostProcessorTrait;

	/** @var bool */
	private $includeEmpty = true;
	/** @var string */
	private $fieldName = null;
	/** @var null|string */
	private $caption;
	/** @var string */
	private $facetName;
	/** @var int|null */
	private $size = null;

	/**
	 * @param string $facetName
	 * @param string $fieldName
	 * @param string|null $caption
	 */
	public function __construct($facetName, $fieldName, $caption = null) {
		$this->facetName = $facetName;
		$this->fieldName = $fieldName;
		if($caption === null) {
			$caption = $this->fieldName;
		}
		$this->caption = $caption;
	}

	/**
	 * @return boolean
	 */
	public function isIncludeEmpty() {
		return $this->includeEmpty;
	}

	/**
	 * @param boolean $includeEmpty
	 * @return $this
	 */
	public function setIncludeEmpty($includeEmpty = true) {
		$this->includeEmpty = $includeEmpty;
		return $this;
	}

	/**
	 * @return string
	 */
	public function getAggregationKeyName() {
		return $this->facetName;
	}

	/**
	 * @return string
	 */
	public function getFieldName() {
		return $this->fieldName;
	}

	/**
	 * @return null|string
	 */
	public function getCaption() {
		return $this->caption;
	}

	/**
	 * @param null|string $caption
	 * @return $this
	 */
	public function setCaption($caption) {
		$this->caption = $caption;
		return $this;
	}

	/**
	 * @return int|null
	 */
	public function getSize() {
		return $this->size;
	}

	/**
	 * @param int|null $size
	 * @return $this
	 */
	public function setSize($size) {
		$this->size = $size;
		return $this;
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
							$this->getFieldName() => $value
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
		$filterData = $this->filterKey($filterData, $this->getAggregationKeyName());
		return [
			'filter' => [
				'bool' => [
					'must' => $filterData
				],
			],
			'aggs' => [
				$this->getAggregationKeyName() => call_user_func(function () {
					$result = [];
					$result['terms'] = ['field' => $this->getFieldName(), 'min_doc_count' => 0];
					return $result;
				})
			]
		];
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
