<?php
namespace ElasticSearch\Result;

use Elastica\ResultSet;

abstract class ResultSetAware {
	/** @var ResultSet */
	private $resultSet;

	/**
	 * @param ResultSet $resultSet
	 */
	public function __construct(ResultSet $resultSet) {
		$this->resultSet = $resultSet;
	}
	
	/**
	 * @return ResultSet
	 */
	final protected function getResultSet() {
		return $this->resultSet;
	}
}
