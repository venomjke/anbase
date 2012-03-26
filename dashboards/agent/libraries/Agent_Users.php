<?php defined('BASEPATH') or die('No direct access to script file');

/*
*	Это джигурда для того, чтобы не возникло проблем во время Extends
*/
if(!class_exists("Users"))
	require_once APPPATH."modules/users/libraries/users.php";

/**
*
*
*	@author  - Alex.strigin <apstrigin@gmail.com>
*	@company - Flyweb 
*
*		Библиотека "Agent" предназначения для реализации логики пользователей - агентов в
*	панели управления "Agent"
*
*/

class Agent_Users extends Users{

	public function __construct(){

	
		parent::__construct();

		/* 
		*
		*	 Загрузка либ
		*/

		/*
		*
		*	Загрузка моделей
		*/
		$this->ci->load->model('agent/m_agent');
	}


	/*
	*	
	*	Проверка, агент ( смит ) ли зашел?
	*/
	public function is_logged_in_as_agent(){

		if( $this->is_logged_in() && $this->is_agent() ){

			return TRUE;
		}
		return FALSE;
	}

	/**
	 * Метод, определяющий, есть у пользователя менеджер или нет?
	 *
	 * @return bool
	 * @author Alex.strigin
	 **/
	public function has_manager()
	{
		return $this->ci->m_agent->has_manager($this->get_user_id());
	}


	/**
	 * Метод, возвращающий имя менеджера агента
	 *
	 * @return string
	 * @author Alex.strigin
	 **/
	public function get_manager_name()
	{
		$manager = $this->ci->m_agent->get_manager_agent($this->get_user_id());
		if(!empty($manager)){
			return $manager->last_name.' '.$manager->name.' '.$manager->middle_name;
		}
		return "";
	}


	/**
	 * Метод, возвращающий телефон агента
	 *
	 * @return string
	 * @author Alex.strigin
	 **/
	public function get_manager_phone()
	{
		$manager = $this->ci->m_agent->get_manager_agent($this->get_user_id());
		if(!empty($manager)){
			return $manager->phone;
		}
		return "";
	}

}