<?php
/**
 * Copyright (c) 2014 Georg Ehrke <oc.list@georgehrke.com>
 * This file is licensed under the Affero General Public License version 3 or
 * later.
 * See the COPYING-README file.
 */
namespace OCA\Calendar\Db;

use OCP\AppFramework\IAppContainer;

class SubscriptionMapper extends Mapper {

	/**
	 * @brief Constructor
	 * @param IAppContainer $app
	 * @param string $tableName
	 */
	public function __construct($app, $tableName='clndr_sbscrptns'){
		parent::__construct($app, $tableName);
	}


	/**
	 * find subscription by id, userId
	 * @param int $id
	 * @param string $userId
	 * @throws \OCP\Calendar\DoesNotExistException: if the item does not exist
	 * @throws \OCP\Calendar\MultipleObjectsReturnedException: if more than one item found
	 * @return Subscription object
	 */
	public function find($id, $userId){
		$sql  = 'SELECT * FROM `' . $this->getTableName() . '` ';
		$sql .= 'WHERE `id` = ? AND `user_id` = ?';

		$row = $this->findOneQuery($sql, array(
			$id,
			$userId
		));

		return new Subscription($row);
	}


	/**
	 * does a subscription exist
	 * @param int $id
	 * @param string $userId
	 * @return boolean
	 */
	public function doesExist($id, $userId) {
		$sql  = 'SELECT COUNT(*) AS `count` FROM `' . $this->tableName . '`';
		$sql .= ' WHERE `id` = ? AND `user_id` = ?';

		$row = $this->findOneQuery($sql, array(
			$id,
			$userId
		));

		$count = intval($row['count']);
		return ($count !== 0);
	}


	/**
	 * does a subscription of a certain type exist
	 * @param int $id
	 * @param string $type
	 * @param string $userId
	 * @return boolean
	 */
	public function doesExistOfType($id, $type, $userId) {
		$sql  = 'SELECT COUNT(*) AS `count` FROM `' . $this->tableName . '`';
		$sql .= ' WHERE `id` = ? AND `type` = ? AND `user_id` = ?';

		$row = $this->findOneQuery($sql, array(
			$id,
			$type,
			$userId
		));

		$count = intval($row['count']);
		return ($count !== 0);
	}


	/**
	 * @param string $userId
	 * @return integer
	 */
	public function count($userId) {
		$sql  = 'SELECT COUNT(*) AS `count` FROM `' . $this->tableName . '`';
		$sql .= ' WHERE `user_id` = ?';

		$row = $this->findOneQuery($sql, array(
			$userId
		));

		return intval($row['count']);
	}


	/**
	 * @param string $type
	 * @param string $userId
	 * @return integer
	 */
	public function countByType($type, $userId) {
		$sql  = 'SELECT COUNT(*) AS `count` FROM `' . $this->tableName . '`';
		$sql .= ' WHERE `type` = ? AND `user_id` = ?';

		$row = $this->findOneQuery($sql, array(
			$type,
			$userId
		));

		return intval($row['count']);
	}


	/**
	 * find all subscriptions of a user
	 * @param string $userId
	 * @param integer $limit
	 * @param integer $offset
	 * @return SubscriptionCollection
	 */
	public function findAll($userId, $limit, $offset){
		$sql  = 'SELECT * FROM `'. $this->getTableName() . '` ';
		$sql .= 'WHERE `user_id` = ?';

		return $this->findEntities($sql, array(
			$userId
		), $limit, $offset);
	}


	/**
	 * find all subscriptions of a user by type
	 * @param string $userId
	 * @param string $type
	 * @param integer $limit
	 * @param integer $offset
	 * @return SubscriptionCollection
	 */
	public function findAllByType($userId, $type, $limit, $offset){
		$sql  = 'SELECT * FROM `'. $this->getTableName() . '` ';
		$sql .= 'WHERE `user_id` = ? AND `type` = ?';

		return $this->findEntities($sql, array(
			$userId,
			$type
		), $limit, $offset);
	}
}