<?php
namespace ElasticSearch\Result;

class Suggestions extends ResultSetAware {
	use Cache;

	/**
	 * @return Suggestions\PhraseSuggestion
	 */
	public function getPhraseSuggestions() {
		return $this->cache(__FUNCTION__, function () {
			$data = $this->getResultSet()->getSuggests();
			if(!array_key_exists('phrase', $data)) {
				$data['phrase'] = [];
			}
			return new Suggestions\PhraseSuggestion($data['phrase']);
		});
	}
}
