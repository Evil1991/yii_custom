<?php

namespace yiiCustom\logger;

use YiiCustom\DateTimeZone;
use YiiCustom\helpers\StringHelper;
use Yii;
use yii\base\Component;
use yii\helpers\Console;

class StdoutLogger extends Component implements LoggerStream {

	/** Разделитель между полями вывода */
	const FIELD_DIVIDER = "\t";

	/** @var bool Вывод даты-времени */
	public $dateTimeOut = true;
	const ATTR_DATE_TIME_OUT = 'dateTimeOut';

	/** @var string Формат даты-времени вывода */
	public $dateTimeFormat = 'd.m.Y H:i:s';
	const ATTR_DATE_TIME_FORMAT = 'dateTimeFormat';

	/** @var bool Вывод использования памяти */
	public $memoryUsageOut = true;
	const ATTR_MEMORY_USAGE_OUT = 'memoryUsageOut';

	/** @var DateTimeZone Таймзона для вывода времени */
	protected $timezone;

	/**
	 * @inheritdoc
	 */
	public function init() {
		parent::init();

		$this->timezone = DateTimeZone::getTimezoneByUtcOffset(Yii::$app->params['localTimezoneOffset']);
	}

	/**
	 * Добавление записи
	 *
	 * @inheritdoc
	 */
	public function log($logMessage, $type = self::TYPE_MESSAGE) {
		$message = '';

		if ($this->dateTimeOut) {
			$message .= (new \DateTime('now', $this->timezone))->format($this->dateTimeFormat) . static::FIELD_DIVIDER;
		}

		if ($this->memoryUsageOut) {
			$message  .= StringHelper::formatSize(memory_get_usage(true)) . ' б.' . static::FIELD_DIVIDER;
		}

		$message .= $logMessage . PHP_EOL;

		switch ($type) {
			case static::TYPE_MESSAGE:
				Console::stdout($message);

				break;

			case static::TYPE_ERROR:
				Console::stderr($message);

				break;
		}
	}
}