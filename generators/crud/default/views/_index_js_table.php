<?php
$l18n = $generator->enableI18N;
function translating6($l18n, $message) {
	return $l18n ? '<?= Yii::t(\'app\', \''.$message.'\') ?>' : $message;
}

?>
<?= '<?php' ?> /* @var $className string */ ?>
<?= '<?php' ?> /* @var $loading_img string */ ?>
<?= '<?php' ?> if (false): ?><script type="text/javascript"><?= '<?php' ?> endIf; // Эта строка нужна только для подстветки синтаксиса ?>
<?= '<?php' ?> use yii\helpers\Url; ?>
<?= '<?php' ?> $table_id = '#'.$className.'-table'; $wrapper_selector = '#'.$className.'-wrapper'; ?>

(function($){$(document).ready(function(){
	var loadingElem = function(settings){return '<?= '<?=' ?> '<?= '<?=' ?>' ?>$table_id; ?>';}; // Место, где показывать индикатор загрузки
	var loadingStyle = function(settings){                           // Стиль элемента (индикатора загрузки)
		var result = 'position:absolute;margin-top:2px;margin-left:3px; width:16px; height:16px; background-repeat: no-repeat;';
		result += "<?= '<?php' ?> echo $loading_img ? 'background-image: url(\''.$loading_img.'\');' : ''; ?>";
		return result;
	};
	var csrf = {<?= '<?=' ?>Yii::$app->request->csrfParam . ":'" . Yii::$app->request->csrfToken."'"; ?>};
	// Форма создания
	var ajax_form_create = {
		loadingElem: loadingElem,
		loadingStyle: loadingStyle,
		csrf: csrf,
 		form: {
			selector: '#<?= '<?=' ?>$className; ?>-ajax-form' // Селектор формы (ее обертки). Нужна, т.к. при открытии новой формы нужно закрыть предыдущую
		},
		create: {
			delegator: '<?= '<?=' ?>$wrapper_selector?>',
			selector: '.ajax-form-button-create',
			on: 'click',
			ajax: function(settings) {
				return {
					url: '<?= '<?=' ?>Url::to(['create']); ?>'
				};
			},
			success : function(data, settings) {
				$(settings.form.selector).remove(); // удаляем предыдующую форму, если мы не закроем предыдущую форму и откроем другую, то предыдущие нужно удалять, чтобы они не загромождали друг друга
				// В этой функции обязательно нужно вернуть jQuery-объект полученной формы для ее последующей обработки
				return $($(data)).appendTo('<?= '<?=' ?>$wrapper_selector?>');
			},
			afterSuccess: function(settings) {
				var $form = settings.form.$;
				$form.find('form').focus().find('input[type="text"]:first').focus();
			}
		},
		submit: {
			selector: '.ajax-form-button-submit',
			ajax: function(settings) {
				var $form = settings.submit.$.parents('form');
				return {
					url: $form.attr('action'),
					data: $form.serializeArray()
				};
			}
		},
		afterSubmit: {
			ajax: function(settings) {
				$(settings.form.selector).remove(); // Закрываю форму только после удачной записи и обновлении таблицы
				$('<?= '<?=' ?>$table_id; ?>').trigger('search'); // Обновляю таблицу (поиск в таблице)
				return false; // Не делать запрос после удачной отправки формы, т.к. форма обновляется вызовом триггера "search"
			},
			success: function(data, settings) {}
		}
	};
	new ajaxForm(ajax_form_create);
	
	// Форма редактирования
	var ajax_form_edit = {
		loadingElem: loadingElem,
		loadingStyle: loadingStyle,
		csrf: csrf,
 		form: {
			selector: '#<?= '<?=' ?>$className; ?>-ajax-form'  // Селектор формы (ее обертки). Нужна, т.к. при открытии новой формы нужно закрыть предыдущую
		},
		create: {
			delegator: '<?= '<?=' ?>$wrapper_selector?>',
			selector: '[data_id]',
			on: 'click',
			ajax: function(settings) {
				return {
					url: '<?= '<?=' ?>Url::to(['update']); ?>',
					data: {id: settings.create.$.attr('data_id')}
				};
			},
			success: function(data, settings) {
				// Удаляем предыдующую форму, если мы не закроем предыдущую форму и откроем другую, то предыдущие нужно удалять, чтобы они не загромождали друг друга
				$(settings.form.selector).remove();
				// В этой функции обязательно нужно вернуть jQuery-объект полученной формы для ее последующей обработки
				return $(data).appendTo('<?= '<?=' ?>$wrapper_selector?>');
			},
			afterSuccess: function(settings) {
				var $form = settings.form.$;
				$form.find('form').focus().find('input[type="text"]:first').focus();
			}
		},
		submit: {
			selector: '.ajax-form-button-submit, .ajax-form-button-delete',
			ajax: function(settings) {
				var $form = settings.submit.$.parents('form');
				if ( settings.submit.$.hasClass('ajax-form-button-delete')) {
					if ( ! confirm('<?= translating6($l18n, 'Remove permanently the record?'); ?>'))
						return false;
				} else if ($form.find('.ajax-form-button-delete').length == 0 && $form.find(':checkbox[name="<?= '<?=' ?>$className; ?>[removed]"]').is(':checked')) {
					if ( ! confirm('<?= translating6($l18n, 'Mark the record as removed?'); ?>'))
						return false;
				}
				
				var $_return = {};
				
				var url = $form.attr('action');
				var data = $form.serializeArray();
				if ( settings.submit.$.hasClass('ajax-form-button-delete')) {
					url = '<?= '<?=' ?>Url::to(['delete']); ?>';
					data = {
						<?= '<?=' ?>$className; ?>: {
							id: settings.create.$.attr('data_id')
						}
					};
				}
				$_return['url'] = url;
				$_return['data'] = data;
				
				return $_return;
			}
		},
		afterSubmit: {
			ajax: function(settings) {
				$(settings.form.selector).remove(); // Закрываю форму только после удачной записи и обновлении таблицы
				$('<?= '<?=' ?>$table_id; ?>').trigger('search'); // Обновляю таблицу (поиск в таблице)
				return false; // Не делать запрос после удачной отправки формы, т.к. форма обновляется вызовом триггера "search"
			},
			success: function(data, settings) {}
		}
	};
	new ajaxForm(ajax_form_edit);
	
	// Удаление записи в таблице
	var ajax_form_delete = {
		loadingElem: loadingElem,
		loadingStyle: loadingStyle,
		csrf: csrf,
		create: {
			delegator: '<?= '<?=' ?>$wrapper_selector?>',
			selector: '[delete_data_id]',
			on: 'click',
			ajax: function(settings) {
				if ( ! confirm('<?= translating6($l18n, 'Remove permanently the record?'); ?>'))
					return false;
				
				return {
					url: '<?= '<?=' ?>Url::to(['delete']); ?>',
					data: {
						<?= '<?=' ?>$className; ?>: {
							id: settings.create.$.attr('delete_data_id')
						}
					},
					type: 'post'
				};
			},
			success: function(data, settings) {
				$(settings.form.selector).remove();
				$('<?= '<?=' ?>$table_id; ?>').trigger('search'); // Обновляю таблицу (поиск в таблице)
				return false; // Не делать запрос после удачной отправки формы, т.к. форма обновляется вызовом триггера "search"
			}
		}
	};
	new ajaxForm(ajax_form_delete);
	
	// Удаление выделенных записей в таблице
	var ajax_form_delete_selected = {
		loadingElem: loadingElem,
		loadingStyle: loadingStyle,
		csrf: csrf,
		create: {
			delegator: '<?= '<?=' ?>$wrapper_selector?>',
			selector: '.ajax-form-button-deleteAll',
			on: 'click',
			ajax: function(settings) {
				if ( ! confirm('<?= translating6($l18n, 'Remove selected records?'); ?>'))
					return false;
				
				var ids = {};
				$('<?= '<?=' ?>$table_id ?> [checkbox_id]:checked').each(function(){
					var id = $(this).attr('checkbox_id');
					ids[id] = id;
				});
				$('.select-all-records-checkbox').prop('checked', false);
				return {
					url: '<?= '<?=' ?>Url::to(['delete-selected']); ?>',
					data: {
						<?= '<?=' ?>$className; ?>: {
							ids: ids
						}
					},
					type: 'post'
				};
			},
			success: function(data, settings) {
				$('<?= '<?=' ?>$table_id; ?>').trigger('search'); // Обновляю таблицу (поиск в таблице)
				return false; // Не делать запрос после удачной отправки формы, т.к. форма обновляется вызовом триггера "search"
			}
		}
	};
	new ajaxForm(ajax_form_delete_selected);
	
	// Просмотр записи
	var ajax_form_view = {
		loadingElem: loadingElem,
		loadingStyle: loadingStyle,
		csrf: csrf,
 		form: {
			selector: '#<?= '<?=' ?>$className; ?>-ajax-form'  // Селектор формы (ее обертки). Нужна, т.к. при открытии новой формы нужно закрыть предыдущую
		},
		create: {
			delegator: '<?= '<?=' ?>$wrapper_selector?>',
			selector: '[view_data_id]',
			on: 'click',
			ajax: function(settings) {
				return {
					url: '<?= '<?=' ?>Url::to(['view']); ?>',
					data: {id: settings.create.$.attr('view_data_id')}
				};
			},
			success: function(data, settings) {
				$(settings.form.selector).remove();
				// В этой функции обязательно нужно вернуть jQuery-объект полученной формы для ее последующей обработки
				return $(data).appendTo('<?= '<?=' ?>$wrapper_selector?>');
			},
			afterSuccess: function(settings) {
				var $form = settings.form.$;
				$form.find('form').focus();
			}
		}
	};
	new ajaxForm(ajax_form_view);
	
	$('[name="<?= '<?=' ?>$className; ?>[all_text]"]').focus();
	$(document).on('submit', '#<?= '<?=' ?>$className; ?>-form', function(e){
		if ( ! $(this).hasClass('submit'))
			return false;
	});
});})(jQuery);
<?= '<?php' ?> if (false): ?></script><?= '<?php' ?> endIf; ?>