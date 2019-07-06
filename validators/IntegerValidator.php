<?php

namespace vendor\Evil1991\yii_custom\validators;

use yii\validators\NumberValidator;

/**
 * @inheritdoc
 *
 * @author Казанцев Александр <kazancev.al@dns-shop.ru>
 */
class IntegerValidator extends NumberValidator {

	/** @inheritdoc */
	public $integerOnly = true;
}