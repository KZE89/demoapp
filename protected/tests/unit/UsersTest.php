<?php

class UsersTest extends CDbTestCase
{
	/**
	   * @var Users
	*/
	
    protected $users;

	public function rules()
	{
		return array(
			array('name, email, apiLink', 'required'),
			array('name, email', 'length', 'max'=>40),
			array('apiLink', 'length', 'max'=>180),
		);
	}    

	protected function setUp()
	{
		parent::setUp();
		$this->users = new Users();
	}
	
	/*Тест name является обязательным полем*/
	public function testNameIsRequired()
	{
		$this->users->name = '';
		$this->assertFalse($this->users->validate(array('name')));
	}
	/*Тест длина name не более 40 символов*/
	public function testNameMaxLengthIs40()
	{
	    $this->users->name = generateString(42);
	    $this->assertFalse($this->users->validate(array('name')));

	    $this->users->name = generateString(40);
	    $this->assertTrue($this->users->validate(array('name')));
	}
	/*Тест email является обязательным полем*/
	public function testEmailIsRequired()
	{
		$this->users->email = '';
		$this->assertFalse($this->users->validate(array('email')));
	}
	/*Тест длина email не более 40 символов*/
	public function testEmailMaxLengthIs40()
	{
	    $this->users->email = generateString(42);
	    $this->assertFalse($this->users->validate(array('email')));

	    $this->users->email = generateString(40);
	    $this->assertTrue($this->users->validate(array('email')));
	}
	
	/*Тест apiLink является обязательным полем*/
	public function testApiLinkIsRequired()
	{
		$this->users->apiLink = '';
		$this->assertFalse($this->users->validate(array('apiLink')));
	}
	/*Тест длина apiLink не более 96 символов*/
	public function testApiLinkMaxLengthIs120()
	{
	    $this->users->apiLink = generateString(182);
	    $this->assertFalse($this->users->validate(array('apiLink')));

	    $this->users->apiLink = generateString(180);
	    $this->assertTrue($this->users->validate(array('apiLink')));
	}

	//метод generateString(), генерирует строку с заданной длиной
	function generateString($length)
	{
	    $random= "";
	    srand((double)microtime()*1000000);
	    $char_list = "ABCDEFGHIJKLMNOPQRSTUVWXYZ";
	    $char_list .= "abcdefghijklmnopqrstuvwxyz";
	    $char_list .= "1234567890";

	    for($i = 0; $i < $length; $i++)
	    {
	        $random .= substr($char_list,(rand()%(strlen($char_list))), 1);
	    }
	    return $random;
	}

}
?>