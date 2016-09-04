<?php

namespace YiiCustom\validators;

use yii\validators\NumberValidator;

/**
 * Валидатор ссылок на другие сущности.
 * Проверяет, что ссылка не пустая.
 */
class ReferenceValidator extends NumberValidator {

	public $min = 1;

	/**
	 * @inheritdoc
	 */
	public function init() {
		parent::init();
		
		//заменяем сообщение для слишком маленького значения обычным сообщением
		$this->tooSmall = $this->message;
	}

}