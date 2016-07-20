<?php
namespace ElasticSearch;

use Elastica\ResultSet;
use ElasticSearch\Request\Aggregations;
use ElasticSearch\Request\Aggregations\Aggregation;
use Traversable;

class Result implements \IteratorAggregate {
	use Result\Cache;

	/** @var ResultSet */
	private $resultSet;
	/** @var Aggregation[]|Aggregations */
	private $aggregations;

	/**
	 * @param ResultSet $resultSet
	 * @param Aggregation[]|Aggregations $aggregates
	 */
	public function __construct(ResultSet $resultSet, Aggregations $aggregates) {
		$this->resultSet = $resultSet;
		$this->aggregations = $aggregates;
	}

	/**
	 * @return Result\Aggregations|Result\Aggregations\Aggregation[]
	 */
	public function getAggregations() {
		return $this->cache(__FUNCTION__, function () {
			$aggregations = iterator_to_array($this->aggregations);
			return new Result\Aggregations($this->resultSet, $aggregations);
		});
	}

	/**
	 * @return Result\Suggestions
	 */
	public function getSuggestions() {
		return $this->cache(__FUNCTION__, function () {
			return new Result\Suggestions($this->resultSet);
		});
	}

	/**
	 * @return Result\Summary
	 */
	public function getSummary() {
		return $this->cache(__FUNCTION__, function () {
			return new Result\Summary($this->resultSet);
		});
	}

	/**
	 * @return Result\Hit[]
	 */
	public function getHits() {
		return $this->cache(__FUNCTION__, function () {
			$results = [];
			foreach($this->resultSet->getResults() as $result) {
				$results[] = new Result\Hit($result);
			}
			return $results;
		});
	}

	/**
	 * @return array
	 */
	public function getRequest() {
		return $this->resultSet->getQuery()->toArray();
	}

	/**
	 * @return array
	 */
	public function getResponse() {
		return $this->resultSet->getResponse()->getData();
	}

	/**
	 * @return Traversable|Result\Hit[]
	 */
	public function getIterator() {
		return $this->getHits();
	}
}
