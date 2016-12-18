<?php

namespace yiiCustom\base;

use yiiCustom\core\WebController;
use Yii;
use yii\web\UnauthorizedHttpException;

/**
 * Расширение веб-контроллера для бэкэнда.
 */
class BackendController extends WebController {

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

			//если пользователь гость, то редиректим его на страницу авторизации
			if (Yii::$app->user->isGuest) {
				Yii::$app->user->setReturnUrl(Yii::$app->request->getUrl());

				//todo убрать здесь и ниже зависимость от модуля auth, реализуемого в приложении
				$this->redirect(Yii::$app->moduleManager->modules->user->getAuthUrl());

				return false;
			}

			if (Yii::$app->moduleManager->modules->user->commonRoleAccess === null) {
				return true;
			}

			//если он уже авторизован, но не имеет права на доступ в админку, то отправляем на соответствующую страницу
			if (Yii::$app->user->can(Yii::$app->moduleManager->modules->user->commonRoleAccess) === false) {
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