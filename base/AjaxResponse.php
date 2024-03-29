<?php

namespace yiiCustom\base;

/**
 * Класс-обёртка ответа контроллера на ajax-запрос
 */
class AjaxResponse {

	/** @var bool Общий результат */
	public $result = false;

	/** @var array Данные */
	public $data = [];

	/** @var string Html-контент ответа */
	public $html;

	/** @var string Сообщение пользователю */
	public $message;

	/** @var string[] Ошибки */
	public $errors = [];

}