# yii2 ajax crud module

[![Latest Stable Version](https://poser.pugx.org/alhimik1986/yii2_crud_module/v/stable)](https://packagist.org/packages/alhimik1986/yii2_crud_module)
[![Latest Unstable Version](https://poser.pugx.org/alhimik1986/yii2_crud_module/v/unstable)](https://packagist.org/packages/alhimik1986/yii2_crud_module)
[![License](https://poser.pugx.org/alhimik1986/yii2_crud_module/license)](https://packagist.org/packages/alhimik1986/yii2_crud_module)
[![Total Downloads](https://poser.pugx.org/alhimik1986/yii2_crud_module/downloads)](https://packagist.org/packages/alhimik1986/yii2_crud_module)
[![Monthly Downloads](https://poser.pugx.org/alhimik1986/yii2_crud_module/d/monthly)](https://packagist.org/packages/alhimik1986/yii2_crud_module)
[![Daily Downloads](https://poser.pugx.org/alhimik1986/yii2_crud_module/d/daily)](https://packagist.org/packages/alhimik1986/yii2_crud_module)

Отличнейший модуль Gii CRUD для генерации кода одностаничных ajax-приложений на Yii2 framework.

![Yii2AjaxCrud Screenshot](https://raw.githubusercontent.com/alhimik1986/yii2_crud_module/master/screenshot.png)

## Характеристики
- Добавление, чтение, удаление, обновление записей без перезагрузки страницы методом ajax
- Возможность удалять записи пачками
- Используется ajax, а не pjax (поддерживает даже IE8)
- Имеется пейджер и возожность выставлять число записей на страницу (чего нет ни в одном из extensions для gii)
- Возможность сортировать по нескольким колонкам одновременно, т.е. например, сперва отсортировать по должностями, а потом каждую группу 
отсортировать по фамилии
- Возможность сортировать по связанным таблицам (как это сделать будет показано ниже)
- При поиске в текстовом поле не нужно нажимать Enter, поиск запускается автоматически, когда пользователь перестает печатать
- Имеются горячие клавиши (Insert, Ctrl+Enter, Ctrl+Delete, Esc, соответственно: добавить, сохранить, удалить, отмена)
- Прост в освоении, сгенерированный код легко использовать для создания ajax-страничек.

### TODO:
- Добавить возможность показывать детальную информацию прямо внутри таблицы (кнопка "expand", как здесь: http://demos.krajee.com/grid-demo)
- Сделать экспорт в html, csv, text, excel, pdf, json

## УСТАНОВКА:

Скачивается с помощью composer. В папке приложения в файле composer.json дописать строчку:
```
    "require": {
		"alhimik1986/yii2_crud_module": "1.0.x-dev"
    },
```
или в командой строке ввести:
```
$ composer require alhimik1986/yii2_crud_module:dev-master
```
Затем в файле config/web.php добавить следующие строчки:
```
// Добавляю генератор кода ajax_form_crud
if (YII_ENV_DEV) {
	$config['modules']['gii']['generators']['ajax_form_crud_generator'] = [
		'class' => 'alhimik1986\yii2_crud_module\generators\crud\Generator',
		'templates' => [
			'ajax_form_template' => '@vendor/alhimik1986/yii2_crud_module/generators/crud/default',
		],
	];
}
```
Прейдите по адресу: http://localhost/index.php?r=gii или http://localhost/gii . Далее перейдите по ссылке "AjaxForm CRUD Generator".

