<?php defined("BASEPATH") or exit("No direct access to script file");


/*
*
*	Model users represents user auth data. 
*	tables: users
*
* @author  - Alex.strigin 
* @company - Flyweb 
*/
class M_User extends MY_Model{


	/*
	*
	*	Возможные состояния пользователя
	*
	*/
	const USER_ACTIVE	   = 1;
	const USER_NON_ACTIVE  = 0;
	
	/*
	*
	*	Основные роли пользователей
	*
	*/
	const USER_ROLE_ADMIN 	= 'Админ';
	const USER_ROLE_MANAGER = 'Менеджер';
	const USER_ROLE_AGENT   = 'Агент';


	public function __construct(){

		parent::__construct();

		/*
		*
		*	Структура таблицы
		*
		*/
		$this->table       = 'users';
		$this->primary_key = 'id';
		$this->fields      =  array('id','login','password','email','activated','name','middle_name','last_name','phone','role','created','last_login','last_ip');

		$this->result_mode = 'object';
		/*
		*
		*	Правила валидации
		*/
		$this->validate = array();

		/*
		*
		*	Хуки
		*/
		$this->before_create = array("before_insert_user");
	}



	/**
	 * Выбор пользователя по ID
	 *
	 * @param	int
	 * @param	bool
	 * @return	object
	 */
	function get_user_by_id($user_id, $activated = 1)
	{
		$this->db->where('activated',$activated ? 1 : 0);
		return $this->get($user_id);
	}

	/**
	 * Выбор пользователя по email или login
	 *
	 * @param	string
	 * @return	object
	 */
	function get_user_by_login_or_email($login)
	{
		$this->db->where('login',$login)
				 ->or_where('email',$login);
		$query = $this->db->get($this->table);
		if ($query->num_rows() == 1) return $query->row();
		return NULL;
	}

	/**
	 * Выбор пользователя по id
	 *
	 * @param	string
	 * @return	object
	 */
	function get_user_by_login($login)
	{
		return $this->get(array('login'=>$login));
	}

	/**
	 * Выбор пользователя по email
	 *
	 * @param	string
	 * @return	object
	 */
	function get_user_by_email($email)
	{
		return $this->get(array('email'=>$email));
	}

	/**
	 * Проверка, свободен ли логин
	 *
	 * @param	string
	 * @return	bool
	 */
	function is_login_available($login)
	{
		return $this->count_all_results(array('login'=>$login)) == 0;
	}

	/**
	 * Проверка, свободен ли email
	 *
	 * @param	string
	 * @return	bool
	 */
	function is_email_available($email)
	{
		return $this->count_all_results(array('email'=>$email)) == 0;
	}


	 /**
	 * Update user login info, such as IP-address or login time, and
	 * clear previously generated (but not activated) passwords.
	 *
	 *	Обновить информацию о пользователе, такую как IP-address и время login
	 * @param	int
	 * @param	bool
	 * @param	bool
	 * @return	void
	 */
	function update_login_info($user_id, $record_ip, $record_time)
	{
		if ($record_ip)		$this->db->set('last_ip', $this->input->ip_address());
		if ($record_time)	$this->db->set('last_login', date('Y-m-d H:i:s'));

		$this->db->where('id', $user_id);
		$this->db->update($this->table);
	}

	/**
	*
	*	Функция, для выполнения перед созданием нового пользователя
	*
	*/
	function before_insert_user($data){
		$data['last_login'] = date('Y-m-d H:i:s');
		$data['last_ip'] = $this->input->ip_address();
		$data['created']    = $data['last_login'];
		$data['activated']  = 1;
		return $data;
	}

}