<?php

namespace yiiCustom\base;

use vendor\yii_custom\yiiCustom\models\RefModuleSetting;
use Yii;
use yii\base\InvalidConfigException;
use yii\caching\TagDependency;

/**
 * Расширение модуля.
 */
class Module extends \yii\base\Module {

	/** @var RefModuleSetting[] Настройки модуля */
	protected $moduleSettings;

	/**
	 * @inheritdoc
	 */
	public function init() {
		parent::init();

		$this->controllerNamespace = 'common' . '\\modules\\' . $this->id . '\\' . Yii::$app->configManager->getEntryPoint() . '\\controllers';

		$cacheKey = __CLASS__ . 'moduleSettins-' . $this->id;
		$moduleSettings = Yii::$app->cache->get($cacheKey);

		if ($moduleSettings === false) {
			$moduleSettings = RefModuleSetting::find()
				->where([
					RefModuleSetting::ATTR_MODULE_NAME => $this->id,
				])
				->indexBy(RefModuleSetting::ATTR_PARAM_NAME)
				->all();

			Yii::$app->cache->set($cacheKey, $moduleSettings, 3600 * 2, new TagDependency([
				'tags' => RefModuleSetting::class,
			]));
		}

		$this->moduleSettings = $moduleSettings;
	}

	/**
	 * Настройки модуля.
	 * Указываются в виде массива:
	 * 'setting_name' => [
	 *  'title' => (название по-русски)
	 *  'type_cast' => (тип)
	 * ],
	 * ...
	 *
	 * @return array
	 */
	public function settings() {
		return [];
	}

	/**
	 * @inheritdoc
	 */
	public function __get($name) {
		//сначала проверяем, есть ли соответствующая настройка у модуля
		$settingsOptions = $this->settings();

		if (array_key_exists($name, $settingsOptions) === true) {
			if (array_key_exists($name, $this->modules) === false) {
				return null;
			}
			else {
				return $this->moduleSettings[$name]->getValue();
			}
		}

		return parent::__get($name);
	}

	/**
	 * @inheritdoc
	 */
	public function __set($name, $value) {
		//сначала проверяем, есть ли соответствующая настройка у модуля
		$settingsOptions = $this->settings();

		if (array_key_exists($name, $settingsOptions) === true) {
			if (array_key_exists($name, $this->moduleSettings) === true) {
				$setting = $this->moduleSettings[$name];
			}
			else {
				$setting = new RefModuleSetting();

				$setting->module_name = $this->id;
				$setting->param_name  = $name;
				$setting->type_cast   = $settingsOptions[$name]['type_cast'];
			}

			$setting->setValue($value);
		}
		else {
			parent::__set($name, $value);
		}
	}

	/**
	 * Сохранение настроек.
	 *
	 * @throws InvalidConfigException
	 */
	public function saveSettings() {
		foreach ($this->moduleSettings as $moduleSetting) {
			if ($moduleSetting->save() === false) {
				throw new InvalidConfigException('Некорректное значение параметра настройки: ' . $moduleSetting->param_name . ', ошибки: ' . $moduleSetting->errors);
			}
		}

		TagDependency::invalidate(Yii::$app->cache, [RefModuleSetting::class]);
	}

	/**
	 * Получение настроек модуля.
	 *
	 * @return RefModuleSetting[]
	 */
	public function getSettings() {
		$settingsOptions = $this->settings();

		$settingModels = [];/** @var RefModuleSetting[] $settingModels */

		//проверяем, по каким настройкам есть записи в БД. Если их нет - то создаём пустые модели
		foreach ($settingsOptions as $name => $options) {
			if (array_key_exists($name, $this->moduleSettings) === true) {
				$settingModels[$name] = $this->moduleSettings[$name];
			}
			else {
				$setting = new RefModuleSetting();

				$setting->module_name = $this->id;
				$setting->param_name  = $name;
				$setting->type_cast   = $settingsOptions[$name]['type_cast'];

				$settingModels[$name] = $setting;
			}

			$settingModels[$name]->title       = $settingsOptions[$name]['title'];
		}

		return $settingModels;
	}
}