<?php

/**
 * LoginModel class.
 * LoginForm is the data structure for keeping
 * user login form data. It is used by the 'login' action of 'SiteController'.
 */
class LoginModel extends CModel
{
	public $email;
	public $hashString;

	private $_identity;

	/**
	 * Declares the validation rules.
	 * The rules state that username and password are required,
	 * and password needs to be authenticated.
	 */
	public function rules()
	{
		return array(
			// username and password are required
			array('email, hashString', 'required'),
			array('email', 'length', 'max'=>40),
			array('hashString', 'length', 'max'=>120),
		);
	}
	
	/**
	 * Declares attribute labels.
	 */
	public function attributeNames()
	{
		return array(
			'email' => 'E-mail-адрес',
			'hashString' => 'Хеш-строка',
		);
	}

	/**
	 * Метод, производящий процесс авторизации и аутентификации (входа) на сайт
	 * @return boolean true|false
	 */
	public function login()
	{
		if($this->_identity===null)
		{
			$this->_identity=new UserIdentity('', '');
			$this->_identity->email = $this->email;
			$this->_identity->hashString = $this->hashString;
			
			$this->_identity->authenticate();
		}
		if($this->_identity->errorCode===UserIdentity::ERROR_NONE)
		{
			$duration=3600*24*30; // 30 days
			Yii::app()->user->login($this->_identity,$duration);
			return true;
		}
		else
			return false;
	}
}
