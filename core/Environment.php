<?php
namespace yiiCustom\core;

use Yii;
use yii\base\Component;

/**
 * Компонент для получения данных о текущем окружении.
 */
class Environment extends Component {

	/**
	 * Получение основного домена сайта (2го уровня).
	 *
	 * @return string
	 */
	public function getCurrentDomain() {
		return Yii::$app->params['baseDomain'];
	}

	/**
	 * Получение домена для конкретной точки входа
	 *
	 * @param string $entryPoint Название точки входа
	 *
	 * @return string
	 */
	public function getDomainForEntryPoint($entryPoint) {
		$currentDomain = $this->getCurrentDomain();

		if ($entryPoint === ConfigCollector::ENTRY_POINT_FRONTEND) {
			return $currentDomain;
		}

		return $entryPoint . '.' . $currentDomain;
	}
}
