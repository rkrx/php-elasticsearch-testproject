<?php
namespace ElasticSearch;

use Elastica\Client;

class IndexRepository {
	/** @var Client */
	private $client;

	/**
	 * @param Client $client
	 */
	public function __construct(Client $client) {
		$this->client = $client;
	}

	/**
	 * @param string $typeName
	 * @return Index
	 */
	public function getIndex($typeName) {
		$index = $this->client->getIndex($typeName);
		return new Index($index);
	}
}
