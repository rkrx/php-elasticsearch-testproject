<?php
namespace ElasticSearch;

use Elastica\Type AS ESType;

class Type {
	/** @var ESType */
	private $type;

	/**
	 * @param ESType $type
	 */
	public function __construct(ESType $type) {
		$this->type = $type;
	}

	/**
	 * @param string $requestBuilderClassName
	 * @param array $arguments
	 * @return Request
	 */
	public function createRequest($requestBuilderClassName = Request::class, array $arguments = []) {
		$rc = new \ReflectionClass($requestBuilderClassName);
		$instance = $rc->newInstanceArgs(array_merge([$this->type], array_values($arguments)));
		/** @var Request $instance */
		return $instance;
	}
}
