<?php
namespace ElasticSearch\Request\PostProcessing;

class KeyAsDataEnricher extends KeyValueEnricher {
	/**
	 */
	public function __construct() {
		parent::__construct(function ($ids) {
			return array_combine($ids, $ids);
		});
	}
}
