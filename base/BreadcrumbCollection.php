<?php

namespace YiiCustom\base;

use Countable;
use Iterator;

class BreadcrumbCollection implements Iterator, Countable {

	/**
	 * Крошки
	 *
	 * @var BreadcrumbItem[]
	 */
	protected $breadcrumbs = [];

	/**
	 * Итератор
	 *
	 * @var int
	 */
	protected $i = 0;

	/**
	 * Добавить крошку
	 *
	 * @param string $title Название
	 * @param array  $url URL ссылки (опционально)
	 */
	public function addBreadcrumb($title, $url = null) {
		$crumb = new BreadcrumbItem();

		$crumb->url   = $url;
		$crumb->title = $title;

		$this->breadcrumbs[] = $crumb;
	}

	/**
	 * @inheritdoc
	 */
	public function current() {
		return $this->breadcrumbs[$this->i];
	}

	/**
	 * @inheritdoc
	 */
	public function next() {
		$this->i++;
	}

	/**
	 * @inheritdoc
	 */
	public function key() {
		return $this->i;
	}

	/**
	 * @inheritdoc
	 */
	public function valid() {
		return isset($this->breadcrumbs[$this->i]);
	}

	/**
	 * @inheritdoc
	 */
	public function rewind() {
		$this->i = 0;
	}

	/**
	 * @inheritdoc
	 */
	public function count() {
		return count($this->breadcrumbs);
	}

	/**
	 * Получить последний элемент
	 * @return BreadcrumbItem|false
	 */
	public function getLast() {
		if (!empty($this->breadcrumbs)) {
			return $this->breadcrumbs[count($this->breadcrumbs) - 1];
		}

		return false;
	}
}