<?php
/**
 * Вспомогательный класс для отображения пейджера.
 * 
 * @author Сидорович Николай <sidorovich21101986@mail.ru>
 * @link https://github.com/alhimik1986
 * @copyright Copyright &copy; 2016
 * 
 */

namespace alhimik1986\yii2_crud_module\web;

use Yii;

class Pager
{
	/**
	 * @param array $search Критерии поиска в формате: ИмяПоляТаблицы=>ЗначениеПоляТаблицы, где $search = array(
	 *     'per-page' => '', // число результатов на страницу
	 *     'page'  => '', // текущая страница в пейджере
	 *     'count' => '', // общее число результатов
	 * )
	 * 
	 * @return array Вся информация о пейджере (левый, средний, правый пейджер и число результатов).
	 */
	public static function getPagerInfo($search, $count)
	{
		$resultsPerPage = self::getResultsPerPage($search);
		$resultsPerPage = self::getResultsPerPageNotMoreThanMax($resultsPerPage);

		$page = (isset($search['page']) AND (int)$search['page']) ? (int)$search['page'] : 1; // Текущая страница в пейджере
		$pager = self::getPagesList($count, $page, $resultsPerPage);
		$pager['count'] = $count;
		return $pager;
	}

	/**
	 * @param array $search
	 * @return integer Число записей на страницу, полученное из http-запроса
	 */
	private static function getResultsPerPage($search)
	{
		$defaultPerPage = Yii::$app->hasModule('settings')
			? Yii::$app->settings->param['pager']['default']['resultsPerPageDefault']
			: 20;

		$resultsPerPage = (isset($search['per-page']) AND (int)$search['per-page'])
			? (int)$search['per-page']
			: $defaultPerPage;

		return $resultsPerPage;
	}

	/**
	 * @param integer $resultsPerPage Число записей на страницу, полученное из http-запроса
	 * @return integer Число записей на страницу, но не больше, чем задано в модуле настроек
	 */
	private static function getResultsPerPageNotMoreThanMax($resultsPerPage)
	{
		$max = Yii::$app->hasModule('settings')
			? Yii::$app->settings->param['pager']['default']['resultsPerPageMax']
			: 1000;

		return ($resultsPerPage > 0 AND $resultsPerPage <= $max)
			? $resultsPerPage
			: $max;
	}

	/**
	 * @param int $count Число результатов поиска
	 * @param int $page Текущая страница
	 * @param int $resultsPerPage Результатов на страницу
	 * @return array Список страниц в результатах поиска (левый, средний и правый pager)
	 */
	public static function getPagesList($count, $page, $resultsPerPage)
	{
		// Настройки
		$pagerLeftCount      = 1; // Число страниц в левом пейджере
		$pagerRightCount     = 1; // Число страниц в правом пейджере
		// Максимальное число страниц в пейджере
		$maxPages = Yii::$app->hasModule('settings')
			? Yii::$app->settings->param['pager']['default']['maxPages']
			: 10;
		
		// Список страниц в среднем пейджере при бесконечном числе результатов.
		$pagesCount = ceil($count / $resultsPerPage); // Число страниц в пейджере в результатах поиска
		$startPage  = $page - floor($maxPages/2);
		$startPage  = ($startPage > $pagesCount - $maxPages + 1) ? $pagesCount - $maxPages + 1: $startPage;
		$startPage  = ($startPage > 0) ? $startPage : 1;
		$stopPage   = $startPage + $maxPages - 1;
		$middle     = array(); // средний пейджер
		for($i=$startPage; $i<=$stopPage; $i++) {
			$middle[$i] = $i;
		}
		// Убираю из этого списка страницы, которые не входят в текущий диапазон.
		foreach($middle as $key=>$p) {
			if ($p > $pagesCount) unset($middle[$key]);
		}
		
		// Убираю из списка посередине крайние первые и посление страницы, добавляя значения к левым и правым пейджерам
		$left = array(); // Левый пейджер
		for($i=1; $i<=$pagerLeftCount; $i++) {
			if ( ! isset($middle[$i])) {
				$left[$i] = $i;
				//unset($middle[reset($middle)]);
			}
		}
		$right = array(); // Правый пейджер
		for($i=$pagesCount-$pagerRightCount+1; $i<=$pagesCount; $i++) {
			if ( ! isset($middle[$i])) {
				$right[$i] = $i;
				//unset($middle[end($middle)]);
			}
		}
		
		return array(
			'left'  =>$left, 'middle'=>$middle, 'right'=>$right, // Число страниц в левом, правом и среднем пейджере
			'start' =>(($page - 1)*$resultsPerPage + 1 < 1) ? 0 : ($page - 1)*$resultsPerPage + 1, // Какая по счету показана первая строка результата
			'stop'  =>($page*$resultsPerPage > $count) ? $count : $page*$resultsPerPage,           // Какая по счету показана последняя строка результата
			'count' => $count, // Число результатов поиска
			'limit' => $resultsPerPage, // Число результатов на страницу
			'page'  => $page, // Текущая страница в пейджере
			'nextPage' => $page + 1 > $pagesCount ? 0 : $page + 1, // Следующая страница
			'prevPage' => $page - 1 < 1 ? 0 : $page - 1, // Предыдущая страница
			'pagesCount' => $pagesCount, // Число страниц в пейджере
		);
	}
}