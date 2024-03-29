<?php

namespace yiiCustom\backend\controllers;

use Yii;
use yii\base\InvalidConfigException;
use yiiCustom\base\BackendController;
use yiiCustom\widgets\Alert;

/**
 * Контроллер настроек модуля. Базовый класс контроллера
 */
class SettingsController extends BackendController {

	const ACTION_INDEX = 'index';

	/** Область видимости параметров в форме */
	const PARAM_SCOPE = 'params';

	/** @var string Название модуля, настройки которого будут обрабатываться в наследнике контроллера */
	public $moduleName;

	/**
	 * Главная страница настроек.
	 *
	 * @return string
	 *
	 * @throws InvalidConfigException
	 */
	public function actionIndex() {
		$moduleName = $this->moduleName;

		if (Yii::$app->moduleManager->modules->{$moduleName} === null) {
			throw new InvalidConfigException('Не удалось получить указанный модуль: ' . $moduleName);
		}

		$settings = Yii::$app->moduleManager->modules->{$moduleName}->getSettings();

		$errors = [];

		if (Yii::$app->request->isPost === true) {
			$isSuccess = false;
			foreach (Yii::$app->request->post()[static::PARAM_SCOPE] as $param => $value) {
				if (array_key_exists($param, $settings) === true) {
					$setting = $settings[$param];

					if ($setting->type_cast === $setting::TYPE_ARRAY) {
						$value = explode("\n", $value);

						$value = array_map(function($item) {
							return trim($item);
						}, $value);

						$value = array_filter($value);
					}

					Yii::$app->moduleManager->modules->{$moduleName}->{$param} = $value;
				}
			}

			try {
				Yii::$app->moduleManager->modules->{$moduleName}->saveSettings();
				$isSuccess = true;
			}
			catch (InvalidConfigException $e) {
				Yii::$app->session->addFlash(Alert::TYPE_ERROR, 'Ошибка при сохранении настроек: ' . $e->getMessage());
			}

			if ($isSuccess === true) {
				Yii::$app->session->addFlash(Alert::TYPE_SUCCESS, 'Настройки успешно сохранены');
			}
		}

		return $this->render($this->action->id, [
			'settings' => $settings,
			'errors'   => $errors,
		]);
	}

	/**
	 * @inheritdoc
	 */
	public function getViewPath() {
		return '@vendor/Evil1991/yii_custom/views/backend/settings';
	}
}