<?php

namespace alhimik1986\yii2_crud_module\Module;
use Yii;
use yii\di\Container;

/**
 * yii2_crud_module module definition class
 */
class Module extends \yii\base\Module
{
    /**
     * @inheritdoc
     */
    public $controllerNamespace = 'alhimik1986\yii2_crud_module\Module\controllers';

	//public $defaultRoute = 'setting';
	public $allowedIPs = ['127.0.0.1', '::1'];
	public $userModule; // Компонент пользователя (для этого модуля)
	public $l18n_base_path = null;

    public function init()
    {
        parent::init();
		$this->registerTranslations();
    }
	protected static function namespace_str()
	{
		return str_replace('\\', '/', __NAMESPACE__);
	}

    public function registerTranslations()
    {
        Yii::$app->i18n->translations[self::namespace_str().'/*'] = [
            'class' => 'yii\i18n\PhpMessageSource',
            'sourceLanguage' => 'en-US',
            'basePath' => $this->l18n_base_path ? $this->l18n_base_path : __DIR__ .'/messages',
            'fileMap' => [
                self::namespace_str().'/app' => 'messages.php',
            ],
        ];
    }

	/**
	 * Использование интернационализации (перевода) внутри модуля.
	 * Пример использования: use alhimik1986\yii2-settings-module\Module; Module::t('app', 'The data is not found!');
	 */
    public static function t($category, $message, $params = [], $language = null)
    {
        return Yii::t(self::namespace_str() .'/'. $category, $message, $params, $language);
    }
}
