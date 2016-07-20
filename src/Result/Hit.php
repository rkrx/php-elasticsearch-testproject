<?php
namespace ElasticSearch\Result;

use Elastica\Result;

class Hit {
	/** @var array */
	private $result;

	/**
	 * @param Result $result
	 */
	public function __construct(Result $result) {
		$this->result = $result;
	}

	/**
	 * @return string
	 */
	public function getId() {
		return $this->result->getId();
	}

	/**
	 * @return float
	 */
	public function getScore() {
		return $this->result->getScore();
	}

	/**
	 * @return array
	 */
	public function getData() {
		return $this->result->getData();
	}

	/**
	 * @return Result
	 */
	public function getResult() {
		return $this->result;
	}
}
