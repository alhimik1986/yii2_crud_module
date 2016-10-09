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
/* @var $searchModelName string */
/* @var $modelName string */
/* @var $tableName string */
/* @var $loading_img string */
/* @var $dataProvider yii\data\ActiveDataProvider */

use yii\helpers\Html;
use yii\helpers\Url;
use yii\widgets\ActiveForm;
use yii\helpers\StringHelper;

$this->title = <?= $generator->generateString(Inflector::pluralize(Inflector::camel2words(StringHelper::basename($generator->modelClass)))) ?>;
$this->params['breadcrumbs'][] = $this->title;

echo $this->render('index/_js_plugins', ['modelName'=>$modelName, 'loading_img' => $loading_img]);
Yii::$app->view->registerJs($this->render('index/_js_table', ['modelName'=>$modelName, 'loading_img' => $loading_img, 'controller_id' => Yii::$app->controller->id]));
?>


<!-- Этот id нужен для разделения crud-ов, когда на одной странице находится несколько таких таблиц, чтобы на каждую таблицу дейстовали свои кнопки. -->
<!-- This id required for the separation of several crud-s, when some crud-tables are on the same page. Dividing them by the id, the every buttons works for corresponding tables. -->
<div id="<?= '<?=' ?> $modelName?>-wrapper">

	<!-- The custom validation errors will be displayed in this block -->
	<div id="<?= '<?=' ?>$modelName?>-errors"></div>

	<?= '<?php' ?> $form = ActiveForm::begin(['id'=>$modelName.'-form']); ?>

    <div>
		<!-- Additional table header -->
		<?= '<?=' ?> $this->render('index/_tableHeader') ?>

		<!-- Table -->
		<div class="grid-view">
			<table class="table table-striped table-bordered table-condensed" id="<?= '<?='?>$modelName?>-table">
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
						<td><input type="text" name="<?='<?='?>$searchModelName?>[<?=$column?>]" class="form-control search-on-change"></td>
<?php endForeach; ?>
<?php if ($remaining_columns): ?>
<!--
<?php endIf; ?>
<?php foreach($remaining_columns as $column): ?>
						<td><input type="text" name="<?='<?='?>$searchModelName?>[<?=$column?>]" class="form-control search-on-change"></td>
<?php endForeach; ?>
<?php if ($remaining_columns): ?>
-->
<?php endIf; ?>
						<td></td>
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