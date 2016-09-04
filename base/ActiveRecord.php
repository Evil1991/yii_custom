<?php

namespace YiiCustom\base;

use Yii;
use yii\caching\TagDependency;
use yii\db\Exception;

/**
 * Переопределённый ActiveRecord с добавлением нужных методов
 */
class ActiveRecord extends \yii\db\ActiveRecord {

	/**
	 * Получение декодированного значения json-поля
	 * @param $name
	 * @return \string[]
	 * @throws Exception
	 */
	public function getJsonFieldValue($name) {
		if (isset($this->$name)) {
			$data = @json_decode($this->$name, true);

			if (is_array($data)) {
				/** @var $data string[] */
				return $data;
			}
		}
		else {
			throw new Exception('Указанное поле не существует: ' . $name);
		}

		return [];
	}

	/**
	 * Установить значение для json-поля
	 * @param $name
	 * @param $value
	 * @throws Exception
	 */
	public function setJsonFieldValue($name, $value) {
		$this->$name = @json_encode($value, JSON_UNESCAPED_UNICODE);
	}

	/**
	 * @inheritdoc
	 */
	public function afterSave($insert, $changedAttributes) {
		parent::afterSave($insert, $changedAttributes);

		TagDependency::invalidate(Yii::$app->cache, __CLASS__);
	}

}