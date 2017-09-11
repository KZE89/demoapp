<?php

/**
 * UserIdentity represents the data needed to identity a user.
 * It contains the authentication method that checks if the provided
 * data can identity the user.
 */

/*
class UserIdentity extends CUserIdentity
{
	public function authenticate()
	{
		$users=array(
			// username => password
			'demo'=>'demo',
			'admin'=>'admin',
		);
		if(!isset($users[$this->username]))
			$this->errorCode=self::ERROR_USERNAME_INVALID;
		elseif($users[$this->username]!==$this->password)
			$this->errorCode=self::ERROR_PASSWORD_INVALID;
		else
			$this->errorCode=self::ERROR_NONE;
		return !$this->errorCode;
	}
}*/

/*Класс авторизации*/

class UserIdentity extends CUserIdentity
{
    private $_id;
	public $email;
	public $hashString;
	
    public function authenticate()
    {
		//Поиск ссылки авторизации
		$activationLink = ActivationLink::model()->findByAttributes(array('email' => $this->email, 'hashString' => $this->hashString));
		//Поиск пользователя
        $user = Users::model()->findByAttributes(array('email' => $this->email));
		
		//Если ссылка не найдена
		if($activationLink === NULL)
		{
			//Не авторизуем
			return NULL;
		}
		else
		{
			//Если у пользователя нет аккаунта
			if($user === NULL)
			{
				//Создаем нового пользователя
				$newUser = new Users();
				$newUser->createNewUser($this->email);
				$this->_id = $newUser->id;
			}
			else
			{
				$this->_id = $user->id;
				$user->setLastVisit();
			}
			//Авторизуем пользователя
			$this->errorCode=self::ERROR_NONE;	
			$activationLink->deleteLinks();
		}
    }
 
    public function getId()
    {
        return $this->_id;
    }
}