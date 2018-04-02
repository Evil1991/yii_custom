<?php

namespace vendor\Evil1991\yii_custom\base;

/**
 * Расширение класса DateTime.
 */
class DateTime extends \DateTime {

	/** Массив соответствия номеров дней по нумерации, где первый день недели - воскресенье и где первый день - понедельник */
	const WEEK_DAYS_NUMBERS_CONVERSION = [
		0 => 6,
		1 => 0,
		2 => 1,
		3 => 2,
		4 => 3,
		5 => 4,
		6 => 5,
	];

	/**
	 * Получение номера дня недели.
	 * Нумерация производится исходя из того, что понедельник - 0, вторник - 1 и т.д.
	 *
	 * @return int
	 */
	public function getWeekdayNumber() {
		$dayNumber = (int)$this->format('w');

		return static::WEEK_DAYS_NUMBERS_CONVERSION[$dayNumber];
	}

	/**
	 * Выпадает ли текущая дата на выходной день.
	 *
	 * @return bool
	 */
	public function isWeekend() {
		return in_array($this->getWeekdayNumber(), [5,6]);
	}
}