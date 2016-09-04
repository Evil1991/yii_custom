<?php

namespace yiiCustom\base;

use Yii;

class Module extends \yii\base\Module {

	public function init() {
		parent::init();
		$this->controllerNamespace = 'common' . '\\modules\\' . $this->id . '\\' . Yii::$app->configManager->getEntryPoint() . '\\controllers';
	}
}