<?php

namespace yiiCustom\console\controllers;

use yiiCustom\console\components\MigrationProvider;
use Yii;
use yii\console\Controller;
use yii\helpers\Console;

/**
 * Инициализация настроек модуля.
 */
class ModuleSettingsInitController extends Controller {

	const MIGRATIONS = [
		'@vendor/yii_custom/yiiCustom/migrations' => 'module_settings_tables',
	];

	/**
	 * Инициализация.
	 */
	public function actionInit() {
		foreach (static::MIGRATIONS as $path => $migrationName) {
			$this->stdout('Выполняем миграцию: ' . $migrationName . PHP_EOL);
			MigrationProvider::getMigration($path, $migrationName)->up();
		}

		$this->stdout('Инициализация успешно выполнена' . PHP_EOL, Console::FG_GREEN);
	}

	/**
	 * Удаление.
	 */
	public function actionRemove() {
		foreach (static::MIGRATIONS as $path => $migrationName) {
			$this->stdout('Откатываем миграцию: ' . $migrationName . PHP_EOL);
			MigrationProvider::getMigration($path, $migrationName)->down();
		}

		$this->stdout('Удаление успешно выполнено' . PHP_EOL, Console::FG_GREEN);
	}
}