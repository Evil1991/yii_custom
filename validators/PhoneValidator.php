<?php
namespace yiiCustom\validators;

use yii\validators\Validator;

/**
 * Валидатор для проверки корректности ввода номера телефона.
 * Позволяет отдельно работать только с номером мобильного телефона.
 * Если указано, может исправлять формат номера телефона.
 */
class PhoneValidator extends Validator {
	/** @var bool Разрешить ли атрибуту иметь пустое значение. */
	public $allowEmpty = true;

	/** @var bool Необходимо ли скорректировать значение атрибута. */
	public $fixFormat = true;

	/** @var bool Необходимо ли пропускать только номера мобильных телефонов или же можно все. */
	public $mobileOnly = false;

	/**
	 * @inheritdoc
	 */
	public function validateAttribute($model, $attribute) {
		$value = $model->$attribute;

		// -- Если значение пустое, не проверяем его (если такое разрешено)
		if ($this->isEmpty($value)) {
			if (true !== $this->allowEmpty) {
				$this->addError($model, $attribute, \Yii::t('yii', '{attribute} cannot be blank.'));
			}
			return;
		}
		// -- -- -- --

		// -- Проверяем введённый номер телефона
		$result = self::isPhoneValid($value, $this->mobileOnly);
		if (true != $result) {
			$message = $this->message;

			if (null === $message) {
				$message = 'Поле «{attribute}» заполнено неверно.';
			}

			$this->addError($model, $attribute, $message);

			return;
		}
		// -- -- -- --

		// -- Если данные введены верно, проверяем, может быть атрибут следует скорректировать
		if (true === $this->fixFormat) {
			$value = preg_replace('/[^\d]+/', '', $value);
			preg_match('/^(8|7|)(\d)(\d{9})$/usi', $value, $matches);// Разбиваем телефон на составные части

			$value = $matches[2] . $matches[3];// Не добавляем сюда $matches[1], так как нам надо номер телефона начать с +7

			// -- Форматируем телефон в зависимости от того, мобильный он или нет
			if (9 == $matches[2]) {// В номерах мобильных телефонов вторая цифра всегда 9
				$value = preg_replace('/(\d{3})(\d{3})(\d{4})/', '+7 \1 \2 \3', $value);
			} else {
				$value = preg_replace('/(\d{3})(\d{3})(\d{2})(\d{2})/', '+7 (\1) \2-\3-\4', $value);
			}
			// -- -- -- --

			$model->$attribute = $value;
		}
		// -- -- -- --
	}

	/**
	 * Проверка, является ли введённый телефон правильным.
	 * Метод необходим, чтобы была возможность проверять значение вне модели.
	 *
	 * @param string $phone Номер телефона
	 * @param bool $mobileOnly Необходимо ли пропускать только номера мобильных телефонов или же можно все номера.
	 * @return bool
	 */
	public static function isPhoneValid($phone, $mobileOnly = false) {
		$phoneStripped = preg_replace('/[^0-9\.\-\s\(\)\+\_\,\*]+/usi', '', $phone);// Удаляем всё, что не используется в номере телефона

		// -- Если в результате удаления оказалось, что в строке ещё что-то было
		if ($phoneStripped != $phone) {
			return false;
		}
		// -- -- -- --

		// -- Удаляем всё, кроме цифр
		$phone = $phoneStripped;
		$phone = preg_replace('/[^0-9]+/', '', $phone);
		$phone = trim($phone);// Удаляем лишние отступы в начале и конце
		// -- -- -- --

		// -- Если значение содержит неверные данные
		if (1 !== preg_match('/^(8|7|)(\d)(\d{9})$/usi', $phone, $matches)) {
			return false;
		}
		// -- -- -- --

		// -- Если разрешено вводить только номера мобильных телефонов (В номерах мобильных телефонов вторая цифра всегда 9)
		if (true === $mobileOnly) {
			if (9 != $matches[2]) {
				return false;
			}
		}
		// -- -- -- --

		return true;
	}
}