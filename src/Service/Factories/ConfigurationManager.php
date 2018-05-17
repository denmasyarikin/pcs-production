<?php 

namespace Denmasyarikin\Production\Service\Factories;

use Denmasyarikin\Production\Service\Factories\Configuration;

class ConfigurationManager
{
	/**
	 * configuration
	 *
	 * @var array
	 */
	protected $configurations = [
		Configuration\IncreasementConfiguration::class,
		Configuration\MultiplicationConfiguration::class,
		Configuration\MultiplicationAreaConfiguration::class,
		Configuration\MultiplicationVolumeConfiguration::class,
		Configuration\SelectionConfiguration::class
	];

	/**
	 * configuration instance
	 *
	 * @var array
	 */
	protected $configurationInstances;

	/**
	 * Create a new ConfigurationManager instance.
	 *
	 * @return void
	 */
	public function __construct()
	{
		$this->instantiateConfigurations();
	}

	/**
	 * instantiate configurations
	 *
	 * @return void
	 */
	protected function instantiateConfigurations()
	{
		foreach ($this->configurations as $configuration) {
			$instance = new $configuration();
			$this->configurationInstances[$instance->getType()] = $instance;
		}
	}

	/**
	 * get configuration instances
	 *
	 * @return array
	 */
	public function getConfigurationInstances()
	{
		return $this->configurationInstances;
	}

	/**
	 * get configuration instance
	 *
	 * @param string $type
	 * @return mixed
	 */
	public function getConfigurationInstance($type)
	{
		if ($this->isConfigurationExists($type)) {
			return $this->configurationInstances[$type];
		}
	}

	/**
	 * check is configuration exists
	 *
	 * @param string $type
	 * @return bool
	 */
	public function isConfigurationExists($type)
	{
		return isset($this->configurationInstances[$type]);
	}
}