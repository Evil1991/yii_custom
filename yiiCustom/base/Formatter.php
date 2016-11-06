<?php

namespace yiiCustom\base;

use DateTime;
use Yii;
use yii\helpers\Html;

class Formatter extends \yii\i18n\Formatter {

	const FORMAT_LOCAL_DATETIME = 'localDateTime';

	protected $localTimezone;

	public function init() {
		parent::init();

		if (isset(Yii::$app->params['localTimezoneOffset'])) {
			$offset = Yii::$app->params['localTimezoneOffset'];
		}
		else {
			$offset = 0;
		}

		$this->localTimezone = DateTimeZone::getTimezoneByUtcOffset($offset);

	}

	/**
	 * Вывод даты, времени в локальном формате
	 * @param      $value
	 * @param null $format
	 * @return string
	 */
	public function asLocalDateTime($value, $format = null) {
		if ($format === null) {
			$format = 'd.m.Y H:i:s';
		}

		if ($value === null) {
			return '';
		}

		return (new DateTime($value, new DateTimeZone('UTC')))
			->setTimezone($this->localTimezone)
			->format($format);
	}

	/**
	 * Вывод даты в локальном формате
	 * @param      $value
	 * @param null $format
	 * @return string
	 */
	public function asLocalDate($value, $format = null) {
		if ($format === null) {
			$format = 'd.m.Y';
		}

		if ($value === null) {
			return '';
		}

		return (new DateTime($value, new DateTimeZone('UTC')))
			->setTimezone(DateTimeZone::getTimezoneByUtcOffset(10))
			->format($format);
	}

	/**
	 * Вывод булева в текстовом виде.
	 *
	 * @param mixed $value
	 * @return string
	 */
	public function asBoolean($value) {
		return $value
			? (Html::tag('span', '', ['class' => 'glyphicon glyphicon-ok']) . 'Да')
			: (Html::tag('span', '', ['class' => 'glyphicon glyphicon-remove']) . 'Нет');
	}

	/**
	 * Получение разницы между двумя датами в строковом представлении
	 *
	 * @param string $dateFirst Дата начала
	 * @param string $dateSecond Дата окончания
	 *
	 * @return string
	 */
	public function showTimeDiff($dateFirst, $dateSecond) {
		$tz = new DateTimeZone('UTC');
		$d1 = new DateTime($dateFirst, $tz);
		$d2 = new DateTime($dateSecond, $tz);

		$diff = $d2->diff($d1);

		$result = '';

		if ($diff->h > 0) {
			$result .= $diff->h . ' ч., ';
		}

		$result .= $diff->m . ' мин. '
			. $diff->s . ' сек.';


		return $result;
	}

	/**
	 * Вывод цены
	 *
	 * @param float $value        Значение
	 * @param bool  $withDecimals С сотыми долями суммы (копейками)
	 *
	 * @return string
	 */
	public function asPrice($value, $withDecimals = false) {
		return number_format($value, $withDecimals ? 2 : 0, '.', ' ') . ' руб.';
	}

	/**
	 * Форматирование даты-времени для БД.
	 *
	 * @param string            $dateTime      Дата-время
	 * @param DateTimeZone|null $localTimezone Локальная таймзона
	 *
	 * @return string
	 */
	public function asDbDateTime($dateTime, DateTimeZone $localTimezone = null) {
		if ($localTimezone === null) {
			$localTimezone = $this->localTimezone;
		}

		$dateTime = new DateTime($dateTime, $localTimezone);

		$dateTime->setTimezone(new DateTimeZone('UTC'));

		return $dateTime->format('Y-m-d H:i:s');
	}

}