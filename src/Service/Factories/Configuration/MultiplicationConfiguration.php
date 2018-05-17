<?php 

namespace Denmasyarikin\Production\Service\Factories\Configuration;

class MultiplicationConfiguration extends Configuration implements ConfigurationInterface
{
	/**
	 * configuration type
	 *
	 * @var string
	 */
	protected $type = 'multiplication';

	/**
	 * structure
	 *
	 * @var array
	 */
	protected $structure = [
		'relativity' => ['unit_price', 'unit_total'],
		'min' => 'integer',
		'max' => 'integer',
		'default' => 'integer'
	];
}