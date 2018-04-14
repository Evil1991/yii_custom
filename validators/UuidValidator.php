<?php

namespace yiiCustom\validators;

use yii\validators\RegularExpressionValidator;

/**
 * Валидатор для проверки значения на формат GUID.
 */
class UuidValidator extends RegularExpressionValidator {
	public $pattern = '/^[a-f0-9]{8}-[a-f0-9]{4}-[a-f0-9]{4}-[a-f0-9]{4}-[a-f0-9]{12}$/i';
}