<?php
/**
 * This is the template for generating CRUD search class of the specified model.
 */

use yii\helpers\StringHelper;


/* @var $this yii\web\View */
/* @var $generator yii\gii\generators\crud\Generator */

$modelClass = StringHelper::basename($generator->modelClass);
$searchModelClass = StringHelper::basename($generator->searchModelClass);
if ($modelClass === $searchModelClass) {
    $modelAlias = $modelClass . 'Model';
}
$rules = $generator->generateSearchRules();
$labels = $generator->generateSearchLabels();
$searchAttributes = $generator->getSearchAttributes();
$searchConditions = $generator->generateSearchConditions();

echo "<?php\n";
?>

namespace <?= StringHelper::dirname(ltrim($generator->modelClass, '\\')) ?>;

use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use <?= ltrim($generator->modelClass, '\\') . (isset($modelAlias) ? " as $modelAlias" : "") ?>;

/**
 * <?= $searchModelClass ?> represents the model behind the search form about `<?= $generator->modelClass ?>`.
 */
class <?= $searchModelClass ?> extends <?= isset($modelAlias) ? $modelAlias : $modelClass ?>

{
    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            <?= implode(",\n            ", $rules) ?>,
        ];
    }

    /**
     * @inheritdoc
     */
    public function scenarios()
    {
        // bypass scenarios() implementation in the parent class
        return Model::scenarios();
    }

    /**
     * Creates data provider instance with search query applied
     *
     * @param array $params
     *
     * @return ActiveDataProvider
     */
    public function search($params)
    {
		// Решаю проблему чувствительности символов к регистру в операторе LIKE для Sqlite.
		// Fix case sensitive in like operator in Sqlite. This line does not affect other types of databases.
		\alhimik1986\yii2_crud_module\web\ModelHelper::sqliteFixCaseSensitiveInLikeOperator(static::getDb());

        $query = <?= isset($modelAlias) ? $modelAlias : $modelClass ?>::find();

        // add conditions that should always apply here

        $dataProvider = new ActiveDataProvider([
            'query' => $query,
			// Пейджер и число записей на страницу
			// Sets the pager and results per page
			'pagination' => array('pageSize' => (isset($params['per-page']) AND (int)$params['per-page']) ? abs((int)$params['per-page']) : 10),
        ]);

        $this->load($params);

        if (!$this->validate()) {
            $query->where('0=1');
            return $dataProvider;
        }

        // grid filtering conditions
		$t = self::tableName();
        <?= implode("\n        ", $searchConditions) ?>

		// Сортировка по нескольким полям таблицы, и вывод результата в виде массива, а не в виде объектов (для экономии памяти)
		// Sorting by several fields of the table and return result as array, not as object (to prevent memory leaks).
		$query->orderBy(\alhimik1986\yii2_crud_module\web\Sort::init()->setOrderByDefault('')->getOrder($params, $this));
		$query->asArray();

        return $dataProvider;
    }
}