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

		if(!$this->dbforge->create_table('ci_sessions')){
			exit('Something goes wrong in Migration_add_user after dbforge->create_table');
		}
	}

	public function down(){


	}
}