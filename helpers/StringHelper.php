<?php

namespace yiiCustom\helpers;

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

	/**
	 * Генерация транслитированной строки.
	 *
	 * @param string
	 * @return string
	 */
	public static function transliterate($title) {
		$title = preg_replace('/\[([^\]]+)\]/u', '', $title);
		$title = preg_replace('/\(([^\)]+)\)/u', '', $title);

		$translit = [
			'/'  => '-',
			'\\' => '-',
			' '  => '-',
			'а'  => 'a',
			'б'  => 'b',
			'в'  => 'v',
			'г'  => 'g',
			'д'  => 'd',
			'е'  => 'e',
			'ё'  => 'yo',
			'ж'  => 'zh',
			'з'  => 'z',
			'и'  => 'i',
			'й'  => 'j',
			'к'  => 'k',
			'л'  => 'l',
			'м'  => 'm',
			'н'  => 'n',
			'о'  => 'o',
			'п'  => 'p',
			'р'  => 'r',
			'с'  => 's',
			'т'  => 't',
			'у'  => 'u',
			'ф'  => 'f',
			'х'  => 'x',
			'ц'  => 'c',
			'ч'  => 'ch',
			'ш'  => 'sh',
			'щ'  => 'shh',
			'ы'  => 'y',
			'э'  => 'e',
			'ю'  => 'yu',
			'я'  => 'ya',
			'ь'  => '',
			'ъ'  => '',
			'-'  => '-',
		];

		$title = mb_strtolower($title, 'UTF-8');

		$title = str_replace(array_keys($translit), array_values($translit), $title);

		$title = preg_replace('/[^\-_A-z0-9]/u', '', $title);
		$title = str_replace('-[]', '', $title);
		$title = str_replace('[', '', $title);
		$title = str_replace(']', '', $title);
		$title = trim($title, '-');
		$title = preg_replace('/-+/', '-', $title);

		return $title;
	}

	/**
	 * Конвертация строки в Title case (с заглавной буквы)/
	 *
	 * @param string $input Строка
	 *
	 * @return string
	 */
	public static function toTitleCase($input) {
		return mb_strtoupper(mb_substr($input, 0, 1)) . mb_substr($input, 1);
	}

	/**
	 * Возвращает сумму прописью
	 *
	 * @return string
	 */
	public static function num2str($num, $currencyUnits = false) {
		$nul='ноль';
		$ten=array(
			array('','один','два','три','четыре','пять','шесть','семь', 'восемь','девять'),
			array('','одна','две','три','четыре','пять','шесть','семь', 'восемь','девять'),
		);
		$a20=array('десять','одиннадцать','двенадцать','тринадцать','четырнадцать' ,'пятнадцать','шестнадцать','семнадцать','восемнадцать','девятнадцать');
		$tens=array(2=>'двадцать','тридцать','сорок','пятьдесят','шестьдесят','семьдесят' ,'восемьдесят','девяносто');
		$hundred=array('','сто','двести','триста','четыреста','пятьсот','шестьсот', 'семьсот','восемьсот','девятьсот');
		$unit=array( // Units
			array('копейка' ,'копейки' ,'копеек',	 1),
			array('рубль'   ,'рубля'   ,'рублей'    ,0),
			array('тысяча'  ,'тысячи'  ,'тысяч'     ,1),
			array('миллион' ,'миллиона','миллионов' ,0),
			array('миллиард','милиарда','миллиардов',0),
		);
		//
		list($rub,$kop) = explode('.',sprintf("%015.2f", floatval($num)));
		$out = array();
		if (intval($rub)>0) {
			foreach(str_split($rub,3) as $uk=>$v) { // by 3 symbols
				if (!intval($v)) continue;
				$uk = sizeof($unit)-$uk-1; // unit key
				$gender = $unit[$uk][3];
				list($i1,$i2,$i3) = array_map('intval',str_split($v,1));
				// mega-logic
				$out[] = $hundred[$i1]; # 1xx-9xx
				if ($i2>1) $out[]= $tens[$i2].' '.$ten[$gender][$i3]; # 20-99
				else $out[]= $i2>0 ? $a20[$i3] : $ten[$gender][$i3]; # 10-19 | 1-9
				// units without rub & kop
				if ($uk>1) $out[]= static::morph($v,$unit[$uk][0],$unit[$uk][1],$unit[$uk][2]);
			} //foreach
		}
		else $out[] = $nul;

		if ($currencyUnits) {
			$out[] = static::morph(intval($rub), $unit[1][0], $unit[1][1], $unit[1][2]); // rub
			$out[] = $kop . ' ' . static::morph($kop, $unit[0][0], $unit[0][1], $unit[0][2]); // kop
		}

		return trim(preg_replace('/ {2,}/', ' ', join(' ',$out)));
	}

	/**
	 * Склоняем словоформу
	 * @ author runcore
	 */
	static private function morph($n, $f1, $f2, $f5) {
		$n = abs(intval($n)) % 100;
		if ($n>10 && $n<20) return $f5;
		$n = $n % 10;
		if ($n>1 && $n<5) return $f2;
		if ($n==1) return $f1;
		return $f5;
	}

}