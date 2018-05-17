<?php 

namespace Denmasyarikin\Production\Service\Factories\Configuration;

interface ConfigurationInterface
{
	/**
	 * configuration type
	 *
	 * @return string
	 */
	public function getType();

	/**
	 * configuration structure
	 *
	 * @return string
	 */
	public function getStructure();

	/**
	 * is valid structure
	 *
	 * @param array $config
	 * @return bool
	 */
	public function isValidStructure(array $config);
}
