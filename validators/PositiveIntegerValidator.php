<?php
namespace vendor\Evil1991\yii_custom\validators;

use common\yii\validators\IntegerValidator;

/**
 * @inheritdoc
 */
class PositiveIntegerValidator extends IntegerValidator {
	/** @inheritdoc */
	public $integerOnly = true;

	/** @inheritdoc */
	public $min = 0;
}