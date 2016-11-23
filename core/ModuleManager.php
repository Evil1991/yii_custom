<?php
namespace yiiCustom\core;

use yiiCustom\base\Module;
use Yii;
use yii\base\BootstrapInterface;
use yii\base\Component;
use yii\helpers\StringHelper;

/**
 * Компонент для управления и проверки модулей.
 *
 * @property-read ModuleManagerModules $modules Провайдер включённых модулей
 */
class ModuleManager extends Component implements BootstrapInterface {

	/** @var Module[] Массив инициализированных модулей. */
	protected $_availableModules;

	/** @var ModuleManagerModules Провайдер включённых модулей. */
	private $_modules;

	/**
	 * @inheritdoc
	 */
	public function bootstrap($app) {
		$this->_initModules();
//		$this->_initEnabledModules();
//		$this->_initDependants();

		// -- Выполняем действие после инициализации всех модулей
//		foreach ($this->getAvailableModules() as $module) {
//			$module->afterInit();
//		}
		// -- -- -- --

		// -- Проверяем, соблюдены ли зависимости, но только на frontend'е
//		if (Yii::$app->configManager->isFrontendEntryPoint()) {
//			$errors = [];
//			foreach ($this->_enabledModules as $module) {
//				$result = $this->_isDependenciesResolved($module);
//				if (true !== $result) {
//					$errors[] = '[' . $module->id . '] ' . $result;
//				}
//			}
//
//			if (0 !== count($errors)) {
//				throw new InvalidConfigException('Ошибка инициализации модулей.' . PHP_EOL . implode(PHP_EOL, $errors));
//			}
//		}
		// -- -- -- --
	}

	/**
	 * Инициализация массива модулей.
	 */
	private function _initModules() {
		$modules = [];

		// -- Отсеиваем сторонние модули - всё равно их настроить нельзя
		foreach (array_keys(Yii::$app->getModules()) as $moduleName) {
			$module = Yii::$app->getModule($moduleName);
			if ($module instanceof Module) {
				$modules[$moduleName] = $module;
			}
		}
		// -- -- -- --

		$this->_availableModules = $modules;
	}

	/**
	 * Получение провайдера включённых модулей.
	 * Вместо прямого вызова метода необходимо обращаться к свойству $this->modules.
	 *
	 * @return ModuleManagerModules
	 */
	protected function getModules() {
		if (null === $this->_modules) {
			$modules = [];
			foreach ($this->_availableModules as $interface => $module) {
				// -- Извлекаем из названия интерфейса только его оконцовку, преобразуя в camelCase
				$key = StringHelper::basename($interface);
				$key = mb_strtolower(mb_substr($key, 0, 1)) . mb_substr($key, 1);
				// -- -- -- --

				$modules[$key] = $module;
			}

			$this->_modules = new ModuleManagerModules($modules);
		}

		return $this->_modules;
	}

	/**
	 * Получение объекта модуля по его имени.
	 * Необходимо, например, чтобы работать с отключённым модулем (получать информацию о нём и управлять им).
	 *
	 * @param string $moduleName
	 * @return Module|null
	 */
	public function getModuleByName($moduleName) {
		if (array_key_exists($moduleName, $this->_availableModules)) {
			return $this->_availableModules[$moduleName];
		}
		return null;
	}
}