<?php defined('BASEPATH') or exit('No direct script access allowed');

/*
*
*	@author Alex.strigin
*	@company Flyweb
*
*/
class Migration_drop_foreign_key extends CI_Migration{


	/*
	*
	*	@author Alex.strigin
	*	
	*		Добавление удаление внешнего ключа у таблицы attempts_login_users
	*/
	public function up(){
		$this->db->query('ALTER TABLE  `attempts_login_users` DROP FOREIGN KEY  `attempts_login_users_ibfk_1`');
	}
	public function down(){

	}
}