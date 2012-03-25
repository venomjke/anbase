<?php defined('BASEPATH') or exit('No direct script access allowed');

/*
*
*	@author Alex.strigin
*	@company Flyweb
*
*/
class Migration_Managers_users extends CI_Migration{


	/*
	*
	*	@author Alex.strigin
	*	
	*		Добавление таблицы managers_users
	*/
	public function up(){
		$this->dbforge->add_field(array(
			'id' => array(
				'type'     => 'INT',
				'unsigned' => true,
				'auto_increment' => true
			),
			'manager_id' => array(
				'type'     => 'INT',
				'unsigned' => true
			),
			'user_id' => array(
				'type'     => 'INT',
				'unsigned' => true
			)
		));

		$this->dbforge->add_key('id',true);

		if(!$this->dbforge->create_table("managers_users",true)){
			exit('Something goes wrong in Migration_managers_users after dbforge->create_table');
		}

		/*
		*
		*	Создание индексов и добавление внещних ключей
		*
		*/
		$this->db->query('CREATE INDEX idx_manager_id ON `managers_users`(manager_id)');
		$this->db->query('CREATE INDEX idx_user_id ON `managers_users`(user_id)');

		$this->db->query('ALTER TABLE `managers_users` ADD FOREIGN KEY (`manager_id`) REFERENCES `users`(id) ON DELETE CASCADE ON UPDATE CASCADE');
		$this->db->query('ALTER TABLE `managers_users` ADD FOREIGN KEY (`user_id`) REFERENCES `users`(id) ON DELETE CASCADE ON UPDATE CASCADE');
	}
	public function down(){
		$this->dbforge->drop_table('managers_users');
	}
}