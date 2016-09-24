<?php

use yii\helpers\Inflector;
use yii\helpers\StringHelper;

/* @var $this yii\web\View */
/* @var $generator yii\gii\generators\crud\Generator */

$urlParams = $generator->generateUrlParams();
$nameAttribute = $generator->getNameAttribute();

$l18n = $generator->enableI18N;
function translating2($l18n, $message) {
	return $l18n ? '<?= Yii::t(\'app\', \''.$message.'\') ?>' : $message;
}

// List of columns
$count = 0;
$columns = [];
$remaining_columns = [];
if (($tableSchema = $generator->getTableSchema()) === false) {
    foreach ($generator->getColumnNames() as $name) {
        if (++$count < 6) {
            $columns[$name] = $name;
        } else {
            $remaining_columns[$name] = $name;
        }
    }
} else {
    foreach ($tableSchema->columns as $column) {
        $format = $generator->generateColumnFormat($column);
		$name = $column->name;
        if (++$count < 6) {
            $columns[$name] = $name;
        } else {
            $remaining_columns[$name] = $name;
        }
    }
}


echo "<?php\n";
?>
<?= !empty($generator->searchModelClass) ? "/* @var \$searchModel " . ltrim($generator->searchModelClass, '\\') . " */\n" : '' ?>
<?= "/* @var \$model " . ltrim($generator->modelClass, '\\') . " */\n" ?>
/* @var $dataProvider yii\data\ActiveDataProvider */

use yii\helpers\Html;
use yii\helpers\Url;
use yii\widgets\ActiveForm;

$this->title = <?= $generator->generateString(Inflector::pluralize(Inflector::camel2words(StringHelper::basename($generator->modelClass)))) ?>;
$this->params['breadcrumbs'][] = $this->title;

$className = substr(strrchr($searchModel::className(), "\\"), 1);
$parentClassName = substr(strrchr($model::className(), "\\"), 1);
$tableName = $model::tableName();
$loading_img = ($loading_img = Yii::$app->assetManager->publish(Yii::getAlias('@vendor').'/alhimik1986/yii2_js_view_module/assets/img/ajax-loader.gif')) ? $loading_img[1] : '';
echo $this->render('_js_plugins', ['className'=>$parentClassName, 'loading_img' => $loading_img]);
Yii::$app->view->registerJs($this->render('_index_js_table', ['className'=>$parentClassName, 'loading_img' => $loading_img]));
?>
<!-- Этот id нужен для разделения crud-ов, когда на одной странице находится несколько таких таблиц, чтобы на каждую таблицу дейстовали свои кнопки. -->
<!-- This id required for the separation of several crud-s, when some crud-tables are on the same page. Dividing them by the id, the every buttons works for corresponding tables. -->
<div id="<?= '<?=' ?> $parentClassName?>-wrapper">
    <!--<h1>Title</h1>    
    <p>Description</p>-->

	<!-- В этот блок будут выводиться нестандартные ошибки валидации -->
	<!-- The custom validation errors will be displayed in this block -->
	<div id="<?= '<?=' ?>$parentClassName?>-errors"></div>
	
	<?= '<?php' ?> $form = ActiveForm::begin([
		'id'=>$parentClassName.'-form',
		'enableClientValidation' => true,
		'options' => [
			// tabindex нужен, чтобы внутри кнопки дейстовали горячие клавиши
			// The tabindex needs for working the hot keys in the form
			'tabindex' => 2,
		],
	]); ?>
    <div>
		<div style="float:left;">
			<p>
				<a class="btn btn-success ajax-form-button-create" href="#">
					<i class="glyphicon glyphicon-plus"></i> <?=translating2($l18n, 'Create')?> 
				</a>
			</p>
		</div>
		<div style="float:right;">
			<p>
				<a class="btn btn-danger ajax-form-button-deleteAll" href="#">
					<i class="glyphicon glyphicon-trash"></i> <?=translating2($l18n, 'Delete selected')?> 
				</a>
			</p>
		</div>
		<div style="clear:both;"></div>
		
		<div class="grid-view">
			<table class="table table-striped table-bordered table-condensed" id="<?= '<?='?>$parentClassName?>-table">
				<thead>
					<tr>
						<th><input type="checkbox" class="select-all-records-checkbox" onclick="jQuery(this).parents('table').find('tbody td:nth-child('+(jQuery(this).parents('th')[0].cellIndex+1)+') :checkbox').prop('checked', jQuery(this).prop('checked')); jQuery(this).parents('table').find('tbody tr:has(:checkbox)').toggleClass('danger', jQuery(this).prop('checked'));"></th>
						<th>#</th>
<?php foreach($columns as $column): ?>
						<th data-sort="<?='<?='?>$tableName?>.<?=$column?>"><a href="#" onclick="return false;"><?='<?='?>$searchModel->getAttributeLabel('<?=$column?>')?></a></th>
<?php endForeach; ?>
<?php if ($remaining_columns): ?>
<!--
<?php endIf; ?>
<?php foreach($remaining_columns as $column): ?>
						<th data-sort="<?='<?='?>$tableName?>.<?=$column?>"><a href="#" onclick="return false;"><?='<?='?>$searchModel->getAttributeLabel('<?=$column?>')?></a></th>
<?php endForeach; ?>
<?php if ($remaining_columns): ?>
-->
<?php endIf; ?>
						<th style="width:120px;"><?=translating2($l18n, 'Actions')?>&nbsp;</th>
					</tr>
					<tr class="filters">
						<td>&nbsp;</td>
						<td>&nbsp;</td>
<?php foreach($columns as $column): ?>
						<td><input type="text" name="<?='<?='?>$className?>[<?=$column?>]" class="form-control search-on-change"></td>
<?php endForeach; ?>
<?php if ($remaining_columns): ?>
<!--
<?php endIf; ?>
<?php foreach($remaining_columns as $column): ?>
						<td><input type="text" name="<?='<?='?>$className?>[<?=$column?>]" class="form-control search-on-change"></td>
<?php endForeach; ?>
<?php if ($remaining_columns): ?>
-->
<?php endIf; ?>
						<td><?= '<?='?>Html::dropDownList('per-page', 10, [1=>1, 3=>3, 5=>5, 10=>10, 30=>30, 100=>100], ['class'=>'search-on-change']); ?></td>
					</tr>
				</thead>
				<tbody>
					<?= '<?php' ?> //echo $this->render('_table', ['dataProvider'=>$dataProvider, 'pk'=>$searchModel->tableSchema->primaryKey]); ?>
				</tbody>
			</table>
		</div>
    </div>
	
	<?= "<?php " ?>ActiveForm::end(); ?>
</div>