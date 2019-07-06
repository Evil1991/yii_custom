<?php
namespace vendor\Evil1991\yii_custom\validators;

/**
 * @inheritdoc
 */
class PositiveIntegerValidator extends IntegerValidator {
	/** @inheritdoc */
	public $min = 0;
}