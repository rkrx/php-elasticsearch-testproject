<?php
namespace ElasticSearch\Result;

trait Cache {
	/** @var array */
	private $cache = [];

	/**
	 * @param string $key
	 * @param callback $callback
	 * @return mixed
	 */
	final protected function cache($key, $callback) {
		if(!array_key_exists($key, $this->cache)) {
			$this->cache[$key] = call_user_func($callback);
			if($this->cache[$key] instanceof \Generator) {
				$this->cache[$key] = iterator_to_array($this->cache[$key]);
				$this->cache[$key] = new \ArrayIterator($this->cache[$key]);
			}
		}
		return $this->cache[$key];
	}
}
