<?php

namespace YiiCustom\core;

use Yii;
use yii\helpers\FileHelper;

/**
 * Расширение темы под проект.
 *
 * @package YiiCustom\core
 */
class Theme extends \yii\base\Theme {

	/** @var string Название темы. */
	public $name = 'default';
	const ATTR_NAME = 'name';

	/**
	 * @inheritdoc
	 */
	public function init() {
		parent::init();

		$this->setBasePath(FileHelper::normalizePath(Yii::$app->configManager->getRepositoryRootPath() . '/'. Yii::$app->configManager->getEntryPoint() . '/themes/' . $this->name));
	}
}