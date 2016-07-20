<?php
namespace ElasticSearch;

use Elastica\Index as ESIndex;

class Index {
	/** @var ESIndex */
	private $index;

	/**
	 * @param ESIndex $index
	 */
	public function __construct(ESIndex $index) {
		$this->index = $index;
	}

	/**
	 * @param string $typeName
	 * @return Type
	 */
	public function getType($typeName) {
		$type = $this->index->getType($typeName);
		return new Type($type);
	}
}
