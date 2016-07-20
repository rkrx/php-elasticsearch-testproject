<?php
namespace ElasticSearch\Result;

use ElasticSearch\Request;
use ElasticSearch\Result\Aggregations\Aggregation;
use Elastica\ResultSet;
use Traversable;

class Aggregations extends ResultSetAware implements \IteratorAggregate {
	use Cache;

	/** @var Request\Aggregations\Aggregation[] */
	private $requestAggregates;

	/**
	 * @param ResultSet $resultSet
	 * @param Request\Aggregations\Aggregation[] $aggregates
	 */
	public function __construct(ResultSet $resultSet, array $aggregates) {
		parent::__construct($resultSet);
		$this->requestAggregates = $aggregates;
	}

	/**
	 * @param string $name
	 * @return Aggregation|null
	 */
	public function getByName($name) {
		foreach($this->getAggregations() as $aggregation) {
			if($aggregation->getKey() === $name) {
				return $aggregation;
			}
		}
		return null;
	}

	/**
	 * @return Aggregations\Aggregation[]|Aggregations\Aggregation\Option[][]
	 */
	public function getAggregations() {
		return $this->cache(__FUNCTION__, function () {
			$aggregations = $this->getResultSet()->getAggregations();
			foreach($aggregations as $key => $subAggregation) {
				$agg = $subAggregation[$key];
				$postProcessor = function (array $data) { return $data; };
				foreach($this->requestAggregates as $requestAggregate) {
					if($requestAggregate->getAggregationKeyName() === $key) {
						$postProcessor = $requestAggregate->getPostProcessor();
						break;
					}
				}
				$agg = $postProcessor($agg);
				yield new Aggregation($key, $agg);
			}
		});
	}

	/**
	 * @return Traversable|Aggregations\Aggregation[]
	 */
	public function getIterator() {
		return $this->getAggregations();
	}
}
