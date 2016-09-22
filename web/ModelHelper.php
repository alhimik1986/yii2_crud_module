<?php
/**
 * Вспомогательный класс для моделей.
 * 
 * @author Сидорович Николай <sidorovich21101986@mail.ru>
 * @link https://github.com/alhimik1986
 * @copyright Copyright &copy; 2016
 * 
 */

namespace alhimik1986\yii2_crud_module\web;

use Yii;

class ModelHelper
{
	/**
	 * Решает проблему чувствительности символов к регистру в операторе LIKE для Sqlite.
	 * Источник: http://blog.amartynov.ru/archives/php-sqlite-case-insensitive-like-utf8/
	 * Эту функцию следует вызывать в местах, где используется оператор LIKE в запросах и база данных Sqlite.
	 * Замедляет время запроса как минимум в 2 раза.
	 * @param CDbConnection $db Объект подключения к базе данных.
	 */
	public static function sqliteFixCaseSensitiveInLikeOperator($db)
	{
		if (preg_match('/^sqlite\:.*/ui', $db->dsn)) {
			$db->getMasterPdo()->sqliteCreateFunction('like', function($mask, $value) {
				$mask = str_replace(
					array("%", "_"),
					array(".*?", "."),
					preg_quote($mask, "/")
				);
				$mask = "/^$mask$/ui";
				return preg_match($mask, str_replace("\n", ' ', $value));
			}, 2);
		}
	}
}