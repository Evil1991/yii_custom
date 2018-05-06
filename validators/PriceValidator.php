<?php

namespace yiiCustom\validators;

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
		$value = (int)$model->$attribute;
		if (!$this->acceptNegative && $value < 0) {
			$this->addError($model, $attribute, '{attribute} не может быть меньше 0');
		}

		if (!$this->acceptZero && $value === 0) {
			$this->addError($model, $attribute, '{attribute} не может быть равно 0');
		}

		return parent::validateAttribute($model, $attribute);
	}
}