<?php


namespace yiiCustom\logger;

/**
 * Интерфейс потока логгирования.
 */
interface LoggerStream {
	
	/** Тип - обычное сообщение */
	const TYPE_MESSAGE = 1;
	/** Тип - ошибка */
	const TYPE_ERROR = 2;

	/**
	 * Добавление записи
	 *
	 * @param string $message Сообщение
	 * @param int    $type    Тип сообщения
	 */
	public function log($message, $type = self::TYPE_MESSAGE);

}