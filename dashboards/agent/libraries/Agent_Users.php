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

}