<?php

namespace vendor\Evil1991\yii_custom\validators;

/**
 * Валидатор для даты, поддерживающий даты в формате Atom.
 */
class DateValidator extends \yii\validators\DateValidator {

	/** @var bool Дата в формате atom, если false - то валидация происходит через yii-вский валидатор */
	public $isAtom = false;
	const ATTR_IS_ATOM = 'isAtom';

	/**
	 * @inheritdoc
	 */
	public function validateAttribute($model, $attribute) {
		if ($this->isAtom === true) {
			$value = $model->$attribute;
			try {
				new DateTime($value);
			}
			catch (\Exception $e) {
				$this->addError($model, $attribute, $this->message);
			}
		}
		else {
			return parent::validateAttribute($model, $attribute);
		}
	}
}