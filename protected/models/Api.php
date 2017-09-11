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
	
    /**
     * Метод для проверки ключа API в БД
	 * @return boolean true|false
	 */
    
	public function keyIsValid()
	{
		//Получеам пользователя по ключу API
		$user = Users::model()->findByAttributes(array('apiLink' => $this->apiKey));
		//Если пользователь существует
		if($user!== NULL)
		{
            //Если API-ключ существует
			if($user->apiLink !== NULL)
			{
                //присваиваем к текущему экземпляру API данного пользователя
				$this->user = $user;
                //Ключ валидный
				return true;
			}
			else
			{
                //Иначу ключ не корректен
				return false;
			}
		}
		
		return false;
		
	}
	
    /**
     * Метод для запуска операции API
	 * @return nothing
	 */
    
	public function execOperation()
	{
		//Переключатель на выбранную операцию
		switch($this->operation)
		{
			case API::$WRITE_OFF_BALANCE: $this->WRITE_OFF_BALANCE(); break;
            //Если операция не найдена, возвращаем ошибку
			default: $this->message = "Ошибка: Операции " . $this->operation. " не существует"; break;
		}
		
	}
    
	 /**
     * Метод API для списания суммы
	 * @return nothing
	 */	
	public function WRITE_OFF_BALANCE()
	{
        
        //Создаем модель истории баланса
		$balance = new BalanceHistory();
        //Переводим число в положительное
        $this->value = $this->value < 0 ? (-$this->value) : $this->value;
		//Если сумма это дробное число
        if(!is_float(floatval($this->value)))
        {
            $this->message = "Ошибка: Параметр суммы не является дробным числом";
        }
        //Если баланс не нулевой
		else if(floatval($this->user->balance) <= 0.00)
		{
			$this->message = "Ошибка: Недостаточно средств на счете";
		}
        //если сумма списания больше суммы баланса
		else if(floatval($this->value) > floatval($this->user->balance))
		{
			$this->message = "Ошибка: Превышена допустимая сумма вывода";
		}
        //иначе
		else
		{
            //Производим операцию списания
			$balance->makeOperation($this->user->id, $this->value);
            //выводим сообщение об успешно провденной операции списания
			$this->message = "Успешно: Сумма в размере ". $this->value ." списана со счета";
		}

	}
    
     /**
     * Метод API для вывода сообщений в формате JSON
	 * @return string сообщение в формате JSON
	 */
	
	public function responseJSON()
	{
		return CJSON::encode($this->message);
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
