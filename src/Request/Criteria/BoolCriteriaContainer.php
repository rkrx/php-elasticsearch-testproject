<?php
namespace ElasticSearch\Request\Criteria;

class BoolCriteriaContainer implements Criteria {
	/** @var string */
	private $boolType;
	/** @var array|Criteria[] */
	private $children;

	/**
	 * @param $boolType
	 * @param Criteria[] $children
	 */
	public function __construct($boolType, array $children) {
		$this->boolType = $boolType;
		$this->children = $children;
	}

	/**
	 * @param array $arguments
	 * @return array
	 */
	public function getData(array $arguments) {
		$result = [];
		foreach($this->children as $child) {
			$result[] = $child->getData($arguments);
		}
		return ['bool' => [$this->boolType => $result]];
	}
}
