<?php

namespace yiiCustom\behaviors;

use DateTime;
use DateTimeZone;
use yii\behaviors\TimestampBehavior;

/**
 * Поведение моделей для автоматического указания stamp создания/обновления записи. Все даты-время пишутся в таймзоне UTC
 */
class TimestampUTCBehavior extends TimestampBehavior {

	const ATTR_CREATED_AT_ATTRIBUTE = 'createdAtAttribute';
	const ATTR_UPDATED_AT_ATTRIBUTE = 'updatedAtAttribute';

	/** @var int|null Количество десятичных знаков у секунд (не больше 6) */
	public $decimalsCount = null;
	const ATTR_DECIMALS_COUNT = 'decimalsCount';

	/**
	 * @inheritdoc
	 */
	protected function getValue($event) {
		$datetime = new DateTime('now', new DateTimeZone('UTC'));

		$result = $datetime->format('Y-m-d H:i:s');

		if ($this->decimalsCount !== null) {
			$result .= substr((string)microtime() , 1, $this->decimalsCount + 1);
		}

		return $result;
	}
}