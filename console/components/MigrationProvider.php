<?php

namespace yiiCustom\console\components;

use Yii;
use yii\base\InvalidParamException;
use yii\db\Migration;

/**
 * Провайдер для получения миграций.
 */
class MigrationProvider {

	/**
	 * Получить объект миграции
	 *
	 * @param string $path  Путь к файлу миграции
	 * @param string $class Имя класса
	 *
	 * @return Migration объект миграции
	 */
	public static function getMigration($path, $class) {
		$file = Yii::getAlias($path . DIRECTORY_SEPARATOR . $class . '.php');

		if (file_exists($file) === false) {
			throw new InvalidParamException('Файл миграцим ' . $file . ' (путь: ' . $path . ') не существует');
		}

		require_once($file);

		if (class_exists($class) === false) {
			throw new InvalidParamException('Миграция ' . $class . ' (путь: ' . $path . ') не существует');
		}

		/** @var Migration $mirgation */
		$mirgation = new $class();

		return $mirgation;
	}

}