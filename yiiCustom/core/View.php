<?php

namespace yiiCustom\core;

use yiiCustom\base\BreadcrumbCollection;
use Yii;

/**
 * Расширение класса View под проект.
 */
class View extends \yii\web\View {

	/**
	 * @var BreadcrumbCollection
	 */
	public $breadcrumbs;

	/** @var bool Использовать ли свой заголовок во view */
	public $useCustomTitle = false;

	/**
	 * @inheritdoc
	 */
	public function init() {
		parent::init();

		$this->breadcrumbs = new BreadcrumbCollection();
		$this->breadcrumbs->addBreadcrumb('Главная', ['/']);
	}

	/**
	 * @inheritdoc
	 */
	public function renderFile($viewFile, $params = [], $context = null) {
		$params['controller'] = Yii::$app->controller;// Пробрасываем контроллер во вьюшку как параметр, а не как свойство (чтобы можно было задать phpdoc)
		return parent::renderFile($viewFile, $params, $context);
	}

}