<?php

/**
 * UserIdentity represents the data needed to identity a user.
 * It contains the authentication method that checks if the provided
 * data can identity the user.
 */

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
                //Устанавливаем дату последнего визита ля пользователя
				$user->setLastVisit();
                $user->save();
			}
			//Авторизуем пользователя
			$this->errorCode=self::ERROR_NONE;	
            //Удаляем все ссылки авторизации в БД для данного пользователя
			$activationLink->deleteLinks();
		}
    }
 
    public function getId()
    {
        return $this->_id;
    }
}