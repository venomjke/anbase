<?php defined("BASEPATH") or die("No direct access to script");


/*
*
*	@author Alex.strigin
*	@company Flyweb
*
*/
class Migration_Org_info extends CI_Migration{

	/*
	*
	*	@author Alex.strigin
	*	
	*		Добавление таблицы managers_users
	*/
	public function up(){
		$this->dbforge->add_column('organizations',array(
			'email' => array(
				'type' => 'VARCHAR',
				'constraint' => '100'
			),
			'phone' => array(
				'type' => 'VARCHAR',
				'constraint' => '20'
			)
		));
	}
	public function down(){
		$this->dbforge->drop_column('organizations','email');
		$this->dbforge->drop_column('organizations','phone');
	}
}