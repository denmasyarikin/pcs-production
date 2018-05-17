<?php 

namespace Denmasyarikin\Production\Service\Factories\Configuration;

class IncreasementConfiguration extends Configuration implements ConfigurationInterface
{
	/**
	 * configuration type
	 *
	 * @var string
	 */
	protected $type = 'increasement';

	/**
	 * structure
	 *
	 * @var array
	 */
	protected $structure = [
		'relativity' => ['unit_price', 'unit_total'],
		'multiples' => 'integer',
		'rule' => ['fixed', 'percentage'],
		'value' => 'integer'
	];
}