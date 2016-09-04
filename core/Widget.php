<?php

namespace YiiCustom\core;

use ReflectionClass;
use Yii;

/**
 * Расширение класса виджета под проект.
 */
class Widget extends \yii\base\Widget {

	/**
	 * @inheritdoc
	 */
	public function getViewPath() {
		//получаем имя модуля, к которому принадлежит виджет
		$class = new ReflectionClass($this);
		if (preg_match('/modules\\\([^\\\]+)/', $class->getName(), $res)) {
			$moduleId = $res[1];
		}
		else {
			return parent::getViewPath();
		}

		return $this->view->theme->getBasePath() . '/' . $moduleId . '/widgets';
	}

}