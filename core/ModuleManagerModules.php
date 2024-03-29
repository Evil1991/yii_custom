<?php
namespace yiiCustom\core;

use yiiCustom\base\Module;

/**
 * Вспомогательный компонент-провайдер для работы с включёнными модулями.
 *
 * Класс не должен нигде использоваться, кроме как в ModuleManager'е.
 *
 * Здесь можно указать модули, чтобы обращаться к ним, используя php-doc (см. readme).
 */
class ModuleManagerModules {
	/** @var Module[] Список включённых модулей. */
	private $_modules;

	/**
	 * @param Module[] $modules Список включённых модулей
	 */
	public function __construct(array $modules) {
		$this->_modules = $modules;
	}

	/**
	 * Магический метод для получения модуля.
	 *
	 * @param string $name Название интерфейса без namespace'а (например, для модуля \common\interfaces\image\Image надо передать image)
	 * @return Module|null Объект модуля или NULL, если такого модуля нет
	 */
	public function __get($name) {
		if (false === array_key_exists($name, $this->_modules)) {
			return null;
		}
		return $this->_modules[$name];
	}

	/**
	 * Магический метод для проверки, существует ли свойство или нет.
	 *
	 * @param string $name Название интерфейса без namespace'а (например, для модуля \common\interfaces\image\Image надо передать image)
	 * @return bool
	 */
	public function __isset($name) {
		return array_key_exists($name, $this->_modules);
	}

	/**
	 * Получение инициализированных модулей.
	 *
	 * @return Module[]
	 */
	public function getModules() {
		return $this->_modules;
	}
}