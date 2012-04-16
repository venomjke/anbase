<?php defined('BASEPATH') or exit('No direct script access allowed');

/*
*
*	@author Alex.strigin
*	@company Flyweb
*
*/
class Migration_Add_user extends CI_Migration{


	/*
	*
	*	@author Alex.strigin
	*	
	*		Добавление таблицы users
	*/
	public function up(){


		/*
		*
		*	Добавление таблицы sessions
		*
		*/
		$this->dbforge->add_field("`session_id` varchar(40) COLLATE utf8_bin NOT NULL DEFAULT '0'");
		$this->dbforge->add_field("`ip_address` varchar(16) COLLATE utf8_bin NOT NULL DEFAULT '0'");
		$this->dbforge->add_field("`user_agent` varchar(150) COLLATE utf8_bin NOT NULL");
		$this->dbforge->add_field("`last_activity` int(10) unsigned NOT NULL DEFAULT '0'");
		$this->dbforge->add_field(" `user_data` text COLLATE utf8_bin NOT NULL");
		$this->dbforge->add_key("session_id",TRUE);

		if(!$this->dbforge->create_table('ci_sessions',TRUE)){
			exit('Something goes wrong in Migration_add_user after dbforge->create_table');
		}


		/*
		*
		*	Добавление таблицы users
		*
		*/
		$users_fields = array(
			'id' => array(
				'type' => 'INT',
				'constraint' => '9',
				'unsigned' => true,
				'auto_increment' => true
			),

			'login' => array(
				'type' => 'varchar',
				'constraint' => '50'
			),
			'email'	=> array(
				'type' => 'varchar',
				'constraint' => '100',
			),

			'activated' => array(
				'type' => 'TINYINT',
				'constraint' => '1',
				'default' => '1',
				'unsigned' => true
			),

			'name' => array(
				'type' => 'VARCHAR',
				'constraint' => '15',
			),

			'middle_name' => array(

				'type' => 'VARCHAR',
				'constraint' => '15'
			),

			'last_name' => array(
				'type' => 'VARCHAR',
				'constraint' => '15'
			),

			'phone' => array(
				'type' => 'VARCHAR',
				'constraint' => '20'
			),

			'role' => array(
				'type' => 'ENUM',
				'constraint' =>"'Админ','Менеджер','Агент'",
				'default' => 'Агент'
			),

			'created' => array(
				'type' => 'datetime',
				'default' => '0000-00-00 00:00:00'
			),

			'last_login' => array(
				'type' => 'datetime',
				'default' => '0000-00-00 00:00:00'
			)
		);
		$this->dbforge->add_field($users_fields);

		$this->dbforge->add_field('`last_ip` VARCHAR(40) COLLATE utf8_bin NOT NULL');
		$this->dbforge->add_field("`modifed` TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP ");
		$this->dbforge->add_field("`password` varchar(255) COLLATE utf8_bin NOT NULL");
		$this->dbforge->add_key('id',true);
		if(!$this->dbforge->create_table("users",true)){
			exit('Something goes wrong in Migration_add_user after dbforge->create_table');

		}
		/*
		*
		*	
		*	Добавление ключей для таблицы users
		*
		*/
		$this->db->query('CREATE UNIQUE INDEX login_idx ON `users`(login)');
		$this->db->query('CREATE UNIQUE INDEX email_idx ON `users`(email)');

		/*
		*
		*
		*	Добавление таблицы Organizations
		*
		*
		*/
		$this->dbforge->add_field(array(
			'id' => array(
				'type' => 'INT',
				'unsigned' => true,
				'auto_increment' => true
			)
		));

		$this->dbforge->add_field(array(
			'name' => array(
				'type' => 'VARCHAR',
				'constraint' => '150'
			)
		));

		$this->dbforge->add_field(array(
			'ceo' => array(
				'type' => 'INT',
				'unsigned' => true,
				'default' => '0'
			)
		));

		$this->dbforge->add_key('id',true);
		if(!$this->dbforge->create_table('organizations',true)){
			exit('Something goes wrong in Migration_add_user after dbforge->create_table');
		}

		/*
		*
		*
		*	Добавление внешних ключей для таблицы organizations
		*
		*/
		$this->db->query('CREATE UNIQUE INDEX ceo_id ON `organizations`(ceo)');
		$this->db->query('ALTER TABLE `organizations` ADD FOREIGN KEY (`ceo`) REFERENCES `users`(id) ON DELETE CASCADE ON UPDATE CASCADE');


		/*
		*
		*
		*	Добавление таблицы UsersOrganizations
		*
		*/

		$this->dbforge->add_field(array(
			'id' => array(
				'type'     => 'INT',
				'unsigned' => true,
				'auto_increment' => true
			)
		));


		$this->dbforge->add_field(array(
			'user_id' => array(
				'type' => 'INT',
				'unsigned' => true,
				'default' => '0'
			)
		));


		$this->dbforge->add_field(array(
			'org_id' => array(
				'type' => 'INT',
				'unsigned' => true,
				'default' => '0'
			)
		));

		$this->dbforge->add_key('id',true);


		if(!$this->dbforge->create_table('users_organizations',true)){
			exit('Something goes wrong in Migration_add_user after dbforge->create_table');
		}

		/*
		*
		*	Добавление индексов и  внешних ключей для таблицы users_organizations
		*
		*/
		$this->db->query('CREATE UNIQUE INDEX user_id ON `users_organizations`(user_id)');
		$this->db->query('CREATE UNIQUE INDEX user_id_org_id ON `users_organizations`(user_id,org_id)');

		$this->db->query("ALTER TABLE `users_organizations` ADD FOREIGN KEY (`user_id`) REFERENCES `users`(id) ON DELETE CASCADE ON UPDATE CASCADE");

		$this->db->query("ALTER TABLE `users_organizations` ADD FOREIGN KEY (`org_id`) REFERENCES `organizations`(id) ON DELETE CASCADE ON UPDATE CASCADE");

		/*
		*
		*
		*	Добавление таблицы attempts_login_users
		*
		*/
		$this->dbforge->add_field(array(
			'id' => array(
				'type' => 'int',
				'auto_increment' => true
			)
		));

		$this->dbforge->add_field('ip_address VARCHAR(40) COLLATE utf8_bin NOT NULL');
		$this->dbforge->add_field('time TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP');

		$this->dbforge->add_field(array(
			'login' => array(
				'type' => 'varchar',
				'constraint' => '50'
			)
		));
		$this->dbforge->add_key('id',true);
		if(!$this->dbforge->create_table("attempts_login_users",true)){
			exit('Something goes wrong in Migration_add_user after dbforge->create_table');
		}

		/*
		*
		*	Добавление внешних ключей к attempts_login_user
		*
		*/
		$this->db->query('CREATE INDEX login_idx ON `attempts_login_users`(login)');
		$this->db->query('ALTER TABLE `attempts_login_users` ADD FOREIGN KEY (`login`) REFERENCES `users`(login) ON DELETE CASCADE ON UPDATE CASCADE');

		/*
		*
		*	Добавление таблицы autologin_users
		*
		*/

		$this->dbforge->add_field('key_id char(32) COLLATE utf8_bin NOT NULL');
		$this->dbforge->add_field(array(
			'user_id' => array(
				'type' => 'int',
				'unsigned' => true,
				'default' => '0'
			)
		));
		$this->dbforge->add_field('user_agent VARCHAR(150) COLLATE utf8_bin NOT NULL');
		$this->dbforge->add_field('last_ip VARCHAR(40) COLLATE utf8_bin NOT NULL');
		$this->dbforge->add_field('last_login TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP');

		$this->dbforge->add_key('key_id',true);
		$this->dbforge->add_key('user_id',true);

		if(!$this->dbforge->create_table("autologin_users",true)){
			exit('Something goes wrong in Migration_add_user after dbforge->create_table');
		}
		/*
		*
		*	добавление индексов в таблицу autologin_users
		*
		*/
		$this->db->query('CREATE INDEX idx_user_id ON `autologin_users`(user_id)');
		$this->db->query('ALTER TABLE `autologin_users` ADD FOREIGN KEY (`user_id`) REFERENCES `users`(id) ON DELETE CASCADE ON UPDATE CASCADE');

		/*
		*
		*	Добавление таблицы invites_users
		*
		*/

		$this->dbforge->add_field('key_id CHAR(32) COLLATE utf8_bin NOT NULL');
		$this->dbforge->add_field(array(
			'org_id' => array(
				'type' => 'int',
				'unsigned' => true,
				'default' => '0'
			)
		));

		$this->dbforge->add_field(array(
			'role' => array(
				'type' => 'ENUM',
				'constraint' => "'Админ','Менеджер','Агент'",
				'default' => 'Агент'
			) 
		));

		$this->dbforge->add_field('created TIMESTAMP DEFAULT CURRENT_TIMESTAMP');
		$this->dbforge->add_field(array(
			'manager_id' => array(
				'type' => 'INT',
				'default' => '0',
				'unsigned' => true,
				'null'  => true
			)
		));

		$this->dbforge->add_key('key_id',true);
		$this->dbforge->add_key('org_id',true);

		if(!$this->dbforge->create_table("invites_users",true)){
			exit('Something goes wrong in Migration_add_user after dbforge->create_table');
		}

		/*
		*
		*	Добавление внешних ключей и индексов для таблицы invites_users
		*
		*/
		$this->db->query('CREATE INDEX idx_org_id ON `invites_users`(org_id)');
		$this->db->query('ALTER TABLE `invites_users` ADD FOREIGN KEY (`org_id`) REFERENCES `organizations`(id) ON DELETE CASCADE ON UPDATE CASCADE');


	}

	public function down(){

		$this->dbforge->drop_table('users');
		$this->dbforge->drop_table('organizations');
		$this->dbforge->drop_table('users_organizations');
		$this->dbforge->drop_table('attempts_login_users');
		$this->dbforge->drop_table('autologin_users');
		$this->dbforge->drop_table('invites_users');

	}
}