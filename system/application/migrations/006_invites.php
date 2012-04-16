<?php defined("BASEPATH") or die("No direct access to script");

/**
 * Модификация таблицы invites_users
 *
 * @package default
 * @author 
 **/
class Migration_Invites extends CI_Migration
{
	/**
	 * 	модификация таблицы invites_users
	 *
	 * Убираем из первичного ключа key+org_id, добавляем просто id
	 * @return void
	 * @author alex.strigin
	 **/
	public function up()
	{

		$this->db->query('ALTER TABLE `invites_users` DROP PRIMARY KEY');

		$this->db->query('ALTER TABLE `invites_users` ADD id INT UNSIGNED NOT NULL AUTO_INCREMENT PRIMARY KEY');
		$this->dbforge->add_column('invites_users',array(
			'email' => array(
				'type'       => 'VARCHAR',
				'constraint' => '100'
			)
		));

		$this->db->query('CREATE INDEX idx_manager_id ON `invites_users`(manager_id)');
		$this->db->query('ALTER TABLE `invites_users` ADD FOREIGN KEY (`manager_id`) REFERENCES `users`(id) ON DELETE SET NULL ON UPDATE SET NULL');
	}


	/**
	 * 
	 *
	 * @return void
	 * @author alex.strigin
	 **/
	public function down()
	{
		$this->dbforge->drop_column('invites_users','email');
		$this->dbforge->drop_column('invites_users','id');
	}
} // END class Migration_Invties extends CI_Migration