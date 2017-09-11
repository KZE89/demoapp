<?php

class ApiController extends Controller
{
	/**
	 * Declares class-based actions.
	 */
	public function actions()
	{
		return array(
			// captcha action renders the CAPTCHA image displayed on the contact page
			'captcha'=>array(
				'class'=>'CCaptchaAction',
				'backColor'=>0xFFFFFF,
			),
			// page action renders "static" pages stored under 'protected/views/api/pages'
			// They can be accessed via: index.php?r=api/page&view=FileName
			'page'=>array(
				'class'=>'CViewAction',
			),
		);
	}

	/**
	 * This is the default 'index' action that is invoked
	 * when an action is not explicitly requested by users.
	 */
	public function actionIndex()
	{
		header('Content-type: application/json');

		if(isset($_GET))
		{
				$API = new API();
				$API->attributes=$_GET;

				// Валидация модели и авторизация
				if($API->validate() )
				{
					if($API->keyIsValid())
					{
						//var_dump($API);
						$API->execOperation();
						$API->responseJSON();
					}
					else
					{
						$API->message = "Ошибка: не действительный ключ API";
					}
				}
				else
				{
					$API->message = "Ошибка: Не корректные параметры запроса к API";
				}
		}
		else
		{
			$API->message = "Ошибка: Не верный запрос к API";
		}
		
		

		Yii::app()->end();
	}

	/**
	 * This is the action to handle external exceptions.
	 */
	public function actionError()
	{
		if($error=Yii::app()->errorHandler->error)
		{
			if(Yii::app()->request->isAjaxRequest)
				echo $error['message'];
			else
				$this->render('error', $error);
		}
	}
}