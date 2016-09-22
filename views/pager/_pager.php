<?php
use \alhimik1986\yii2_js_view_module\Module;
use \yii\helpers\Html;
 ?>

<div class="ajax-table-pager" style="text-align:center;">
<?php $onClick = "jQuery(this).parents('.pagination').parent().parent().parent().parent().find('input[name=\"".$pageName."\"]').val(jQuery(this).attr('data-page'));"; ?>
	<ul class="pagination" style="float:left; margin:0;">
		<li class="disabled"><span><?php echo Module::t('app', 'Rows:'); ?> <?php echo $pagerInfo['start']; ?> - <?php echo $pagerInfo['stop']; ?> <?php echo Module::t('app', 'of'); ?> <?php echo $pagerInfo['count']; ?></span></li>
	</ul>
<?php if ($pagerInfo['pagesCount'] > 1 ): ?>
	<ul class="pagination" style="text-align:center; margin:0;">
		<?php echo Html::hiddenInput($pageName, $pagerInfo['page']); ?>
		
		<?php if ($pagerInfo['prevPage']): ?>
			<li class="prev"><?php echo Html::a(Module::t('app', 'Previous'), ['index', 'per-page'=>$pagerInfo['limit'], 'page'=>$pagerInfo['prevPage']], ['data-page'=>$pagerInfo['prevPage'], 'onclick'=>$onClick]); ?></li>
		<?php else: ?>
			<li class="prev disabled"><span><?php echo Module::t('app', 'Previous'); ?></span></li>
		<?php endIf; ?>

		<?php if ($pagerInfo['left']): ?>
			<?php foreach($pagerInfo['left'] as $page): ?>
				<li><?php echo Html::a($page, ['index', 'per-page'=>$pagerInfo['limit'], 'page'=>$page], ['data-page'=>$page, 'onclick'=>$onClick]); ?></li>
			<?php endForeach; ?>
			<li class="current"><span>...</span></li>
		<?php endIf; ?>

		<?php if ($pagerInfo['middle']): ?>
			<?php foreach($pagerInfo['middle'] as $page): ?>
				<?php if ($page != $pagerInfo['page']): ?>
					<li><?php echo Html::a($page, ['index', 'per-page'=>$pagerInfo['limit'], 'page'=>$page], ['data-page'=>$page, 'onclick'=>$onClick]); ?></li>
				<?php else: ?>
					<li class="active"><span><?php echo $page; ?></span></li>
				<?php endIf; ?>
			<?php endForeach; ?>
		<?php endIf; ?>

		<?php if ($pagerInfo['right']): ?>
			<li class="current"><span>...</span></li>
			<?php foreach($pagerInfo['right'] as $page): ?>
				<li><?php echo Html::a($page, ['index', 'per-page'=>$pagerInfo['limit'], 'page'=>$page], ['data-page'=>$page, 'onclick'=>$onClick]); ?></li>
			<?php endForeach; ?>
		<?php endIf; ?>

		<?php if ($pagerInfo['nextPage']): ?>
			<li class="next"><?php echo Html::a(Module::t('app', 'Next'), ['index', 'per-page'=>$pagerInfo['limit'], 'page'=>$pagerInfo['nextPage']], ['data-page'=>$pagerInfo['nextPage'], 'onclick'=>$onClick]); ?></li>
		<?php else: ?>
			<li class="next disabled"><span><?php echo Module::t('app', 'Next'); ?></span></li>
		<?php endIf; ?>
	</ul>
<?php endIf; ?>
</div>