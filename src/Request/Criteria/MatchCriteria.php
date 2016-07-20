<?php
namespace ElasticSearch\Request\Criteria;

class MatchCriteria implements Criteria {
	/** @var string */
	private $fieldName;
	/** @var string */
	private $argumentKeyName;
	/** @var array */
	private $options;

	/**
	 * @param string $fieldName
	 * @param string $argumentKeyName
	 * @param array $options
	 */
	public function __construct($fieldName, $argumentKeyName, array $options = []) {
		$this->fieldName = $fieldName;
		$this->argumentKeyName = $argumentKeyName;
		$this->options = $options;
	}

	/**
	 * @param array $arguments
	 * @return array
	 */
	public function getData(array $arguments) {
		if(array_key_exists($this->argumentKeyName, $arguments)) {
			$enrich = function (array $query) {
				return array_merge($this->options, $query);
			};
			return [
				'match' => [
					$this->fieldName => $enrich([
						'query' => $arguments[$this->argumentKeyName]
					])
				]
			];
		}
		return [];
	}
}
