<?php
namespace ElasticSearch;

use Elastica;
use ElasticSearch\Request\PostProcessing\KeyAsDataEnricher;
use ElasticSearch\Request\PostProcessing\KeyValueEnricher;
use Kir\MySQL\Database;

class TestRequest extends Request {
	/**
	 * @param Elastica\Type $type
	 * @param string $languageId
	 * @param Database $db
	 * @throws \Exception
	 */
	public function __construct(Elastica\Type $type, $languageId, Database $db) {
		parent::__construct($type);

		$this->query(
			$this->query()->allMust(
				$this->query()->match('inventory.domain-5.domain_id', 'domain'),
				$this->query()->someMust(
					$this->query()->matchPhrase("description.{$languageId}.title", 'query', ['slop' => 0, 'boost' => 10]),
					$this->query()->matchPhrase("description.{$languageId}.title", 'query', ['slop' => 1, 'boost' => 8]),
					$this->query()->matchPhrase("description.{$languageId}.title", 'query', ['slop' => 2, 'boost' => 6])
					#$this->query()->match("description.{$languageId}.title", 'query', ['boost' => 5, 'slop' => 1])#,
					//$this->query()->match("description.{$languageId}.keywords", 'query', ['boost' => 2]),
					//$this->query()->fuzzyMatch("description.{$languageId}.title", 'query', ['boost' => 1.5]),
					//$this->query()->match("description.{$languageId}.description", 'query')
				)
			)
		);

		$this->aggregations()->addAggregation(
			$this->aggregations()->createDynamicTermListAggregation(
				'vendor', // Techn. Name der Facetten-Gruppe und dies ist gleichzeitig auch der Name des URL-Feldes
				'vendor_id', // So heißt das Feld des ElasticSearch-Dokuments
				'Hersteller' // Datenfeld, dass beliebig verwendet werden kann
			)
			->setPostProcessor(new KeyValueEnricher(function ($ids) use ($db) {
				return $db->select()
				->field('v.vendor_id')
				->field('v.vendor_internal_name')
				->from('v', 'shop#vendors')
				->where('v.vendor_id IN (?)', $ids)
				->fetchKeyValue();
			}))
		);

		$this->aggregations()->addAggregation(
			$this->aggregations()->createDynamicTermListAggregation('type', 'type_id', 'Typ')
			->setPostProcessor(new KeyValueEnricher(function ($ids) use ($db) {
				return $db->select()
				->field('d.descriptiontable_id')
				->field('d.descriptiontable_name')
				->from('d', 'shop#product_descriptiontables')
				->where('d.descriptiontable_id IN (?)', $ids)
				->fetchKeyValue();
			}))
		);

		$this->aggregations()->addAggregation(
			$this->aggregations()->createDynamicTermListAggregation('color', 'color_id', 'Farbe')
			->setPostProcessor(new KeyAsDataEnricher())
		);

		$this->aggregations()->addAggregation(
			$this->aggregations()->createMinAggregation('min_price', "inventory.domain-5.price")
		);

		$this->aggregations()->addAggregation(
			$this->aggregations()->createMaxAggregation('max_price', "inventory.domain-5.price")
		);
	}
}
