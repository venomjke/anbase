<?php defined("BASEPATH") or exit("No direct access to script file");


/*
*
*	Model users represents attempts users to log in. 
*	tables: attempts_login_users
*
* @author  - Alex.strigin 
* @company - Flyweb 
*
*/

class M_Attempt_login_user extends MY_Model {


	public function __construct(){

		/*
		*
		*	Структура модели
		*
		*/
		$this->table 	   = 'attempts_login_users';
		$this->primary_key = 'id';
		$this->fields 	   = array('id','ip_address','time','login'); 
		$this->result_mode = 'object';
		/*
		*
		*	Правила валидации
		*
		*/
		$this->validate    = array();
	}



	/**
	 *	Выбор числа попыток login происходящих от переданного IP-address или login
	 *
	 * @param	string
	 * @param	string
	 * @return	int
	 */
	function get_attempts_num($ip_address, $login)
	{
		$this->db->where('id_address',$ip_address)
				 ->or_where('login',$login);
		return $this->count_all_results();
	}

	/**
	 *	Увеличение числа попыток для данного IP-адреса и логина
	 *
	 * @param	string
	 * @param	string
	 * @return	void
	 */
	function increase_attempt($ip_address, $login)
	{
		return $this->insert(array('ip_address' => $ip_address,'login' => $login),true);
	}

	/**
	 *	Удалить все записи о попытках login для заданного IP и login
	 *	
	 * @param	string
	 * @param	string
	 * @param	int
	 * @return	void
	 */
	function clear_attempts($ip_address, $login, $expire_period = 86400)
	{
		$this->db->where(array('ip_address' => $ip_address, 'login' => $login));

		// Purge obsolete login attempts
		$this->db->or_where('UNIX_TIMESTAMP(time) <', time() - $expire_period);

		return $this->delete();
	}

}