<?php
namespace vendor\Evil1991\yii_custom\validators;

use yii\validators\FilterValidator;

/**
 * Расширение валидатора: автоматическое приведение к типу boolean.
 */
class BoolValValidator extends FilterValidator {
	/** @inheritdoc */
	public $filter = 'boolval';

	/** @inheritdoc */
	public $skipOnEmpty = true;
}