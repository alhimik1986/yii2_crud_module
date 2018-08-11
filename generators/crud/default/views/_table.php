<?php

use yii\helpers\Inflector;
use yii\helpers\StringHelper;

/* @var $this yii\web\View */
/* @var $generator yii\gii\generators\crud\Generator */

$urlParams = $generator->generateUrlParams();
$nameAttribute = $generator->getNameAttribute();

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

// get pk name
$pk_name = $generator->getTableSchema()->primaryKey[0];

echo "<?php\n";
?>
<?= !empty($generator->searchModelClass) ? "/* @var \$searchModel " . ltrim($generator->searchModelClass, '\\') . " */\n" : '' ?>
/* @var $dataProvider yii\data\ActiveDataProvider */

use yii\helpers\Url;

$counter = 0;
$models = $dataProvider->getModels();
$pagerInfo = alhimik1986\yii2_crud_module\web\Pager::getPagerInfo(Yii::$app->request->queryParams, $dataProvider->totalCount);
$columns = <?= (count($columns) + 3) ?>;
?>

<!-- Pager -->
<?= '<?php' ?> if (($pagerInfo['count'] > 5 ) OR ($pagerInfo['count'] > $pagerInfo['limit'])): ?>
<tr class="tr-pager">
	<td colspan="<?= '<?php' ?> echo $columns; ?>">
		<?= '<?php' ?> echo $this->render('@vendor/alhimik1986/yii2_crud_module/views/pager/_pager', array('pagerInfo'=>$pagerInfo, 'pageName'=>'page')); ?>
	</td>
</tr>
<?= '<?php' ?> endIf; ?>
<!-- End Pager -->

<?= '<?php' ?> foreach($models as $model): ?>
	<tr data-key="<?= '<?=' ?>$model['<?=$pk_name?>']?>">
		<td><input type="checkbox" checkbox_id="<?= '<?=' ?>$model['<?=$pk_name?>']?>" onclick="jQuery(this).parents('tr').toggleClass('danger', jQuery(this).prop('checked'));"></td>
		<td><?= '<?=' ?>++$counter?></td>
<?php foreach($columns as $column): ?>
		<td><?= '<?=' ?>$model['<?=$column?>']?></td>
<?php endForeach; ?>
<?php if ($remaining_columns): ?>
<!--
<?php endIf; ?>
<?php foreach($remaining_columns as $column): ?>
		<td><?= '<?=' ?>$model['<?=$column?>']?></td>
<?php endForeach; ?>
<?php if ($remaining_columns): ?>
-->
<?php endIf; ?>
		<td>
			<a class="btn btn-success btn-xs" href="#" view_data_id="<?= '<?=' ?>$model['<?=$pk_name?>']?>">
				<i class="glyphicon glyphicon-eye-open"></i>
			</a>
			<a class="btn btn-primary btn-xs" href="#" data_id="<?= '<?=' ?>$model['<?=$pk_name?>']?>">
				<i class="glyphicon glyphicon-pencil"></i>
			</a> 
			<a class="btn btn-danger btn-xs" href="#" delete_data_id="<?= '<?=' ?>$model['<?=$pk_name?>']?>">
				<i class="glyphicon glyphicon-trash"></i>
			</a>
		</td>
	</tr>
<?= '<?php' ?> endForeach ?>