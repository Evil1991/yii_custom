<?php

namespace yiiCustom\base;

class ActiveQuery extends \yii\db\ActiveQuery {

	/** @var bool Нужно ли добавить в select инструкцию NO UPDATE */
	private $isSelectForUpdate = false;

	/**
	 * Установка чтения с блокировкой.
	 */
	public function forUpdate() {
		$this->isSelectForUpdate = true;
	}

	/**
	 * @inheritdoc
	 */
	public function createCommand($db = null) {
		/* @var $modelClass ActiveRecord */
		$modelClass = $this->modelClass;
		if ($db === null) {
			$db = $modelClass::getDb();
		}

		if ($this->sql === null) {
			list ($sql, $params) = $db->getQueryBuilder()->build($this);
		} else {
			$sql = $this->sql;
			$params = $this->params;
		}

		if ($this->isSelectForUpdate === true) {
			$sql .= ' FOR UPDATE';
		}

		return $db->createCommand($sql, $params);
	}

}