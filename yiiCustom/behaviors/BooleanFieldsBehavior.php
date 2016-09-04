<?php

namespace yiiCustom\behaviors;

use yiiCustom\ActiveRecord;
use yii\base\Behavior;
use yii\base\Event;

/**
 * Поведение для boolean-полей для приведения их значений к boolean-типу.
 */
class BooleanFieldsBehavior extends Behavior {

	/** @var string[] Поля для приведения типа */
	public $fields = [];
	const ATTR_FIELDS = 'fields';

	/**
	 * @inheritdoc
	 */
	public function events() {
		return [
			ActiveRecord::EVENT_AFTER_FIND => function (Event $event) {
				/** @var ActiveRecord $model */
				$model = $event->sender;
				$this->castFields($model);
			},
		];
	}

	/**
	 * Приведение полей к boolean.
	 *
	 * @param ActiveRecord $model Модель
	 */
	protected function castFields(ActiveRecord $model) {
		foreach ($this->fields as $fieldName) {
			$model->$fieldName = (bool)$model->$fieldName;
		}
	}
}