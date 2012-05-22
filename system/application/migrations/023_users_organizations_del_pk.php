<?php defined("BASEPATH") or die("No direct access to script");


/**
 *
 * @package default
 * @author alex.strigin 
 **/
class Migration_Users_organizations_del_pk extends CI_Migration
{

	public function up()
	{
		$this->dbforge->drop_column('users_organizations','id');
	}

	public function down()
	{
		$this->dbforge->add_column('users_organizations',array(
			'id' => array(
				'type' => 'INT',
				'unsigned' => true,
				'auto_increment' => true
			)
		));
	}
} // END class Migration_Settings_org extends CI_Migration