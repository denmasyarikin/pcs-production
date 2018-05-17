<?php 

namespace Denmasyarikin\Production\Service\Factories\Configuration;

class SelectionConfiguration extends Configuration implements ConfigurationInterface
{
	/**
	 * configuration type
	 *
	 * @var string
	 */
	protected $type = 'selection';

	/**
	 * structure
	 *
	 * @var array
	 */
	protected $structure = [
		'value' => 'array',
		'multiple' => 'boolean',
		'affected_the_price' => 'boolean',
		'relativity' => [null, 'unit_price', 'unit_total'],
		'rule' => [null, 'fixed', 'percentage'],
		'formula' => [null, 'multiplication', 'division', 'addition', 'reduction']
	];

	/**
	 * is valid structure
	 *
	 * @param array $config
	 * @return bool
	 */
	public function isValidStructure(array $config)
	{
		return parent::isValidStructure($config)
			AND $this->isValidAffectedThePriceStructure($config);
	}

	/**
	 * check is valid affected the price structure
	 *
	 * @param array $config
	 * @return bool
	 */
	protected function isValidAffectedThePriceStructure(array $config)
	{
		if ($config['affected_the_price']) {
			if (is_null($config['relativity']) OR is_null($config['rule']) OR is_null($config['formula'])) {
				return false;
			}

			foreach ($config['value'] as $value) {
				if (!is_array($value)) {
					return false;
				}

				if (!isset($value['label']) OR !isset($value['value']) OR !is_int($value['value'])) {
					return false;
				}
			}

			return true;
		} else {
			foreach ($config['value'] as $value) {
				if (is_array($value)) {
					return false;
				}
			}

			return true;
		}
	}
}