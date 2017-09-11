<?php

/**
 * Класс модели для простого демо JSON API.
 */
class API extends CModel
{
	//Экземпляр класса Users
	public $user;
	//API-ключ
	public $apiKey;
	//Операция
	public $operation;
	//Значение
	public $value;
	//Сообщение от API
	public $message;
	
	private static $WRITE_OFF_BALANCE = "WOFFB";

	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		// NOTE: you should only define rules for those attributes that
		// will receive user inputs.
		return array(
			array('apiKey, operation, value', 'required'),
			array('apiKey', 'length', 'max'=>180),
		);
	}


	/**
	 * @return array customized attribute labels (name=>label)
	 */
	public function attributeNames()
	{
		return array(
			'apiKey' => 'API-ключ',
			'operation' => 'Операция',
			'value' => 'Значение',
		);
	}
	
	public function keyIsValid()
	{
		//Получеам пользователя по ключу API
		$user = Users::model()->findByAttributes(array('apiLink' => $this->apiKey));
		
		if($user!== NULL)
		{
			if($user->apiLink !== NULL)
			{
				$this->user = $user;
				return true;
			}
			else
			{
				return false;
			}
		}
		
		return false;
		
	}
	
	public function execOperation()
	{
		
		switch($this->operation)
		{
			case API::$WRITE_OFF_BALANCE: $this->WRITE_OFF_BALANCE();
			default: $this->message = "Ошибка: Операции " . $this->operation. " не существует";
		}
		
	}
		
	public function WRITE_OFF_BALANCE()
	{

		$balance = new BalanceHistory();
		
		if(floatval($this->user->balance) <= 0.00)
		{
			$this->message = "Ошибка: Недостаточно средств на счете";
		}
		else if(floatval($this->value) >= floatval($this->user->balance))
		{
			$this->message = "Ошибка: Превышена допустимая сумма вывода";
		}
		else
		{
			$balance->makeOperation($this->user->id, $this->value);
			$this->message = "Успешно: Сумма в размере ". $this->value ." списана со счета";
		}

	}
	
	public function responseJSON()
	{
		echo CJSON::encode($this->message);
	}
	
	/**
	 * Returns the static model of the specified AR class.
	 * Please note that you should have this exact method in all your CActiveRecord descendants!
	 * @param string $className active record class name.
	 * @return API the static model class
	 */
	public static function model($className=__CLASS__)
	{
		return parent::model($className);
	}
}
