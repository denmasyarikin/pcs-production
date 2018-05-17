<?php 

namespace Denmasyarikin\Production\Service\Factories\Configuration;

class MultiplicationVolumeConfiguration extends Configuration implements ConfigurationInterface
{
	/**
	 * configuration type
	 *
	 * @var string
	 */
	protected $type = 'multiplication_volume';

	/**
	 * structure
	 *
	 * @var array
	 */
	protected $structure = [
		'relativity' => ['unit_price', 'unit_total'],
		'min_width' => 'integer',
		'max_width' => 'integer',
		'min_length' => 'integer',
		'max_length' => 'integer',
		'min_height' => 'integer',
		'max_height' => 'integer',
		'default_width' => 'integer',
		'default_length' => 'integer',
		'default_height' => 'integer'
	];
}