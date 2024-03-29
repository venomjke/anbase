<?php defined("BASEPATH") or die("No direct access to script");


/*
*	Это джигурда для того, чтобы не возникло проблем во время Extends
*/
if(!class_exists("Users"))
	require_once APPPATH."modules/users/libraries/users.php";

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
		* Подключение исключение
		*/
		$this->ci->load->exception('AnbaseRuntimeException');
		$this->ci->load->exception('ValidationException');
		
		/*
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

	public function get_manager_agents()
	{
		$this->ci->load->model('m_manager_user');
		return $this->ci->m_manager_user->get_manager_agents($this->get_user_id());
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
		$this->ci->form_validation->set_rules($this->ci->m_manager->personal_profile_validation_rules);
		if($this->ci->form_validation->run($this->ci->m_manager)){
			$data=array();
			/*
			* консервируем необходимые для изменения данные
			*/
			$data = array_intersect_key($this->ci->input->post(), array_flip($fields));

			/*
			* Воизбежание пустых полей делаем следующее.
			* Validation rules этого отловить не может, required тут не помогает, т.к необязательно передавать все поля
			* [my_notice]: не очень нравится такое решение, но пока так.
			*/
			foreach($data as $k=>$item){
				if(empty($data[$k])) unset($data[$k]);
			}
			if (!empty($data)) {
				$this->ci->m_manager->update($this->get_user_id(),$data,true);
				$this->update_manager_session_data($data);
			} else {
				throw new AnbaseRuntimeException(lang('common.empty_data'));
			}
			return;
		}

		$errors_validation = array();

		if(has_errors_validation($fields,$errors_validation)){
			throw new ValidationException($errors_validation);
		}
	}

	/**
	 * Редактирование системной информации
	 *
	 * @return void
	 * @author alex.strigin
	 **/
	public function edit_system_profile()
	{
		$fields = array('password','new_password','re_new_password');
		$this->ci->form_validation->set_rules($this->ci->m_manager->system_profile_validation_rules);

		if($this->ci->form_validation->run($this->ci->m_manager)){

			$data = array_intersect_key($this->ci->input->post(),array_flip($fields));

			$this->change_password($this->get_user_id(),$data);

			return;
		}

		$error_validation = array();

		if(has_errors_validation($fields,$error_validation)){
			throw new ValidationException($error_validation);
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
		$fields = array('user[login]','user[password]','user[re_password]','user[name]','user[middle_name]','user[last_name]','user[phone]');

		/*
		* Валидация данных
		*/
		$this->ci->form_validation->set_rules($this->get_validation_fields());

		$register_data = array();

		/*
		* Выполняем валидацию
		*/
		if($this->ci->form_validation->run($this->ci->m_manager)){

			/*
			* Извлекаем данные формы
			*/
			$register_data = array();
			$register_data = $this->get_form_data();
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

	private function get_validation_fields()
	{
		return $this->get_register_validation_rules(false); // обращаемся к users за списком правил валидации 
	}

	private function get_form_data()
	{
		return $this->ci->input->post('user');
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