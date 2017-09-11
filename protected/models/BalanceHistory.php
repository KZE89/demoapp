<?php

/**
 * This is the model class for table "balanceHistory".
 *
 * The followings are the available columns in table 'balanceHistory':
 * @property integer $id
 * @property integer $userId
 * @property string $value
 * @property string $operationDateTime
 */
class BalanceHistory extends CActiveRecord
{
	/**
	 * @return string the associated database table name
	 */
	public function tableName()
	{
		return 'balanceHistory';
	}

	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		// NOTE: you should only define rules for those attributes that
		// will receive user inputs.
		return array(
			array('userId, value, operationDateTime', 'required'),
			array('userId', 'numerical', 'integerOnly'=>true),
			array('value', 'length', 'max'=>15),
			// The following rule is used by search().
			// @todo Please remove those attributes that should not be searched.
			array('id, userId, value, operationDateTime', 'safe', 'on'=>'search'),
		);
	}

	/**
	 * @return array relational rules.
	 */
	public function relations()
	{
		// NOTE: you may need to adjust the relation name and the related
		// class name for the relations automatically generated below.
		return array(
		);
	}

	/**
	 * @return array customized attribute labels (name=>label)
	 */
	public function attributeLabels()
	{
		return array(
			'id' => 'ID',
			'userId' => 'User',
			'value' => 'Value',
			'operationDateTime' => 'Operation Date Time',
		);
	}
	
	/**
	 * Проводит операции с балансом пользователя.
	 * @param integer $userId ID пользователя.
	 * @param decimal $value сумма операции (положительная прибавление, отрицательная убавление баланса).
	 */
	
	public function makeOperation($userId, $value)
	{
		//Регистрации операции в логе (истории) операций
		$this->userId = $userId;
		$this->value = (-$value);
		$this->operationDateTime = date("Y-m-d H:i:s");
		$this->save();
		//Изменение баланса пользователя
		$user = Users::model()->findByAttributes(array('id' => $userId));
		$user->balance -= $value;
		$user->save();
	}
    
    
    	/**
	 * Возвращает 10 последних операций по балансу пользователя.
	 * @param integer $userId ID пользователя.
	 * @param decimal $value сумма операции (положительная прибавление, отрицательная убавление баланса).
     * @return BalanceHistory model class or false
	 */
	
	public function getLast10($userId)
	{
		//Поиск операций по балансу пользователя по его ID
		$criteria=new CDbCriteria;
		$criteria->select='*'; 
		$criteria->condition='userId=:userId';
		$criteria->params=array(':userId'=>$userId);
        $criteria->limit = 10;
        $criteria->offset = 0;
        $criteria->order = 'operationDateTime DESC';
		$balanceHistory = BalanceHistory::model()->findAll($criteria);
		
		if($balanceHistory === NULL)
		{
			return false;
		}
		
		return $balanceHistory;
	}

	/**
	 * Retrieves a list of models based on the current search/filter conditions.
	 *
	 * Typical usecase:
	 * - Initialize the model fields with values from filter form.
	 * - Execute this method to get CActiveDataProvider instance which will filter
	 * models according to data in model fields.
	 * - Pass data provider to CGridView, CListView or any similar widget.
	 *
	 * @return CActiveDataProvider the data provider that can return the models
	 * based on the search/filter conditions.
	 */
	public function search()
	{
		// @todo Please modify the following code to remove attributes that should not be searched.

		$criteria=new CDbCriteria;

		$criteria->compare('id',$this->id);
		$criteria->compare('userId',$this->userId);
		$criteria->compare('value',$this->value,true);
		$criteria->compare('operationDateTime',$this->operationDateTime,true);

		return new CActiveDataProvider($this, array(
			'criteria'=>$criteria,
		));
	}

	/**
	 * Returns the static model of the specified AR class.
	 * Please note that you should have this exact method in all your CActiveRecord descendants!
	 * @param string $className active record class name.
	 * @return BalanceHistory the static model class
	 */
	public static function model($className=__CLASS__)
	{
		return parent::model($className);
	}
}
