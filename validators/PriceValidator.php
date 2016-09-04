<?php

namespace YiiCustom\validators;

use yii\validators\NumberValidator;

/**
 * Валидатор цены.
 */
class PriceValidator extends NumberValidator {

	/** @var bool Цена может быть отрицательной */
	public $acceptNegative = false;
	const ATTR_ACCEPT_NEGATIVE = 'acceptNegative';
	
	/** @var bool Цена может быть нулевой */
	public $acceptZero = true;
	const ATTR_ACCEPT_ZERO = 'acceptZero';

	/**
	 * @inheritdoc
	 */
	public function validateAttribute($model, $attribute) {
		if (!$this->acceptNegative && $model->$attribute < 0) {
			$this->addError($model, $attribute, '{attribute} не может быть меньше 0');
		}

		if (!$this->acceptZero && $model->$attribute === 0) {
			$this->addError($model, $attribute, '{attribute} не может быть равно 0');
		}

		return parent::validateAttribute($model, $attribute);
	}
}