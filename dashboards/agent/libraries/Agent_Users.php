<?php defined('BASEPATH') or die('No direct access to script file');

/*
*	Эта джигурда для того, чтобы не возникло проблем во время Extends
*/
if(!class_exists("Users"))
	require_once APPPATH."modules/users/libraries/users.php";


/*
*
* Подключение исключений
*
*/
if(!class_exists("ValidationException")){
	require_once APPPATH."exceptions/ValidationException.php";
}

if(!class_exists("RuntimeException")){
	require_once APPPATH."exceptions/RuntimeException.php";
}

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


	/**
	 * Метод выбирает список сотрудников с ролью = Агент и Менеджер, а
	 * затем возвращает наверх
	 *
	 * @return array
	 * @author alex.strigin
	 **/
	public function get_list_staff()
	{
		return $this->ci->m_agent->get_list_staff($this->get_org_id());
	}


	/**
	 * Метод возвращает список админов
	 *
	 * @return array
	 * @author alex.strigin
	 **/
	public function get_list_admins()
	{
		return $this->ci->m_agent->get_list_admins($this->get_org_id());
	}

	/**
	 * Редактирование персонального профайла
	 *
	 * @return void
	 * @author alex.strigin
	 **/
	public function edit_personal_profile()
	{
		$fields = array('name','middle_name','last_name','phone');
		$this->ci->form_validation->set_rules($this->ci->m_agent->get_validation_rules());
		if($this->ci->form_validation->run()){
			$data=array();
			/*
			*
			* консервируем необходимые для изменения данные
			*
			*/
			foreach($fields as $field){
				if($this->ci->input->post($field)) $data[$field] = $this->ci->input->post($field);
			}

			if (!empty($data)) {
				$this->ci->m_agent->update($this->get_user_id(),$data);
				$this->update_agent_session_data($data);
			} else {
				throw new RuntimeException(lang('common.empty_data'),'no_recive_data');
			}
			return;
		}
		/*
		* Из-за того, что form_validation не имеет методов, позволяющих проверить, были ли ошибки, приходится вот так изгалаться
		*
		*/
		$has_validation_error = false;
		$validation_errors = array();
		foreach($fields as $field){
			$validation_errors[$field] = $this->ci->form_validation->error($field);
			if(empty($validation_errors[$field]))
				$has_validation_error = true;
		}


		if($has_validation_error){
			throw new ValidationException($validation_errors);
		}
	}


	/**
	 * Обновление данных сессии агента
	 *
	 * @return void
	 * @author alex.strigin
	 **/
	public function update_agent_session_data($data)
	{
		$this->ci->session->set_userdata($data);
	}
}