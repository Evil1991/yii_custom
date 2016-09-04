<?php

namespace YiiCustom\helpers;

/**
 * Расширение хелпера строк.
 */
class StringHelper extends \yii\helpers\StringHelper {

	/**
	 * Форматирование размера в читаемом виде.
	 *
	 * @param int $size Размер
	 *
	 * @return string
	 */
	public static function formatSize($size) {
		return number_format($size, 0, '', ' ');
	}

	/**
	 * Вывод количества чего-то с окончанием согласно морфологии русского языка.
	 *
	 * countPostfix($AutoCnt, array ('товар', 'товара', 'товаров'), 'показывать 0 словом нет?', 'выводить перед словом число?')
	 *
	 * @param int           $count          Число
	 * @param array         $cases          Варианты слов с нужными окончаниями, например: array('товар', 'товара', 'товаров')
	 * @param string|false  $zeroAsWord     Показывать 0 каким-либо словом (например, "нет") или показывать как число 0 (false)
	 * @param bool          $showCount      Выводить перед словом число/количество (true) или нет (false)
	 *
	 * @return string
	 */
	public static function countPostfix($count, $cases, $zeroAsWord = 'нет', $showCount = true) {
		$countString = preg_replace('/[^\d]+/', '', $count);// Удаляем всё, кроме чисел
		$caseIndex = self::getCountPostfixForm((int)$countString);

		$result = $cases[$caseIndex];

		// -- Если необходимо вывести перед словом количество
		if (false !== $showCount) {
			// -- Если количество равно нулю, и надо отобразить не число а слово (например, "нет")
			if (0 == $count && false !== $zeroAsWord) {
				$count = $zeroAsWord;
			}
			// -- -- -- --

			$result = $count . ' ' . $result;
		}

		return $result;
	}

	/**
	 *
	 * Возвращает форму слова, описывающего число
	 *
	 * @author Pabolkov D.
	 *
	 * @param int $num
	 *
	 * @return int
	 */
	public static function getCountPostfixForm($num) {
		$num  = abs($num);
		$dec  = $num % 10;
		$form = 0;
		if ($dec >= 2 && $dec <= 4) {
			$form = 1;
		}
		if ($dec == 0 || ($dec >= 5 && $dec <= 9) || ($num > 10 && $num < 20)) {
			$form = 2;
		}

		return $form;
	}

	/**
	 * Регистронечувствительное сравнение строк.
	 *
	 * @param  string $str1
	 * @param  string $str2
	 * @param null    $encoding
	 *
	 * @return bool Строки равны или нет
	 */
	public static function stringCaseCompare($str1, $str2, $encoding = null) {
		if (null === $encoding) {
			$encoding = mb_internal_encoding();
		}
		return strcmp(mb_strtoupper($str1, $encoding), mb_strtoupper($str2, $encoding)) === 0;
	}

}