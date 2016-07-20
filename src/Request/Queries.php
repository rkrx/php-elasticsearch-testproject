<?php
namespace ElasticSearch\Request;

use ElasticSearch\Request\Criteria;

class Queries {
	/**
	 * @param Criteria\Criteria[] $criterias
	 * @return Criteria\BoolCriteriaContainer
	 */
	public function allMust(Criteria\Criteria... $criterias) {
		return new Criteria\BoolCriteriaContainer('must', $criterias);
	}

	/**
	 * @param Criteria\Criteria[] ...$criterias
	 * @return Criteria\BoolCriteriaContainer
	 */
	public function someMust(Criteria\Criteria... $criterias) {
		return new Criteria\BoolCriteriaContainer('should', $criterias);
	}

	/**
	 * @param string $fieldName
	 * @param string $argumentKeyName
	 * @param array $options
	 * @return Criteria\MatchCriteria
	 */
	public function match($fieldName, $argumentKeyName, array $options = []) {
		return new Criteria\MatchCriteria($fieldName, $argumentKeyName, $options);
	}

	/**
	 * @param string $fieldName
	 * @param string $argumentKeyName
	 * @param array $options
	 * @return Criteria\MatchCriteria
	 */
	public function matchPhrase($fieldName, $argumentKeyName, array $options = []) {
		$options['type'] = 'phrase';
		return new Criteria\MatchCriteria($fieldName, $argumentKeyName, $options);
	}

	/**
	 * @param string $fieldName
	 * @param string $argumentKeyName
	 * @param array $options
	 * @return Criteria\FuzzyCriteria
	 */
	public function fuzzyMatch($fieldName, $argumentKeyName, array $options = []) {
		return new Criteria\FuzzyCriteria($fieldName, $argumentKeyName, $options);
	}
}
