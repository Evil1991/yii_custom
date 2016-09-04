<?php

namespace yiiCustom\base;

use yii\log\FileTarget;

/**
 * Упрощённый тип логгирования в файл (без лишних переменных окружения)
 */
class FileLogTargetSimple extends FileTarget {

	public $logVars = [];

}