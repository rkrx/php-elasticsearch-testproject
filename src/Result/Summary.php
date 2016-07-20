<?php
namespace ElasticSearch\Result;

class Summary extends ResultSetAware {
	/**
	 * @return float
	 */
	public function getTotalTime() {
		return $this->getResultSet()->getTotalTime() / 1000;
	}

	/**
	 * @return int
	 */
	public function getTotalHits() {
		return $this->getResultSet()->getTotalHits();
	}
}
