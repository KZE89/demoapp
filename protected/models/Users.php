<?php

/**
 * This is the model class for table "users".
 *
 * The followings are the available columns in table 'users':
 * @property integer $id
 * @property string $name
 * @property string $email
 * @property string $balance
 * @property string $activationLink
 * @property string $apiLink
 * @property string $lastVisit
 * @property string $updatedAt
 * @property string $registiredAt
 */
class Users extends CActiveRecord
{
	/**
	 * @return string the associated database table name
	 */
	public function tableName()
	{
		return 'users';
	}

	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		// NOTE: you should only define rules for those attributes that
		// will receive user inputs.
		return array(
			array('name, email, balance, activationLink, apiLink, lastVisit, updatedAt, registiredAt', 'required'),
			array('name, email', 'length', 'max'=>40),
			array('balance', 'length', 'max'=>15),
			array('activationLink', 'length', 'max'=>64),
			array('apiLink', 'length', 'max'=>96),
			// The following rule is used by search().
			// @todo Please remove those attributes that should not be searched.
			array('id, name, email, balance, activationLink, apiLink, lastVisit, updatedAt, registiredAt', 'safe', 'on'=>'search'),
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
			'name' => 'Name',
			'email' => 'Email',
			'balance' => 'Balance',
			'activationLink' => 'Activation Link',
			'apiLink' => 'Api Link',
			'lastVisit' => 'Last Visit',
			'updatedAt' => 'Updated At',
			'registiredAt' => 'Registired At',
		);
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
		$criteria->compare('name',$this->name,true);
		$criteria->compare('email',$this->email,true);
		$criteria->compare('balance',$this->balance,true);
		$criteria->compare('activationLink',$this->activationLink,true);
		$criteria->compare('apiLink',$this->apiLink,true);
		$criteria->compare('lastVisit',$this->lastVisit,true);
		$criteria->compare('updatedAt',$this->updatedAt,true);
		$criteria->compare('registiredAt',$this->registiredAt,true);

		return new CActiveDataProvider($this, array(
			'criteria'=>$criteria,
		));
	}

	/**
	 * Returns the static model of the specified AR class.
	 * Please note that you should have this exact method in all your CActiveRecord descendants!
	 * @param string $className active record class name.
	 * @return Users the static model class
	 */
	public static function model($className=__CLASS__)
	{
		return parent::model($className);
	}
}
