<?php
/**
 * Имеет методы для работы с ajaxForm.
 * 
 * @author Сидорович Николай <sidorovich21101986@mail.ru>
 * @link https://github.com/alhimik1986
 * @copyright Copyright &copy; 2016
 */

namespace alhimik1986\yii2_crud_module\web;

use Yii;
use yii\helpers\StringHelper;

class JsonController extends \yii\web\Controller
{
	/**
	 *  Проверить ошибоки валидации и вывести в формате JSON: если успех - удачно записанные данные, если ошибка - ошибки.
	 */
	public static function checkErrorsAndDisplayResult($model, $result='ok')
	{
		if(Yii::$app->request->isAjax) {
			$messages = array();
			foreach(Yii::$app->session->getAllFlashes() as $type=>$message)
				$messages[][$type] = $message;
			
			$className = StringHelper::basename($model::className());
			
			if ($model->hasErrors()) {
				return json_encode(array(
					'status'   => 'error',
					'content'  => array($className => $model->getErrors()),
					'messages' => $messages,
				));
			} else {
				return json_encode(array(
					'status'   => 'success',
					'content'  => $result,
					'messages' => $messages,
				));
			}
		} else {
			if ($model->hasErrors()) {
				foreach($model->getErrors() as $key=>$errors) {
					foreach($errors as $error) {
						Yii::$app->session->setFlash('error', $model->getAttributeLabel($key).': '.$error);
					}
				}
			} else {
				Yii::$app->controller->redirect(Yii::$app->request->getReferrer());
			}
		}
	}


	/**
	 * Вывести данные в формате JSON
	 */
	public function renderJson($view, $params=array(), $getContent=false)
	{
		$result = null;
		
		if(Yii::$app->request->isAjax) {
			$messages = array();
			$content = $this->renderPartial($view, $params, true);
			foreach(Yii::$app->session->getAllFlashes() as $type=>$message)
				$messages[][$type] = $message;

			$result = json_encode(array(
				'status'   => 'success',
				'content'  => $content,
				'messages' => $messages,
			));
		} else {
			$result = $this->render($view, $params, true);
		}
		
		if ($getContent) {
			return $result;
		} else {
			return $result;
		}
	}


	/**
	 * Ответ в формате JSON
	 */
	public function echoJson($view, $params=array())
	{
		if(Yii::$app->request->isAjax) {
			$messages = array();
			$content = $this->renderPartial($view, $params, true);
			foreach(Yii::$app->session->getAllFlashes() as $type=>$message)
				$messages[][$type] = $message;

			return json_encode(array(
				'status'   => 'success',
				'content'  => $content,
				'messages' => $messages,
			));
		} else {
			$this->renderPartial($view, $params);
		}
	}


	/**
	 * Вывести ошибку в формате JSON
	 */
	public function renderJsonError($view, $params=array())
	{
		if(Yii::$app->request->isAjax) {
			$messages = array();
			$content = array(array(array($this->renderPartial($view, $params, true))));
			foreach(Yii::$app->session->getAllFlashes() as $type=>$message) $messages[][$type] = $message;
			return json_encode(array(
				'status'   => 'error',
				'content'  => $content,
				'messages' => $messages,
			));
		} else {
			$this->render($view, $params);
		}
	}


	/**
	 * Вывести ошибку в формате JSON
	 * @param string $text Текст ошибки.
	 * @param boolean $throwException Бросать исключение (завершать приложение) при выводе сообщения.
	 * @param integer $httpStatusCode Код ошибки (если не ajax-сообщение), такой как (404, 500 и т.д.).
	 */
	public function echoJsonError($text='', $throwException=false, $httpStatusCode='')
	{
		if(Yii::$app->request->isAjax) {
			$text = ($text != '') ? array(array(array($text))) : '';
			$messages = array();
			foreach(Yii::$app->session->getAllFlashes() as $type=>$message) $messages[][$type] = $message;
			return json_encode(array(
				'status'   => 'error',
				'content'  => $text,
				'messages' => $messages,
			));

			if ($throwException)
				Yii::$app->end();
		} else {
			if ($throwException)
				throw new \yii\web\HttpException($httpStatusCode, $text);
			else
				return $text;
		}
	}
}