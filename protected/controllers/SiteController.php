<?php

class SiteController extends Controller
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
			// page action renders "static" pages stored under 'protected/views/site/pages'
			// They can be accessed via: index.php?r=site/page&view=FileName
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
		// renders the view file 'protected/views/site/index.php'
		// using the default layout 'protected/views/layouts/main.php'
		$this->render('index');
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

	/**
	 * Action, реализующий процесс авторизации
	 */
	public function actionLogin()
	{
		if(Yii::app()->user->isGuest)
		{
			$model=new LoginModel;

			// if it is ajax validation request
			if(isset($_POST['ajax']) && $_POST['ajax']==='login-form')
			{
				echo CActiveForm::validate($model);
				Yii::app()->end();
			}

			// Проверка наличия данных в ссылке авторизации
			if(isset($_GET))
			{
                //Сбор данных из ссылки
				$model->attributes=$_GET;
				// Валидация модели и авторизация
				if($model->validate() && $model->login())
				{
					//Перенаправление в личный кабинет
					$this->redirect('index.php?site/account');
				}
			}
            //Перенаправление на страницу с ошибкой
			$this->redirect('index.php?r=site/page&view=urlError');
		}
		else
		{
			//Перенаправление в личный кабинет
			$this->redirect('index.php?site/account');
		}
        
        //Перенаправление в личный кабинет
        $this->redirect('index.php?site/account');
	}

	/**
	 * Action, реализующий выход из учетной записи
	 */
	public function actionLogout()
	{
        //Находим модель пользователя по текущему id
        $user = Users::model()->findByAttributes(array('id' => Yii::app()->user->id));
        //Выход из учетной записи
        Yii::app()->user->logout();
        //Устанавливаем дату последнего визита пользователя
        $user->setLastVisit();
        //Сохраняем данные в БД
        $user->save();
        //Перенаправление на домашнюю страницу
		$this->redirect(Yii::app()->homeUrl);
	}
	
	/**
	 * Action, личный кабинет
	 */
	public function actionAccount()
	{
        //Находим модель пользователя по текущему id
		$user = Users::model()->findByAttributes(array('id' => Yii::app()->user->id));
        //Создаем экземпляр класса модели истории баланса пользователя
		$balanceHistory = new BalanceHistory();
        //Берем последние 10 операций баланса пользователя по текущему id
        $lastOperations = $balanceHistory->getLast10(Yii::app()->user->id);
		//Если пользователь авторизован
		if(!Yii::app()->user->isGuest)
		{
            //Выводим страницу с учетной записью
			$this->render('account',array('model'=>$user, 'balance' => $lastOperations));
		}
		else
		{
            //Перенаправление на страницу регистрации
			$this->redirect('index.php?r=site/register');
		}
	}
	
	/**
	* Action, реализующий процесс регистрации
	*/
	
	public function actionRegister()
	{
        //Если пользователь не авторизован
		if(Yii::app()->user->isGuest)
		{
			$model = new RegisterForm;
			
			if(isset($_POST['ajax']) && $_POST['ajax']==='login-form')
			{
				echo CActiveForm::validate($model);
				Yii::app()->end();
			}

			// collect user input data
			if(isset($_POST['RegisterForm']))
			{
				
				$model->attributes=$_POST['RegisterForm'];
				// Валидация входных данных для модели
				if($model->validate())
					//$this->redirect(Yii::app()->user->returnUrl);
					//Создаем экземпляр класса модели ActivationLink
					$link = new ActivationLink();
					//Загружаем аттрибуты
					$link->attributes=$_POST['RegisterForm'];
					//Удаляем все ссылки из БД, перед формированием новой ссылки
					$link->deleteLinks();
					//Формируем новую ссылку
					$link->generateLink();
					//Сохраняем в БД
					$link->save();
					//Отправляем по почте
					$link->sendByEmail();
					//Перенаправление на страницу об успешной регистрации
					$this->redirect('index.php?r=site/page&view=registered');
			}
			// выводим форму регистрации
			$this->render('register',array('model'=>$model));
		}
		else
		{
			//Перенаправление в личный кабинет
			$this->redirect('index.php?r=site/account');
		}
	}
    
    /**
	* Action, реализующий процесс редактирования данных учетной записи
	*/
    
    public function actionEdit()
    {
        //Если пользователь авторизован
        if(!Yii::app()->user->isGuest)
		{
			$model = new Users();
			
			if(isset($_POST['ajax']) && $_POST['ajax']==='account-form')
			{
				echo CActiveForm::validate($model);
				Yii::app()->end();
			}

            // collect user input data
			if(isset($_POST['Users']))
			{
                //Находим модель пользователя по текущему id
                $model = Users::model()->findByAttributes(array('id' => Yii::app()->user->id));
				//Загружаем данные
                $model->attributes=$_POST['Users'];
				// Валидация входных данных для модели
				if($model->validate())
                    //Устанавливаем дату изменения данных пользователя
					$model->setUpdatedAt();
					//Сохраняем в БД
					$model->save();
			}
			// Перенаправляем страницу аккаунта
			$this->redirect('index.php?r=site/account');
		}
		else
		{
			//Перенаправление на страницу регистрации
			$this->redirect('index.php?r=site/register');
		}
    }

}