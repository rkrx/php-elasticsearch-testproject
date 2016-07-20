<?php
namespace ElasticSearch\Request\PostProcessing;

class KeyValueEnricher {
	/** @var callable */
	private $callback;

	/**
	 * @param callable $callback
	 */
	public function __construct($callback) {
		$this->callback = $callback;
	}

	/**
	 * @param array $data
	 * @return array
	 */
	public function __invoke(array $data) {
		if(array_key_exists('buckets', $data)) {
			$ids = [];
			foreach($data['buckets'] as $bucket) {
				if(array_key_exists('key', $bucket)) {
					$ids[] = $bucket['key'];
				}
			}

			if(count($ids)) {
				$keyValueArray = call_user_func($this->callback, $ids);

				foreach($data['buckets'] as $key => $bucket) {
					if(array_key_exists('key', $bucket)) {
						if(array_key_exists($bucket['key'], $keyValueArray)) {
							$data['buckets'][$key]['data'] = $keyValueArray[$bucket['key']];
						}
					}
				}
			}
		}
		return $data;
	}
}
