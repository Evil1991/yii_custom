<?php

namespace YiiCustom\base;

use YiiCustom\core\WebController;
use Yii;
use yii\web\UnauthorizedHttpException;

/**
 * Расширение веб-контроллера для бэкэнда.
 */
class BackendController extends WebController {

	/** @var string Основная роль для доступа в админку. Если не задано, то доступ не будет проверяться на данном уровне */
	protected $commonRoleAccess;

	/** @var string URL для редиректа в случае отсутствия доступа. Если не задано, то будет выброшено исключение */
	protected $noAccessRedirectUrl;

	/** @var bool Требуется авторизация для страниц контроллера */
	protected $needAuthorise = true;

	/**
	 * @inheritdoc
	 */
	public function beforeAction($action) {
		if (parent::beforeAction($action) === false) {
			return false;
		}

		if ($this->needAuthorise) {

			if ($this->commonRoleAccess === null) {
				return true;
			}

			//если пользователь гость, то редиректим его на страницу авторизации
			if (Yii::$app->user->isGuest) {
				$this->redirect(Yii::$app->moduleManager->modules->user->getAuthUrl());

				return false;
			}

			//если он уже авторизован, но не имеет права на доступ в админку, то отправляем на соответствующую страницу
			if (Yii::$app->user->can($this->commonRoleAccess) === false) {
				if ($this->noAccessRedirectUrl !== null) {
					$this->redirect($this->noAccessRedirectUrl);
				}
				else {
					throw new UnauthorizedHttpException('You have not permissions for this page');
				}

				return false;
			}
		}

		return true;
	}

}