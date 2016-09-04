<?php

namespace YiiCustom\console\controllers;

use Yii;
use yii\base\InvalidParamException;
use yii\console\Controller;
use yii\console\Exception;
use yii\db\Migration;
use yii\helpers\Console;

/**
 * Контроллер ACL.
 */
class AclInitController extends Controller {

	protected $rbacMigrationPath  = '@yii/rbac/migrations/';
	protected $rbacMigrationClass = 'm140506_102106_rbac_init';

	/**
	 * Первичная инициализация ACL-структуры.
	 */
	public function actionCreateAclTables() {
		$this->stdout('Создаём структуру таблиц ACL' . PHP_EOL);

		if ($this->getMigration($this->rbacMigrationPath, $this->rbacMigrationClass)->up() !== false) {
			$this->stdout('Успешно' . PHP_EOL, Console::FG_GREEN);
		}
		else {
			$this->stdout('Ошибка' . PHP_EOL, Console::FG_RED);

			return;
		}

		$this->stdout('Завершено' . PHP_EOL, Console::FG_GREEN);
	}

	/**
	 * Откат таблиц ACL-структуры.
	 */
	public function actionRemoveAclTables() {
		$this->stdout('Удаляем структуру таблиц ACL' . PHP_EOL);

		if ($this->getMigration($this->rbacMigrationPath, $this->rbacMigrationClass)->down() !== false) {
			$this->stdout('Успешно' . PHP_EOL, Console::FG_GREEN);
		}
		else {
			$this->stdout('Ошибка' . PHP_EOL, Console::FG_RED);

			return;
		}

		$this->stdout('Завершено' . PHP_EOL, Console::FG_GREEN);
	}

	/**
	 * Получить объект миграции
	 *
	 * @param string $path  Путь к файлу миграции
	 * @param string $class Имя класса
	 *
	 * @return Migration объект миграции
	 */
	protected function getMigration($path, $class) {
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