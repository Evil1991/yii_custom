<?php

namespace vendor\yii_custom\yiiCustom\models;

use yii\validators\RequiredValidator;
use yiiCustom\base\ActiveRecord;

/**
 * Справочник настроек модулей.
 *
 * @property int    $id          Идентификатор настройки
 * @property string $module_name Название модуля, с которым связана настройка
 * @property string $param_name  Название параметра настройки
 * @property string $param_value Значение настройки
 * @property string $type_cast   Тип, к которому будет приведено значение
 */
class RefModuleSetting extends ActiveRecord {

	const ATTR_ID          = 'id';
	const ATTR_MODULE_NAME = 'module_name';
	const ATTR_PARAM_NAME  = 'param_name';
	const ATTR_PARAM_VALUE = 'param_value';
	const ATTR_TYPE_CAST   = 'type_cast';

	const TYPE_INT    = 'int';
	const TYPE_BOOL    = 'bool';
	const TYPE_FLOAT  = 'float';
	const TYPE_STRING = 'string';
	const TYPE_ARRAY  = 'array';
	const TYPE_OTHER  = 'other';

	const TYPE_CAST_LIST = [
		self::TYPE_INT,
		self::TYPE_BOOL,
		self::TYPE_FLOAT,
		self::TYPE_STRING,
		self::TYPE_ARRAY,
		self::TYPE_OTHER,
	];

	/** @var string Название настройки (для runtime) */
	public $title;

	/**
	 * @inheritdoc
	 */
	public function rules() {
		return [
			[static::ATTR_MODULE_NAME, RequiredValidator::class],
			[static::ATTR_PARAM_NAME, RequiredValidator::class],
			[static::ATTR_TYPE_CAST, RequiredValidator::class],
			[static::ATTR_TYPE_CAST, function() {
				return in_array($this->type_cast, static::TYPE_CAST_LIST);
			}],
		];
	}

	/**
	 * Получение значения параметра.
	 *
	 * @return mixed|null Значение, соответствующее типу, либо null, если данные отсутствуют
	 */
	public function getValue() {
		switch ($this->type_cast) {
			case static::TYPE_INT:
				return (int) $this->param_value;

			case static::TYPE_BOOL:
				return (bool) $this->param_value;

			case static::TYPE_FLOAT:
				return (float) $this->param_value;

			case static::TYPE_STRING:
				return (string) $this->param_value;

			case static::TYPE_ARRAY:
				$value = @json_decode($this->param_value, true);

				if ($value !== false) {
					return $value;
				}

				break;

			case static::TYPE_OTHER:
				return $this->param_value;
		}

		return null;
	}

	/**
	 * Установка значения параметра.
	 * Перед установкой должно быть назначено поле типа значения параметра.
	 *
	 * @see param_value
	 *
	 * @param mixed $value Значение
	 */
	public function setValue($value) {
		switch ($this->type_cast) {
			case static::TYPE_ARRAY:
				$this->param_value = @json_encode($value);

				break;

			case static::TYPE_INT:
				$this->param_value = (int) $value;

				break;

			case static::TYPE_FLOAT:
				$this->param_value = (float) $value;

				break;

			default:
				$this->param_value = (string)$value;

				break;
		}
	}

}