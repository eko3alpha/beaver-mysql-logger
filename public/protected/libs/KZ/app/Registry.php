<?php

namespace KZ\app;
use KZ\db;

/**
 * We need special registry, which store and returns necessary components for application.
 *
 * Class Registry
 * @package KZ\app
 *
 * Following properties are available via magic methods (get/set):
 * @property \PDO $db
 * @property \PDO $mysql
 * @property \KZ\db\interfaces\ConnectionStorage $connectionStorage
 * @property \KZ\app\interfaces\Kit $kit
 */
class Registry extends \KZ\Registry implements interfaces\Registry
{
	/**
	 * @return \PDO
	 */
	public function getDb()
	{
		return $this->getConnectionStorage()->getDefault();
	}

	/**
	 * @return \PDO
	 */
	public function getMysql()
	{
		$mysql = $this->getConnectionStorage()->getByType(db\interfaces\ConnectionStorage::MYSQL);

		if (!$mysql)
			return null;

		return $mysql[array_keys($mysql)[0]];
	}

	/**
	 * @throws \UnderflowException
	 * @return \KZ\db\interfaces\ConnectionStorage
	 */
	public function getConnectionStorage()
	{
		if (!isset($this->data['connectionStorage']))
			throw new \UnderflowException('You must setup connectionStorage before calling this method!');

		return $this->data['connectionStorage'];
	}

	/**
	 * @param db\interfaces\ConnectionStorage $connectionStorage
	 * @return $this
	 */
	public function setConnectionStorage(\KZ\db\interfaces\ConnectionStorage $connectionStorage)
	{
		$this->data['connectionStorage'] = $connectionStorage;

		return $this;
	}

	/**
	 * @throws \UnderflowException
	 * @return \KZ\app\interfaces\Kit
	 */
	public function getKit()
	{
		if (!isset($this->data['kit']))
			throw new \UnderflowException('You must setup kit before calling this method!');

		return $this->data['kit'];
	}

	/**
	 * @param \KZ\app\interfaces\Kit $kit
	 * @return $this
	 */
	public function setKit(\KZ\app\interfaces\Kit $kit)
	{
		$this->data['kit'] = $kit;

		return $this;
	}
} 