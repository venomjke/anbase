<?php defined("BASEPATH") or die("No direct access to script");

/**
 * @package default
 * @author alex.strigin
 **/
class Migration_Invites_role_refactoring extends CI_Migration
{
	public function up()
	{
		$this->dbforge->add_column('invites_users',array(
			'role_new' => array(
				'type' => 'TINYINT',
				'unsigned' => true,
				'default' => 0x00
			)
		));
		/*
		* Заменяем все Менеджер, Админ, Агент на соотв.
		* Админ -> 0x01, Менеджер -> 0x02, Агент -> 0x03
		*/	
		$this->db->where('role','Админ');
		$this->db->update('invites_users',array(
			'role_new' => 0x01
		));

		$this->db->where('role','Менеджер');
		$this->db->update('invites_users',array(
			'role_new' => 0x02
		));

		$this->db->where('role','Агент');
		$this->db->update('invites_users',array(
			'role_new' => 0x03
		));

		$this->dbforge->drop_column('invites_users','role');

		$this->dbforge->modify_column('invites_users',array(
			'role_new' => array(
				'name' => 'role',
				'type' => 'TINYINT',
				'unsigned' => true,
				'default' => 0x00
			)
		));
	}

	public function down()
	{
		/*
		* Опускаться в низ не советовал бы.
		*/
	}
} // END class Migration_Users_role_refactoring