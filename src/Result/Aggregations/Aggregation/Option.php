<?php
namespace ElasticSearch\Result\Aggregations\Aggregation;

class Option {
	/** @var array */
	private $data;

	/**
	 * @param array $data
	 */
	public function __construct(array $data) {
		$this->data = $data;
	}

	/**
	 * @return string
	 */
	public function getKey() {
		return $this->data['key'];
	}

	/**
	 * @return string
	 */
	public function getData() {
		if(array_key_exists('data', $this->data)) {
			return $this->data['data'];
		}
		return null;
	}

	/**
	 * @return int
	 */
	public function getDocCount() {
		return array_key_exists('doc_count', $this->data) ? $this->data['doc_count'] : 0;
	}

	/**
	 * @return string
	 */
	public function __toString() {
		if(array_key_exists('label', $this->data)) {
			if(array_key_exists('buckets', $this->data['label'])) {
				$result = [];
				foreach($this->data['label']['buckets'] as $bucket) {
					if(array_key_exists('key', $bucket)) {
						$result[] = $this->upperCaseFirst($bucket['key']);
					}
				}
				return join(' ', $result);
			}
		}
		return (string) $this->getKey();
	}

	/**
	 * @param string $str
	 * @param string $encoding
	 * @return string
	 */
	private function upperCaseFirst($str, $encoding = "UTF-8") {
		$firstLetter = mb_strtoupper(mb_substr($str, 0, 1, $encoding), $encoding);
		$strEnd = mb_substr($str, 1, mb_strlen($str, $encoding), $encoding);
		$str = $firstLetter . $strEnd;
		return $str;
	}
}
