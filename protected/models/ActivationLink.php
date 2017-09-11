<?php

/**
 * This is the model class for table "activationLinks".
 *
 * The followings are the available columns in table 'activationLinks':
 * @property integer $id
 * @property string $email
 * @property string $hashString
 * @property string $createdAt
 */
class ActivationLink extends CActiveRecord
{
	
	private static $site = 'http://demoapp/index.php?r=site/login&hashString=';
	
	/**
	 * @return string the associated database table name
	 */
	public function tableName()
	{
		return 'activationLinks';
	}

	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		// NOTE: you should only define rules for those attributes that
		// will receive user inputs.
		return array(
			array('hashString, createdAt, email', 'required'),
			array('hashString', 'length', 'max'=>120),
			array('email', 'length', 'max'=>40),
			// The following rule is used by search().
			// @todo Please remove those attributes that should not be searched.
			array('id, email, hashString, createdAt', 'safe', 'on'=>'search'),
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
			'email' => 'E-mail-адрес',
			'hashString' => 'Хеш-строка',
			'createdAt' => 'Создано',
		);
	}
	
	/**
	 * Генерирует данные для модели ссылки активации и присваивает их текущему экземпляру класса.
	 * @param string $email E-mail адрес пользователя.
	 */
	
	public function generateLink()
	{
		$this->createdAt = date("Y-m-d H:i:s");
		$this->hashString = CPasswordHelper::hashPassword($this->email) . CPasswordHelper::hashPassword($this->createdAt);
	}
	
	/**
	 * Проверяет существует ли текущая ссылка активации с указанным адресом E-mail.
	 * @param string $email E-mail адрес пользователя.
	 * @param string $hashString хеш-строка для активации.
	 * @return bool true|false
	*/
	public function isExist($hashString)
	{
		$criteria=new CDbCriteria;
		$criteria->select='*'; 
		$criteria->condition='email=:email and hashString=:hashString';
		$criteria->params=array(':email'=>$email, ':hashString'=>$hashString);
		$link=ActivationLink::model()->find($criteria);
		
		if($link === NULL)
		{
			return false;
		}
		
		return true;
	}
	
	/**
	 * Удаляет все ссылки активации перед генерацией новой ссылки.
	 * @return bool true|false
	*/
	public function deleteLinks()
	{
		$criteria=new CDbCriteria;
		$criteria->select='*'; 
		$criteria->condition='email=:email';
		$criteria->params=array(':email'=>$this->email);
		ActivationLink::model()->deleteAll($criteria);
	}
	
	public function sendByEmail()
	{
		
		$to = $this->email;
		$subject = 'Ссылка для входа на сайт DemoApp';
		$message = 'Ссылка для входа на сайт DemoApp: ' . ActivationLink :: $site . $this->hashString .'&email=' . $this->email;
		$headers = 'From: webmaster@example.com' . "\r\n" .
			'Reply-To: webmaster@example.com' . "\r\n" .
			'X-Mailer: PHP/' . phpversion();

		mail($to, $subject, $message, $headers);
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
		$criteria->compare('email',$this->hashString,true);
		$criteria->compare('hashString',$this->hashString,true);
		$criteria->compare('createdAt',$this->createdAt,true);

		return new CActiveDataProvider($this, array(
			'criteria'=>$criteria,
		));
	}

	/**
	 * Returns the static model of the specified AR class.
	 * Please note that you should have this exact method in all your CActiveRecord descendants!
	 * @param string $className active record class name.
	 * @return ActivationLink the static model class
	 */
	public static function model($className=__CLASS__)
	{
		return parent::model($className);
	}
}
