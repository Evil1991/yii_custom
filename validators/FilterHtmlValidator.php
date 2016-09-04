<?php

namespace YiiCustom\validators;

use yii\helpers\HtmlPurifier;
use yii\validators\FilterValidator;

/**
 * Фильтр-валидатор для HTML-контента.
 */
class FilterHtmlValidator extends FilterValidator {

	/**
	 * @inheritdoc
	 */
	public function init() {
		parent::init();
		$this->filter = function ($value) {
			return HtmlPurifier::process($value);
		};
	}

}