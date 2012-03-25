<?php defined("BASEPATH") or die("No direct access to script");


/*
* гл. модель для работы с user
*/
if(!class_exists("M_User"))
	require_once APPPATH."modules/users/models/m_user.php";

/**
*
*
*	@author  - Alex.strigin <apstrigin@gmail.com>
*	@company - Flyweb 
*
*/
class M_Agent extends M_User{


	public function __construct(){

		parent::__construct();

	}


	/**
	 * Подготовка запроса к выбору данных об менеджере
	 *
	 * @return void
	 * @author Alex.strigin
	 **/
	private function _build_manager_query()
	{
		$this->select("users.id");
		$this->select("users.name");
		$this->select("users.middle_name");
		$this->select("users.last_name");
		$this->select("users.phone");


		$this->from("managers_users");
		$this->join("users","managers_users.manager_id = users.id");
	}

	/**
	 * Метод, возвращающий телефон диспетчера
	 *
	 * @return string
	 * @author Alex.strigin
	 **/
	public function get_callmanager_phone()
	{

	}

	/**
	 * Метод, определяющй есть ли у агента менеджер
	 *
	 * @return bool
	 * @author Alex.strigin
	 **/
	public function has_manager($user_id)
	{
		$this->_build_manager_query();
		$this->where("user_id =",$user_id);
		return $this->db->count_all_results() == 1?TRUE:FALSE;
	}

	/**
	 * Метод, возвращающий объект менеджера
	 *
	 * @return object
	 * @author Alex.strigin
	 **/
	public function get_manager_agent($user_id)
	{
		$this->_build_manager_query();
		$this->where("user_id =",$user_id);
		$this->limit(1);
		$manager = $this->db->get();

		if($manager->num_rows() == 1){
			return $manager->row();
		}
		return NULL;
	}
}