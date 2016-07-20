<?php
namespace ElasticSearch\Request\Helpers;

use Exception;

trait AggregationPostProcessorTrait {
	/** @var callable|null */
	private $postProcessor= null;

	/**
	 * @param callable $callable
	 * @return $this
	 * @throws Exception
	 */
	public function setPostProcessor($callable) {
		if(!is_callable($callable) && !is_null($callable)) {
			throw new Exception('Invalid callback');
		}
		$this->postProcessor = $callable;
		return $this;
	}

	/**
	 * @return callable
	 */
	public function getPostProcessor() {
		if($this->postProcessor === null) {
			$this->postProcessor = function (array $data) { return $data; };
		}
		return $this->postProcessor;
	}
}
