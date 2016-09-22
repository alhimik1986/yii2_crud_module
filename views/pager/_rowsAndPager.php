<!-- Пейджер -->
<?php if (($pagerInfo['count'] > 5 ) OR ($pagerInfo['count'] > $pagerInfo['limit'])): ?>
<tr class="tr-pager">
	<td colspan="<?php echo $columns; ?>">
		<div class="urv-pager"><?php $this->renderPartial('application.modules.js_plugins.views.pager._index_rows_pager', array('pagerInfo'=>$pagerInfo, 'pageName'=>'page')); ?></div>
	</td>
</tr>
<?php endIf; ?>
<!-- Конец Пейджер -->

<?php echo TableHelper::arrayToHtmlTable($table); ?>

<!-- Пейджер -->
<?php if (($pagerInfo['count'] > 5 ) OR ($pagerInfo['count'] > $pagerInfo['limit'])): ?>
<tr class="tr-pager">
	<td colspan="<?php echo $columns; ?>">
		<div class="urv-pager"><?php $this->renderPartial('application.modules.js_plugins.views.pager._index_rows_pager', array('pagerInfo'=>$pagerInfo, 'pageName'=>'page')); ?></div>
	</td>
</tr>
<?php endIf; ?>
<!-- Конец Пейджер -->