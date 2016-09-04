<?php

namespace yiiCustom;

use yii\log\FileTarget\base;

/**
 * Упрощённый тип логгирования в файл (без лишних переменных окружения)
 */
class FileLogTargetSimple extends FileTarget {

	public $logVars = [];

}