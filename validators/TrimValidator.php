<?php
namespace yiiCustom\validators;

use common\yii\validators\FilterValidator;

/**
 * Валидатор для удаления пробелов в начале и в конце.
 */
class TrimValidator extends FilterValidator {
	public $filter = 'trim';
}