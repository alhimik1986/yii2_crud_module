<?php
/**
 * This is the template for generating a CRUD controller class file.
 */

use yii\db\ActiveRecordInterface;
use yii\helpers\StringHelper;


/* @var $this yii\web\View */
/* @var $generator yii\gii\generators\crud\Generator */

$controllerClass = StringHelper::basename($generator->controllerClass);
$modelClass = StringHelper::basename($generator->modelClass);
$searchModelClass = StringHelper::basename($generator->searchModelClass);
if ($modelClass === $searchModelClass) {
    $searchModelAlias = $searchModelClass . 'Search';
}

/* @var $class ActiveRecordInterface */
$class = $generator->modelClass;
$pks = $class::primaryKey();
$urlParams = $generator->generateUrlParams();
$actionParams = $generator->generateActionParams();
$actionParamComments = $generator->generateActionParamComments();

echo "<?php\n";
?>

namespace <?= StringHelper::dirname(ltrim($generator->controllerClass, '\\')) ?>;

use Yii;
use <?= ltrim($generator->modelClass, '\\') ?> as Model;
use <?= ltrim($generator->searchModelClass, '\\') ?> as SearchModel;
use yii\helpers\StringHelper;
use alhimik1986\yii2_crud_module\web\JsonController as Controller;

/**
 * The controller implements the CRUD actions for model.
 */
class <?= $controllerClass ?> extends Controller
{
    /**
     * @inheritdoc
     */
    public function behaviors()
    {
        return [
            'verbs' => [
                'class' => \yii\filters\VerbFilter::className(),
                'actions' => [
                    'delete' => ['POST'],
                ],
            ],
        ];
    }

    /**
     * Main action, with list of records.
     */
    public function actionIndex()
    {
		$searchModel = new SearchModel();
		$model = new Model;
		
        return $this->render('index', [
			'searchModel' => $searchModel,
			'model' => $model,
			'searchModelName' => StringHelper::basename($searchModel::className()),
			'modelName' => StringHelper::basename($model::className()),
			'tableName' => Model::tableName(),
			'loading_img' =>($loading_img = Yii::$app->assetManager->publish(Yii::getAlias('@vendor').'/alhimik1986/yii2_js_view_module/assets/img/ajax-loader.gif')) ? $loading_img[1] : '',
			//'dataProvider' => $searchModel->search(Yii::$app->request->queryParams),
			//'models' => $dataProvider->getModels(),
			//'pagerInfo' => \alhimik1986\yii2_crud_module\web\Pager::getPagerInfo(Yii::$app->request->queryParams, $dataProvider->totalCount),
		]);
    }

    /**
     * Displays detailed information about the record.
     */
    public function actionView($id)
    {
        $model = $this->findModel($id);

		return $this->renderJson('_view', [
			'model' => $model,
			'modelName' => StringHelper::basename($model::className()),
			'formTitle' => 'View',
		]);
    }

    /**
     * Creates a new record.
     */
    public function actionCreate()
    {
        $model = new Model();

        if (Yii::$app->request->isPost) {
			$model->load(Yii::$app->request->post()) AND $model->save();
            $this->checkErrorsAndDisplayResult($model);
        } else {
            return $this->renderJson('_form', [
                'model' => $model,
				'modelName' => StringHelper::basename($model::className()),
				'formTitle' => 'New record',
            ]);
        }
    }

    /**
     * Updates an existing record.
     */
    public function actionUpdate($id)
    {
        $model = $this->findModel($id);

		if (Yii::$app->request->isPost) {
			$model->load(Yii::$app->request->post()) AND $model->save();
            $this->checkErrorsAndDisplayResult($model);
        } else {
            return $this->renderJson('_form', [
                'model' => $model,
				'modelName' => StringHelper::basename($model::className()),
				'formTitle' => 'Edit record',
            ]);
        }
    }

    /**
     * Deletes an existing record.
     */
    public function actionDelete()
    {
		$modelName = StringHelper::basename(Model::className());
		$param = Yii::$app->request->post($modelName);
		$id = isset($param['id']) ? $param['id'] : null;
        $model = $this->findModel($id);
		$model->delete();
        $this->checkErrorsAndDisplayResult($model);
    }

    /**
     * Deletes selected records.
     */
    public function actionDeleteSelected()
    {
		$modelName = StringHelper::basename(Model::className());
		$param = Yii::$app->request->post($modelName);
		$ids = (isset($param['ids']) AND is_array($param['ids'])) ? $param['ids'] : array();
		$resultModel = false;
		foreach($ids as $id) {
			$model = $this->findModel($id);
			$model->delete();
			if ( ! $resultModel AND $model->hasErrors())
				$resultModel = clone $model;
		}
		$resultModel = $resultModel ? $resultModel : new Model;
        $this->checkErrorsAndDisplayResult($resultModel);
    }

    /**
     * Search records.
     */
    public function actionSearch()
    {
        $searchModel = new SearchModel();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);

		if ( ! $searchModel->hasErrors()) {
			return $this->renderJson('_table', [
				'searchModel' => $searchModel,
				'dataProvider' => $dataProvider,
				'models' => $dataProvider->getModels(),
				'pagerInfo' => \alhimik1986\yii2_crud_module\web\Pager::getPagerInfo(Yii::$app->request->queryParams, $dataProvider->totalCount),
			]);
		} else {
			\alhimik1986\yii2_crud_module\web\JsonController::checkErrorsAndDisplayResult($searchModel);
		}
    }

    /**
     * Finds records based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     */
    protected function findModel($id)
    {
        if (($model = Model::findOne($id)) !== null) {
            return $model;
        } else {
            throw new \yii\web\NotFoundHttpException(<?php echo $generator->enableI18N ? "Yii::t('app', 'The requested page does not exist.')" : "'The requested page does not exist.'"; ?>);
        }
    }
}