<?php
namespace vendor\Evil1991\yii_custom\validators;

use yii\validators\FilterValidator;

/**
 * Расширение валидатора: trim, strip_tags.
 */
class StripValidator extends FilterValidator {
	/**
	 * @inheritdoc
	 *
	 * @author Залатов Александр <zalatov.ao@dns-shop.ru>
	 */
	public function init() {
		$this->filter = function($value) {
			return trim(strip_tags($value));
		};

		parent::init();
	}
}