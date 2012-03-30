<?php defined("BASEPATH") or die("No direct access to script");


/*
*	Это джигурда для того, чтобы не возникло проблем во время Extends
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
* @author  Alex.strigin <apstrigin@gmail.com>
* @company Flyweb
*/
class Manager_Users extends Users
{
	
	function __construct()
	{
		parent::__construct();

		/*
		*
		*	Загрузка моделей
		*/
		$this->ci->load->model('manager/m_manager');

	}


	/**
	 * Проверка, является ли user манагером
	 *
	 * @return void
	 * @author alex.strigin
	 **/
	public function is_logged_in_as_manager ()
	{
		if($this->is_logged_in() && $this->is_manager()){

			return TRUE;
		}
		return FALSE;
	}


	/**
	 * Редактирование персонального профиля
	 *
	 * @return void
	 * @author alex.strigin
	 **/
	public function edit_personal_profile()
	{

		$fields = array('name','middle_name','last_name','phone');
		$this->ci->form_validation->set_rules($this->ci->m_manager->get_validation_rules());
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
				$this->ci->m_manager->update($this->get_user_id(),$data);
				$this->update_manager_session_data($data);
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
	public function update_manager_session_data($data)
	{
		$this->ci->session->set_userdata($data);
	}


}