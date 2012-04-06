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

if(!class_exists("AnbaseRuntimeException")){
	require_once APPPATH."exceptions/AnbaseRuntimeException.php";
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
	 * Метод проверяет, есть ли у пользователя invite на вход
	 *
	 * @return boolean
	 * @author alex.strigin
	 **/
	public function has_invite()
	{
		$this->ci->load->model("m_invite_user");

		/*
		* Для инвайта нужно обязательно передавать key_id, email
		*/
		$key_id = $this->ci->input->get('key','');
		$email  = $this->ci->input->get('email','');
		if(!empty($key_id) and !empty($email)){
			return $this->ci->m_invite_user->is_exists_invite($key_id,$email,M_User::USER_ROLE_MANAGER);
		}

	}


	/**
	 * Загрузка инвайта. Выбор информации об инвайте, а также обо всем, что с ним связано
	 *
	 * @return object
	 * @author alex.strigin
	 **/
	public function load_invite($key,$email)
	{
		$this->ci->load->model("m_invite_user");
		return $this->ci->m_invite_user->load_invite($key,$email);
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
		return $this->ci->m_manager->get_list_staff($this->get_org_id());
	}


	/**
	 * Метод возвращает список админов
	 *
	 * @return array
	 * @author alex.strigin
	 **/
	public function get_list_admins()
	{
		return $this->ci->m_manager->get_list_admins($this->get_org_id());
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
				throw new AnbaseRuntimeException(lang('common.empty_data'));
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
	 * Регистрация менеджера в системе
	 *
	 * @return void
	 * @author alex.strigin
	 **/
	public function register($invite)
	{
		$this->ci->load->model('m_invite_user');

		/*
		*
		* Наши данные
		*/
		$fields = array('login','name','password','middle_name','last_name','phone');

		/*
		* Валидация данных
		*/
		$this->ci->form_validation->set_rules($this->ci->m_manager->register_validation_rules);

		$register_data = array();

		/*
		* Выполняем валидацию
		*/
		if($this->ci->form_validation->run($this->ci->m_manager)){

			/*
			* Выбираем наши данные путем пересечения "наших" и "общих"
			*/
			$register_data = array_intersect_key($this->ci->input->post(),array_flip($fields));

			$register_data['email']  = $invite->email;
			$register_data['org_id'] = $invite->org_id;
			$register_data['role']   = $invite->role;

			if (($user_id = $this->simple_register($register_data))) {
				
				/*
				*
				*	Если регистрация прошла успешно, то удаляем инвайт
				*/
				$this->ci->m_invite_user->delete($invite->id);
				return true;
			}
			/*
			* если по какой-то жопеной причине не удалось зарегаться, то возвращаем исключение
			*/	
			throw new AnbaseRuntimeException(lang("common.insert_error"));
		}
		$errors_validation = array();

		if(has_errors_validation($fields,$errors_validation)){
			throw new ValidationException($errors_validation);
		}
		return false;
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