<?= '<?php' ?> use yii\helpers\Html; ?>

<div style="background: #efefef; padding:10px 10px 0 10px; border-top-left-radius:3px; border-top-right-radius:3px; border:1px solid #ccc;">
	<div style="float:left;">
		<p>
			<a class="btn btn-success ajax-form-button-create" href="#">
				<i class="glyphicon glyphicon-plus"></i> Create 
			</a>
		</p>
	</div>
	<div style="float:left; margin-left:50px; margin-top:5px;">
		<?= '<?=' ?> Yii::t('app', 'Results per page')?>: 
		<?= '<?=' ?> Html::dropDownList('per-page', 10, [1=>1, 3=>3, 5=>5, 10=>10, 30=>30, 100=>100], ['class'=>'search-on-change']); ?>
	</div>
	<div style="float:right;">
		<p>
			<a class="btn btn-danger ajax-form-button-deleteAll" href="#">
				<i class="glyphicon glyphicon-trash"></i> Delete selected 
			</a>
		</p>
	</div>
	<div style="clear:both;"></div>
</div>