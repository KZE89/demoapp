<?php

/**
 * RegisterForm class.
 * RegisterForm Класс для регистрации пользоваелей
 */
class RegisterForm extends CFormModel
{
	public $email;

	/**
	 * Declares the validation rules.
	 */
	public function rules()
	{
		return array(
			array('email', 'required'),
			array('email', 'email'),
			//array('email', 'safe'),
		);
	}

	/**
	 * Declares attribute labels.
	 */
	public function attributeLabels()
	{
		return array(
			'email'=>'Адрес E-mail',
		);
	}
}
