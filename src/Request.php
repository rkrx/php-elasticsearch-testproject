<?php
namespace ElasticSearch;

use Elastica;
use ElasticSearch\Request\Criteria;
use ElasticSearch\Request\Aggregations\Aggregation;
use ElasticSearch\Request\Aggregations;
use ElasticSearch\Request\Queries;

abstract class Request {
	/** @var Aggregation[]|Aggregations */
	private $aggregations = null;
	/** @var Queries */
	private $queries = null;
	/** @var Elastica\Type */
	private $type;
	/** @var Criteria\Criteria */
	private $query = null;

	/**
	 * @param Elastica\Type $type
	 */
	public function __construct(Elastica\Type $type) {
		$this->type = $type;
	}

	/**
	 * @param array $arguments
	 * @param array $options
	 * @return Result
	 */
	public function search(array $arguments, array $options = []) {
		$query = [
			'query' => $this->buildQuery($arguments),
			'aggs' => $this->buildAggregationArray($arguments),
			'post_filter' => $this->buildPostFilterArray($arguments),
		];
		if(array_key_exists('limit', $options)) {
			$query['size'] = $options['limit'];
		}
		if(array_key_exists('offset', $options)) {
			$query['from'] = $options['offset'];
		}
		/*if(array_key_exists('query', $arguments)) {
			$query['suggest'] = ['simple_phrase' => ['text' => $arguments['query']]];
		}*/
		
		$response = $this->type->search($query);
		return new Result($response, $this->aggregations);
	}

	/**
	 * @param Criteria\Criteria $query
	 * @return Queries
	 */
	public function query(Criteria\Criteria $query = null) {
		if($query !== null) {
			$this->query = $query;
		}
		if($this->queries === null) {
			$this->queries = new Queries();
		}
		return $this->queries;
	}

	/**
	 * @return Aggregations|Aggregation[]
	 */
	public function aggregations() {
		if($this->aggregations === null) {
			$this->aggregations = new Aggregations();
		}
		return $this->aggregations;
	}

	/**
	 * @param array $arguments
	 * @return array
	 */
	private function buildQuery($arguments) {
		if($this->query !== null) {
			return $this->query->getData($arguments);
		}
		return [];
	}

	/**
	 * @param array $arguments
	 * @return array
	 */
	public function buildAggregationArray(array $arguments) {
		$filterArray = $this->getPostFilterArray($arguments);
		$aggregationArray = $this->getAggregationArray($filterArray);
		return $aggregationArray;
	}

	/**
	 * Builds the primary filter.
	 * The primary filter is the sum of all filters minus the post-filters
	 *
	 * @param array $arguments
	 * @return array
	 */
	private function buildPostFilterArray(array $arguments) {
		$filterArray = $this->getPostFilterArray($arguments);
		$postFilterKeys = array_keys($filterArray);
		$result = [];
		foreach(array_keys($arguments) as $argumentKey) {
			if(in_array($argumentKey, $postFilterKeys)) {
				$result[] = $filterArray[$argumentKey];
			}
		}
		return ['bool' => ['must' => $result]];
	}

	/**
	 * @param array $arguments
	 * @return array
	 */
	private function getPostFilterArray(array $arguments) {
		$filterArray = [];
		$aggregations = $this->aggregations();
		foreach($aggregations as $aggregation) {
			$aggregationKeyName = $aggregation->getAggregationKeyName();
			$argument = null;
			if(array_key_exists($aggregationKeyName, $arguments)) {
				$argument = $arguments[$aggregationKeyName];
			}
			$filterData = $aggregation->getFilterData($argument);
			if($filterData !== null) {
				assert('is_array($filterData)');
				$filterArray[$aggregationKeyName] = $filterData;
			}
		}
		return $filterArray;
	}

	/**
	 * @param array $filterArray
	 * @return array
	 */
	private function getAggregationArray(array $filterArray) {
		$aggregationArray = [];
		$aggregations = $this->aggregations()->getIterator();
		foreach($aggregations as $aggregation) {
			$facetKeyName = $aggregation->getAggregationKeyName();
			$aggregationArray[$facetKeyName] = $aggregation->getAggregationData($filterArray);
		}
		return $aggregationArray;
	}
}
