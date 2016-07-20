<?php
namespace ElasticSearch\Result\Suggestions;

class PhraseSuggestion {
	/** @var array */
	private $data;

	/**
	 * @param array $data
	 */
	public function __construct(array $data) {
		$this->data = $data;
	}

	/**
	 * @return bool
	 */
	public function hasSuggestion() {
		return !empty($this->data['options']);
	}
}
