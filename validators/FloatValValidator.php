<?php
namespace vendor\Evil1991\yii_custom\validators;

use yii\validators\FilterValidator;

/**
 * Расширение валидатора: автоматическое приведение к типу float.
 */
class FloatValValidator extends FilterValidator {

	/** @inheritdoc */
	public $filter = 'floatval';

	/** @inheritdoc */
	public $skipOnEmpty = true;
}