<?php

namespace yiiCustom\base;

/**
 * Класс элемента-хлебной крошки
 */
class BreadcrumbItem {

	/**
	 * Параметр url. @see Url::to
	 * @var array|string $url
	 */
	public $url;

	/**
	 * Название ссылки
	 * @var string
	 */
	public $title;

}