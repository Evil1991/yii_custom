<?php

namespace yiiCustom\helpers;

class DateHelper {

	/** Формат даты-времени в БД */
	const DATE_TIME_DATABASE_FORMAT = 'Y-m-d H:i:s';

	/** Формат даты-времени в БД c микросекундами */
	const MICRO_DATE_TIME_DATABASE_FORMAT = 'Y-m-d H:i:s.u';

	/** Количество секунд в 1 часе */
	const SEC_OF_HOUR = 3600;

	/** Количество секунд в 1 сутках */
	const SEC_OF_DAY = self::SEC_OF_HOUR * 24;

}