<?php

namespace yiiCustom\core;

use Yii;
use yii\helpers\StringHelper;
use yii\web\Controller;
use yii\web\Request;

/**
 * Расширение класса контроллера под проект.
 *
 * @property-read View $view
 */
class WebController extends Controller {

	/** @var string Общее название страниц контроллера */
	protected $title;

	/** @var string Основной экшн контроллера */
	protected $mainAction = 'index';

	/** @var bool Использовать ли дефолтные хлебные крошки */
	protected $useDefaultBreadcrumbs = true;

	/**
	 * @inheritdoc
	 */
	public function beforeAction($action) {
		if (parent::beforeAction($action) === false) {
			return false;
		}

		//если у контроллера указано название, то пихаем его либо в хлебные крошки, либо в название страницы
		if ($this->title) {
			if ($this->useDefaultBreadcrumbs && $this->mainAction) {
				$this->view->breadcrumbs->addBreadcrumb($this->title, static::getActionUrl($this->mainAction));
			}
			else {
				$this->view->title = $this->title;
			}
		}


		return true;
	}

	/**
	 * @inheritdoc
	 */
	public function getViewPath() {
		return Yii::$app->view->theme->basePath . '/' . $this->module->id . '/' . $this->id;
	}

	/**
	 * Получение пространства имён (namespace) для класса.
	 * Лучше использовать этот метод вместо задания namespace'а обычным текстом.
	 * Таким образом, будет более простая разработка, автокомплит, поиск зависимостей и т.д.
	 *
	 * @return string
	 */
	public static function getNamespace() {
		return StringHelper::dirname(static::class);
	}

	/**
	 * Получение ссылки на указанное действие исходя из контроллера.
	 *
	 * @param string    $actionName   Название действия
	 * @param array     $actionParams Дополнительные параметры
	 * @param bool      $withDomain   Включить в ссылку домен и протокол
	 * @param bool|null $secure       Https или http, если null, то будет взят текущий протокол
	 *
	 * @return string
	 */
	public static function getActionUrl($actionName, array $actionParams = [], $withDomain = false, $secure = null) {
		// -- Определяем, относится ли контроллер к текущей точке входа или нет
		$prefix = null;
		$domain = null;

		$configManager = Yii::$app->configManager;

		$controllerEntryPoint = preg_replace('/^.*\\\\(.*?)\\\\controllers.*$/', '\1', static::getNamespace());
		if (($withDomain === true) || ($controllerEntryPoint !== $configManager->getEntryPoint())) {
			if ($controllerEntryPoint !== $configManager->getEntryPoint()) {
				$prefix = $controllerEntryPoint . '/';
			}

			if ($secure === null) {
				if (Yii::$app->request instanceof Request) {
					$secure = Yii::$app->request->isSecureConnection;
				}
				else {
					$secure = false;
				}
			}

			$protocol = $secure ? 'https://' : 'http://';

			$domain = $protocol . Yii::$app->env->getDomainForEntryPoint($controllerEntryPoint);
		}

		// -- -- -- --

		$moduleName = preg_replace('/^.*\\\\modules\\\\(.*?)\\\\.*$/', '\1', static::class);

		$controllerName = preg_replace('/Controller$/', '', StringHelper::basename(static::class));
		//Преобразуем название контроллера к формату url (aaa-bbb-ccc-..)
		$controllerName = mb_strtolower(preg_replace('~(?!\b)([A-Z])~', '-\\1', $controllerName));

		$actionParams[0] = implode('/', [
			$moduleName,
			$controllerName,
			$actionName,
		]);
		$actionParams[0] = '/' . $prefix . $actionParams[0];

		$url = Yii::$app->urlManager->createUrl($actionParams);
		if (null !== $domain) {
			$url = $domain . $url;
		}

		return $url;
	}
}