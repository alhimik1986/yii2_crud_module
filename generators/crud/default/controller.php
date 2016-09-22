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
		
        return $this->render('index', [
			'searchModel' => $searchModel,
			'model' => new Model,
			//'dataProvider' => $searchModel->search(Yii::$app->request->queryParams),
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
				'formTitle' => 'Edit record',
            ]);
        }
    }

    /**
     * Deletes an existing record.
     */
    public function actionDelete()
    {
		$param = Yii::$app->request->post(basename(Model::className()));
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
		$param = Yii::$app->request->post(basename(Model::className()));
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