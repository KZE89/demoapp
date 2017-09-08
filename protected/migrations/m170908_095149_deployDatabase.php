<?php

class m170908_095149_deployDatabase extends CDbMigration
{
	public function up()
	{
        $this->createTable('users', array(
            'id' => 'pk',
            'name' => 'nvarchar(40) NOT NULL',
            'email' => 'nvarchar(40) NOT NULL',
            //'password' => $this->string(40),
            'balance' => 'decimal(15,2) NOT NULL',
            'activationLink' => 'nvarchar(64) NOT NULL',
            'apiLink' => 'nvarchar(96) NOT NULL',
            'lastVisit' => 'datetime NOT NULL',
            'updatedAt' => 'datetime NOT NULL',
            'registiredAt' => 'datetime NOT NULL',
            
        ));
        
        $this->alterColumn('users', 'id', 'integer(8)'.' NOT NULL AUTO_INCREMENT');
        
        $this->createTable('balanceHistory', array(
            'id' => 'pk',
            'userId' => 'integer(8) NOT NULL',
            'value' => 'decimal(15,2) NOT NULL',
            'operationDateTime' => 'datetime NOT NULL',
            
        ));
        
        $this->alterColumn('balanceHistory', 'id', 'integer(8)'.' NOT NULL AUTO_INCREMENT');
	}

	public function down()
	{
		$this->dropTable('users');
	}
}