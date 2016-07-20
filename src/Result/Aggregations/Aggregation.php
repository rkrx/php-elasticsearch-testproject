<?php
namespace ElasticSearch\Result\Aggregations;

use ElasticSearch\Result\Cache;
use Traversable;

class Aggregation implements \IteratorAggregate {
	use Cache;

	/** @var array */
	private $aggregate;
	/** @var string */
	private $key;

	/**
	 * @param string $key
	 * @param array $aggregate
	 */
	public function __construct($key, array $aggregate) {
		$this->key = $key;
		$aggregate = array_merge(['buckets' => []], $aggregate);
		$this->aggregate = $aggregate;
	}

	/**
	 * @return string
	 */
	public function getKey() {
		return (string) $this->key;
	}

	/**
	 * @return string
	 */
	public function getValue() {
		if(array_key_exists('value', $this->aggregate)) {
			return $this->aggregate['value'];
		}
		return null;
	}

	/**
	 * @return Aggregation\Option[]
	 */
	public function getOptions() {
		return $this->cache(__FUNCTION__, function () {
			foreach($this->aggregate['buckets'] as $bucket) {
				yield new Aggregation\Option($bucket);
			}
		});
	}

	/**
	 * @return Traversable|Aggregation\Option[]
	 */
	public function getIterator() {
		return new \ArrayIterator($this->getOptions());
	}

	/**
	 * @return string
	 */
	public function __toString() {
		return (string) $this->getValue();
	}
}
