<?php

namespace KZ\app\interfaces;

/**
 * We need special registry, which store and returns necessary components for application.
 *
 * Interface Registry
 * @package KZ\app\interfaces
 */
interface Registry extends \ArrayAccess, \Iterator
{
	/**
	 * @return \PDO
	 */
	public function getDb();

	/**
	 * @return \KZ\db\interfaces\ConnectionStorage
	 */
	public function getConnectionStorage();

	/**
	 * @param \KZ\db\interfaces\ConnectionStorage $connectionStorage
	 * @return $this
	 */
	public function setConnectionStorage(\KZ\db\interfaces\ConnectionStorage $connectionStorage);

	/**
	 * @return \KZ\app\interfaces\Kit
	 */
	public function getKit();

	/**
	 * @param Kit $kit
	 * @return $this
	 */
	public function setKit(Kit $kit);

	/**
	 * @return array
	 */
	public function getConfig();

	/**
	 * @param array $config
	 * @return $this
	 */
	public function setConfig(array $config);
} 