<?php
/**
 * Вспомогательный класс для сортировки по нескольким полям, в которой данные приходят из запроса.
 * 
 * @author Сидорович Николай <sidorovich21101986@mail.ru>
 * @link https://github.com/alhimik1986
 * @copyright Copyright &copy; 2016
 * 
 * Пример использования:
 * ```
 * $sort = (new alhimik1986\yii2_crud_module\web\Sort)
 *     ->setOrderByDefault('id asc')                    // сортировать по указанным колонкам, если не указана сортировка
 *     ->appendAllowedColumns(['tableName.columnName']) // дополнительные колонки, по которым разрешить сортировку
 *     ->getOrder(Yii::$app->request->queryParams, $searchModel);
 * ```
 * или 
 * ```
 * $sort = alhimik1986\yii2_crud_module\web\Sort::init()->getOrder(Yii::$app->request->queryParams, $searchModel);
 * ```
 */

namespace alhimik1986\yii2_crud_module\web;

use Yii;

class Sort
{
	protected $allowedColumns = null;
	protected $additionalAllowedColumns = null;
	protected $orderByDefault = null;

	public function setAllowedColumns($value)
	{
		$this->allowedColumns = $value; return $this;
	}

	public function appendAllowedColumns($value)
	{
		$this->additionalAllowedColumns = $value; return $this;
	}

	public function setOrderByDefault($value)
	{
		$this->orderByDefault = $value; return $this;
	}


	/**
	 * @param mixed $search Параметры поиска, принятые с формы.
	 * @param ActiveRecord $model Модель поиска.
	 * @return string Сортировка данных при поиске.
	 */
	public function getOrder($search, $model)
	{
		$order = '';
		
		if (isset($search['order'])) {
			$allowedColumns = is_null($this->allowedColumns) ? self::getAllowedToOrderColumns($model) : $this->allowedColumns;
			$additionalAllowedColumns = is_null($this->additionalAllowedColumns) ? self::appendAllowedToOrderColumns() : $this->additionalAllowedColumns;
			foreach($additionalAllowedColumns as $column)
				$allowedColumns[] = $column;
			$order = self::getOrderByAllowedColumns($search['order'], $allowedColumns, array('asc', 'desc'));
		} else {
			$order = is_null($this->orderByDefault) ? self::getOrderByDefault($model) : $this->orderByDefault;
		}
		
		return $order;
	}
	/**
	 * @return array Список всех колонок, по которым только возможно сортировать вообще.
	 */
	private static function getAllowedToOrderColumns($model)
	{
		$result = array();
		
		$tbl_name = $model::tableName();
		foreach ($model::getTableSchema()->columns as $column)
			$result[] = $tbl_name.'.'.$column->name;
		
		return $result;
	}
	/**
	 * @return array Список колонок, разрешенных для сортировки, который нужно добавить к тому, что по умолчанию.
	 */
	private static function appendAllowedToOrderColumns()
	{
		return array();
	}
	/**
	 * @return string Сортировка по умолчанию (если параметр сортировки не задан).
	 */
	private static function getOrderByDefault($model)
	{
		$pk = $model::primaryKey();
		if ($pk) {
			return $model::tableName().'.'.$pk[0].' desc';
		} else {
			return '';
		}
	}


	/**
	 * Помогает создавать экзмепляр одной строчкой.
	 * @return object Экземпляр этого класса.
	 */
	public static function init()
	{
		$className = get_called_class();
		return new $className();
	}


	/**
	 * Возвращает безопасный порядок сортировки из принятых данных.
	 * @param array $data Входные данные вида: array('имя_колонки'=>'порядок_сортировки', 'name'=>'asc', 'address'=>'desc').
	 * @param array $allowableKeys Список разрешенных имен полей, по которым можно сортировать вида: array('имя_колонки', 'name', 'address').
	 * @param array $allowableValues Список разрешенных порядков сортировки вида: array('asc', 'desc').
	 * @return string Строка для sql-запроса.
	 */
	private static function getOrderByAllowedColumns($data, $allowableKeys=array(), $allowableValues=array())
	{
		$keys = array(); $values = array();
		foreach($allowableKeys as $key) $keys[$key] = $key;
		foreach($allowableValues as $value) $values[$value] = $value;
		$allowableKeys = $keys;
		$allowableValues = $values;
		
		$result = '';
		if (is_array($data)) foreach($data as $column=>$order) {
			if (isset($allowableKeys[$column]) AND isset($allowableValues[$order])) {
				if ($result != '') $result .= ', ';
				$result .= $column.' '.$order;
			}
		}
		
		return $result;
	}
}