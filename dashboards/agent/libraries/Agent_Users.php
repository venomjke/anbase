<?php defined('BASEPATH') or die('No direct access to script file');

/*
*	Эта джигурда для того, чтобы не возникло проблем во время Extends
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
		* Подключение исключение
		*/
		$this->ci->load->exception('AnbaseRuntimeException');
		$this->ci->load->exception('ValidationException');

		/*
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
	 * Метод возвращает uri домашней страницы агента
	 *
	 * @return void
	 * @author alex.strigin
	 **/
	public function get_home_page()
	{
		return "agent/orders/?s=my";
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
	 * Метод проверяет, есть ли у пользователя invite на вход
	 *
	 * @return void
	 * @author 
	 **/
	public function has_invite()
	{
		$this->ci->load->model('m_invite_user');
		/*
		* Для инвайта нужно обязательно передавать key_id, email
		*/
		$key_id = $this->ci->input->get('key','');
		$email  = $this->ci->input->get('email','');
		if(!empty($key_id) && !empty($email)){
			return $this->ci->m_invite_user->is_exists_invite($key_id,$email,M_User::USER_ROLE_AGENT);
		}
		return false;
	}

	/**
	 * Загрузка инвайта. Выбор информации об инвайте, а также обо всем, что с ним связано.
	 *
	 * @return object
	 * @author alex.strigin
	 **/
	public function load_invite($key,$email)
	{
		$this->ci->load->model('m_invite_user');
		return $this->ci->m_invite_user->load_invite($key,$email);
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
		$this->ci->form_validation->set_rules($this->ci->m_agent->personal_profile_validation_rules);
		if($this->ci->form_validation->run($this->ci->m_agent)){
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
				$this->ci->m_agent->update($this->get_user_id(),$data,true);
				$this->update_agent_session_data($data);
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
		$this->ci->form_validation->set_rules($this->ci->m_agent->system_profile_validation_rules);

		if($this->ci->form_validation->run($this->ci->m_agent)){

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
	 * Регистрация пользователя в качестве агента в системе.
	 *
	 * @return void
	 * @author alex.strigin
	 **/
	public function register($invite)
	{
		$this->ci->load->model('m_invite_user');
		/*
		* Наши данные
		*/
		$fields = array('r_login','r_password','r_re_password','name','middle_name','last_name','phone');

		/*
		* Валидация данных
		*/
		$this->ci->form_validation->set_rules($this->ci->m_agent->register_validation_rules);
		$register_data = array();

		if($this->ci->form_validation->run($this->ci->m_agent)){
			/*
			* Выбираем наши данные
			*/
			$register_data['login']  = $this->ci->input->post('r_login');
			$register_data['password'] = $this->ci->input->post('r_password');
			$register_data['name'] = $this->ci->input->post('name');
			$register_data['middle_name'] = $this->ci->input->post('middle_name');
			$register_data['last_name'] = $this->ci->input->post('last_name');
			$register_data['phone'] = $this->ci->input->post('phone');			
			$register_data['email']  = $invite->email;
			$register_data['org_id'] = $invite->org_id;
			$register_data['role']   = $invite->role;

			if( ($res = $this->simple_register($register_data)) ){
				/*
				* Если регистрация пройдена успешно, то удалить инвайт, и присобачить агента к менеджеру
				*/
				$this->ci->m_invite_user->delete($invite->id);
				/*
				* Если задан id manager
				*/
				if($invite->manager_id){
					log_message('debug','Try to assign manager to agent, manager_id:'.$invite->manager_id.' user_id:'.$res);
					$this->ci->m_manager_user->insert(array('manager_id'=>$invite->manager_id,'user_id'=>$res),true);
				}
				return true;
			}
			throw new AnbaseRuntimeException(lang("common.insert_error"));
		
		}

		$errors_validation = array();

		/*
		* Из-за того, что form_validation не имеет методов, позволяющих проверить, были ли ошибки, приходится вот так изгалаться
		*/
		if(has_errors_validation($fields,$errors_validation)){
			throw new ValidationException($errors_validation);
		}	
		
		/*
		*  Ничего не произошло... ничего не делаем
		*/
		return false;
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