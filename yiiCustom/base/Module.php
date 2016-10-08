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
	protected $moduleSettings = [];

	/** @var array Конфиг настроек */
	protected $settingsConfig;

	/**
	 * @inheritdoc
	 */
	public function init() {
		parent::init();

		$this->controllerNamespace = 'common' . '\\modules\\' . $this->id . '\\' . Yii::$app->configManager->getEntryPoint() . '\\controllers';

		$this->settingsConfig = $this->settings();

		//если у модуля есть настройки, то инициализируем их
		if (count($this->settingsConfig) > 0) {
			$this->initSettings();
		}
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
			return $this->moduleSettings[$name]->getValue();
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
			$this->moduleSettings[$name]->setValue($value);
		}
		else {
			parent::__set($name, $value);
		}
	}

	/**
	 * @inheritdoc
	 */
	public function __isset($name) {
		if (array_key_exists($name, $this->settingsConfig) === true) {
			return true;
		}

		return parent::__isset($name);
	}

	/**
	 * Сохранение настроек.
	 *
	 * @throws InvalidConfigException
	 */
	public function saveSettings() {
		foreach ($this->moduleSettings as $moduleSetting) {
			if ($moduleSetting->save() === false) {
				throw new InvalidConfigException('Некорректное значение параметра настройки: ' . $moduleSetting->param_name . ', ошибки: ' . print_r($moduleSetting->errors, true));
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
		return $this->moduleSettings;
	}

	/**
	 * Инициализация настроек модуля.
	 */
	protected function initSettings() {
		$cacheKey       = __CLASS__ . 'moduleSettins-' . $this->id . '.v2';
		$loadedModuleSettings = Yii::$app->cache->get($cacheKey);/** @var RefModuleSetting[] $moduleSettings */

		if ($loadedModuleSettings === false) {
			$moduleSettings = RefModuleSetting::find()
				->where([
					RefModuleSetting::ATTR_MODULE_NAME => $this->id,
				])
				->indexBy(RefModuleSetting::ATTR_PARAM_NAME)
				->all();

			Yii::$app->cache->set($cacheKey, $loadedModuleSettings, 3600 * 2, new TagDependency([
				'tags' => RefModuleSetting::class,
			]));
		}

		$moduleSettings = [];
		//перебираем настройки из конфига. Если для какой-либо нет записи, то создаём пустую
		foreach ($this->settingsConfig as $name => $settingConfig) {
			if (array_key_exists($name, $loadedModuleSettings) === true) {
				$moduleSettings[$name] = $loadedModuleSettings[$name];
			}
			else {
				$setting = new RefModuleSetting();

				$setting->module_name = $this->id;
				$setting->param_name  = $name;
				$setting->type_cast   = $settingConfig['type_cast'];

				$moduleSettings[$name] = $setting;
			}

			$moduleSettings[$name]->title = $settingConfig['title'];
		}

		$this->moduleSettings = $moduleSettings;
	}
}