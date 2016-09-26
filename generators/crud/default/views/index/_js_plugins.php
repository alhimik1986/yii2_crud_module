<?= '<?php' ?>

/* @var $modelName string */
/* @var $loading_img string */

Yii::$app->view->registerJs(alhimik1986\yii2_js_view_module\components\JSPlugins::includePlugins([
	'ajaxTable' => [
		[
			'tbl_selector' => '#'.$modelName.'-table',                   // Селектор ajax-таблицы для поиска
			'error_selector' => '#'.$modelName.'-errors',                // Место, куда будут выводиться нестандартные ошибки валидации
			'search_url' => \yii\helpers\Url::to(['search']),            // url-адрес, куда отправлять искомые данные
			'search_request_type' => 'get',                              // Тип запроса при поиске в таблице
			'search_on_change_selector' => '.search-on-change',          // Селектор элементов, при изменении или нажатии клавиш которых осуществлять поиск
			'search_on_change_dateSelector' => '.from-date, .to-date',   // Селектор элементов, при изменении которых осуществлять поиск
			'tooltip_selector' => '',                                    // Селектор для всплывающей подсказки при обновлении ajax-таблицы
			'loading_img' => $loading_img,                               // url-адрес индикатора загрузки
			'ajaxDataCallBack' => 'js:function(data){return data;}',     // Пост-обработка данных для поиска перед отправкой ajax-запроса (например, чтобы втиснуть в поиск диапазон дат from_date и to_date)
			'afterSuccessCallBack' => 'js:function(data){}',             // Дополнительные операции при успешном запросе (в поиске)
		]
	],
]));