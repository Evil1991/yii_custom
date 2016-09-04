<?php

class Yii extends \yii\BaseYii {
	/** @var yii\console\Application|yii\web\Application|Application The application instance */
	public static $app;
}

/**
 * @property-read \YiiCustom\core\ConfigManager $configManager
 * @property-read \YiiCustom\core\View          $view
 * @property-read \YiiCustom\core\ModuleManager $moduleManager
 * @property-read \YiiCustom\core\Environment   $env
 */
class Application {}
