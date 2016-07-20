<?php
namespace ElasticSearch\Request\Criteria;

class FuzzyCriteria implements Criteria {
	/** @var string */
	private $fieldName;
	/** @var string */
	private $argumentKeyName;
	/** @var array */
	private $options;

	/**
	 * For possible option-parameters look here:
	 * https://www.elastic.co/guide/en/elasticsearch/reference/current/query-dsl-fuzzy-query.html
	 *
	 * @param string $fieldName
	 * @param string $argumentKeyName
	 * @param array $options
	 */
	public function __construct($fieldName, $argumentKeyName, array $options = ['fuzziness' => 'AUTO']) {
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
				'fuzzy' => [
					$this->fieldName => $enrich([
						'value' => $arguments[$this->argumentKeyName]
					])
				]
			];
		}
		return [];
	}
}
