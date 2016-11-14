<?php
use yii\db\Schema;

/**
 * Миграция создания таблиц настроек модулей.
 */
class module_settings_tables extends \yii\db\Migration {

	/**
	 * @inheritdoc
	 */
	public function safeUp() {
		$this->createTable('ref_module_setting', [
			'id'          => $this->primaryKey() . ' COMMENT "Идентификатор настройки"',
			'module_name' => Schema::TYPE_STRING . '(100) NOT NULL COMMENT "Название модуля, с которым связана настройка"',
			'param_name'  => Schema::TYPE_STRING . '(100) NOT NULL COMMENT "Название параметра настройки"',
			'param_value' => Schema::TYPE_TEXT . ' NOT NULL COMMENT "Значение настройки"',
			'type_cast'   => 'enum("int","float","string","array","bool","other") COMMENT "Тип, к которому будет приведено значение"',
			'KEY (module_name)',
		], 'COMMENT "Справочник настроек модулей"');
	}

	/**
	 * @inheritdoc
	 */
	public function safeDown() {
		$this->dropTable('ref_module_setting');
	}
}