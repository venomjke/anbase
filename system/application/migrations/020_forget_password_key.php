<?php defined("BASEPATH") or die("No direct access to script");


/**
 *
 * @package default
 * @author alex.strigin 
 **/
class Migration_Forget_password_key extends CI_Migration
{

	public function up()
	{
		$this->dbforge->add_column('users',array(
			'forget_password_key' => array(
				'type' => 'VARCHAR',
				'constraint' => '32'
			)
		));
	}

	public function down()
	{
		$this->dbforge->drop_column('users','forget_password_key');
	}
} // END class Migration_Settings_org extends CI_Migration