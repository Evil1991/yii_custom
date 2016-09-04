<?php

class Yii extends \yii\BaseYii {
	/** @var yii\console\Application|yii\web\Application|Application The application instance */
	public static $app;
}

/**
 * @property-read \yiiCustom\core\ConfigManager $configManager
 * @property-read \yiiCustom\core\View          $view
 * @property-read \yiiCustom\core\ModuleManager $moduleManager
 * @property-read \yiiCustom\core\Environment   $env
 */
class Application {}
