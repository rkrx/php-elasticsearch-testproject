<?php
namespace ElasticSearch\Request;

use ArrayIterator;
use IteratorAggregate;
use Traversable;
use ElasticSearch\Request;

class Aggregations implements IteratorAggregate {
	/** @var Request\Aggregations\Aggregation */
	private $aggregations = [];

	/**
	 * @param string $aggregationKeyName
	 * @param string $fieldName
	 * @return Aggregations\SimpleAggregation
	 */
	public function createMinAggregation($aggregationKeyName, $fieldName) {
		return new Aggregations\SimpleAggregation('min', $aggregationKeyName, $fieldName);
	}

	/**
	 * @param string $aggregationKeyName
	 * @param string $fieldName
	 * @return Aggregations\SimpleAggregation
	 */
	public function createMaxAggregation($aggregationKeyName, $fieldName) {
		return new Aggregations\SimpleAggregation('max', $aggregationKeyName, $fieldName);
	}

	/**
	 * @param string $facetKeyName
	 * @param string $fieldName
	 * @param string|null $caption
	 * @return Request\Aggregations\DynamicTermListAggregation
	 */
	public function createDynamicTermListAggregation($facetKeyName, $fieldName, $caption) {
		return new Aggregations\DynamicTermListAggregation($facetKeyName, $fieldName, $caption);
	}

	/**
	 * @return Request\Aggregations\NumericRangeAggregation
	 */
	public function createNumericRangeAggregation() {
		return new Aggregations\NumericRangeAggregation();
	}

	/**
	 * @param Request\Aggregations\Aggregation $aggregation
	 * @return $this
	 */
	public function addAggregation(Aggregations\Aggregation $aggregation) {
		$this->aggregations[] = $aggregation;
		return $this;
	}

	/**
	 * @return Traversable|Request\Aggregations\Aggregation[]
	 */
	public function getIterator() {
		return new ArrayIterator($this->aggregations);
	}
}
