<?php

namespace vendor\Evil1991\yii_custom\base;
use yiiCustom\base\DateTimeZone;

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
	 * @param DateTimeZone|null $timeZone Таймзона, по которой нужно вывести данные. Если null, то берётся таймзона текущего объекта
	 *
	 * @return int
	 */
	public function getWeekdayNumber(DateTimeZone $timeZone = null) {
		$date = $this;
		if ($timeZone !== null) {
			$date = clone $this;
			$date->setTimezone($timeZone);
		}

		$dayNumber = (int)$date->format('w');

		return static::WEEK_DAYS_NUMBERS_CONVERSION[$dayNumber];
	}

	/**
	 * Выпадает ли текущая дата на выходной день.
	 *
	 * @param DateTimeZone|null $timeZone Таймзона, по которой нужно вывести данные. Если null, то берётся таймзона текущего объекта
	 *
	 * @return bool
	 */
	public function isWeekend(DateTimeZone $timeZone = null) {
		return in_array($this->getWeekdayNumber($timeZone), [5,6]);
	}

	/**
	 * Конвертация даты-времени к началу недели.
	 *
	 * @param DateTimeZone|null $timeZone Таймзона, по которой нужно вывести данные. Если null, то берётся таймзона текущего объекта
	 */
	public function convertToWeekBegin(DateTimeZone $timeZone = null) {
		$interval = new \DateInterval('P' . $this->getWeekdayNumber($timeZone) . 'D');
		$interval->invert = 1;
		$this->add($interval);
	}
}