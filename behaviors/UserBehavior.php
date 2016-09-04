<?php

namespace YiiCustom\behaviors;

use Yii;
use yii\base\UnknownMethodException;
use yii\behaviors\AttributeBehavior;
use yii\db\BaseActiveRecord;

/**
 * Поведение для моделей для автоматического указания guid-а пользователя, создавшего/изменившего запись
 *
 * @package common\base
 */
class UserBehavior extends AttributeBehavior {

	/**
	 * Поле, в которое пишем guid пользователя, создавшего запись
	 * если false, то не будет использоваться
	 * @var string
	 */
	public $createdAtAttribute = 'create_user_guid';
	const ATTR_CREATED_AT_ATTRIBUTE = 'createdAtAttribute';

	/**
	 * Поле, в которое пишем guid пользователя, обновившего запись
	 * @var string
	 * если false, то не будет использоваться
	 */
	public $updatedAtAttribute = 'update_user_guid';
	const ATTR_UPDATED_AT_ATTRIBUTE = 'updatedAtAttribute';


	/**
	 * @inheritdoc
	 */
	public function init() {
		parent::init();

		if (empty($this->attributes)) {
			$this->attributes = [
				BaseActiveRecord::EVENT_BEFORE_INSERT => [$this->createdAtAttribute, $this->updatedAtAttribute],
				BaseActiveRecord::EVENT_BEFORE_UPDATE => $this->updatedAtAttribute,
			];
		}
	}

	/**
	 * @inheritdoc
	 */
	protected function getValue($event) {
		if (Yii::$app->user !== null) {
			return Yii::$app->user->id;
		}

		return null;
	}

}