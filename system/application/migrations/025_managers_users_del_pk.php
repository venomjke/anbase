<?php defined("BASEPATH") or die("No direct access to script");


/**
 *
 * @package default
 * @author alex.strigin 
 **/
class Migration_Managers_users_del_pk extends CI_Migration
{

	public function up()
	{
		$this->dbforge->drop_column('managers_users','id');
	}

	public function down()
	{
		$this->dbforge->add_column('managers_users',array(
			'id' => array(
				'type' => 'INT',
				'unsigned' => true,
				'auto_increment' => true
			)
		));
	}
} // END class Migration_Settings_org extends CI_Migration