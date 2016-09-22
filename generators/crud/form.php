<?php
/* @var $this yii\web\View */
/* @var $form yii\widgets\ActiveForm */
/* @var $generator yii\gii\generators\crud\Generator */

echo $form->field($generator, 'modelClass');
echo $form->field($generator, 'searchModelClass');
echo $form->field($generator, 'controllerClass');
echo $form->field($generator, 'viewPath');
echo $form->field($generator, 'baseControllerClass');
echo $form->field($generator, 'indexWidgetType')->dropDownList([
    'grid' => 'GridView',
    'list' => 'ListView',
]);
echo $form->field($generator, 'enableI18N')->checkbox();
//echo $form->field($generator, 'enablePjax')->checkbox();
echo $form->field($generator, 'messageCategory');


// Autocomplete remaining fields, if modelClass was specified
$script = <<<EOL
(function(\$){\$(document).ready(function(){
	\$('#generator-modelclass').on('change', function(){
		var modelClass = $(this).val();
		if ( ! modelClass)
			return;
		
		// get base path
		var paths = modelClass.split('\\\').slice(0, -2);
		var base_path = '';
		for(var path in paths) {
			base_path += base_path ? '\\\' : '';
			base_path += paths[path];
		}
		
		// get model name
		var model_name = modelClass.split('\\\').slice(-1);
		
		
		controller_path = base_path + '\\\' + 'controllers' + '\\\' + model_name + 'Controller';
		
		$('#generator-searchmodelclass').val($(this).val()+'Search');
		$('#generator-controllerclass').val(controller_path);
		
	});
});})(jQuery);
EOL;
Yii::$app->view->registerJs($script, \yii\web\View::POS_END);