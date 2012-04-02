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
*
*	@author  - Alex.strigin
*	@company - Flyweb
*
*/
class Admin_Users extends Users{

	function __construct() {
		
		parent::__construct();
		
		/*
		*
		*	Загрузка моделей
		*
		*/
		$this->ci->load->model('admin/m_admin');
	}


	/**
	 * Залогинен ли пользователь как админ, а?
	 *
	 * @return void
	 * @author alex.strigin
	 **/
	public function is_logged_in_as_admin()
	{
		if ($this->is_logged_in() && $this->is_admin()) {	
			return true;
		}
		return false;
	}

	/**
	 * Метод выбирает список сотрудников с ролью = Агент и Менеджер, и 
	 * затем возвращает наверх
	 *
	 * @return array
	 * @author alex.strigin
	 **/
	public function get_list_staff()
	{
		return $this->ci->m_admin->get_list_staff($this->get_org_id());
	}


	/**
	 * Метод возвращает список админов
	 *
	 * @return array
	 * @author alex.strigin
	 **/
	public function get_list_admins()
	{
		return $this->ci->m_admin->get_list_admins($this->get_org_id());
	}

	
	/**
	 * Редактирование персонального профайла
	 *
	 * @return boolean
	 * @author alex.strigin
	 **/
	public function edit_personal_profile()
	{
		$this->ci->form_validation->set_rules($this->ci->m_admin->get_validation_rules());
		if($this->ci->form_validation->run()){
			$data = array();
			/*
			*
			* собираем данные для изменения т.к правила валидации required не
			* предусматривают
			*
			*/
			if($this->ci->input->post('name')) $data['name'] = $this->ci->input->post('name');
			if($this->ci->input->post('middle_name')) $data['middle_name'] = $this->ci->input->post('middle_name');
			if($this->ci->input->post('last_name')) $data['last_name'] = $this->ci->input->post('last_name');
			if($this->ci->input->post('phone')) $data['phone'] = $this->ci->input->post('phone');

			if(!empty($data)){
				$this->ci->m_user->update($this->get_user_id(),$data,true);
				$this->update_admin_session_data($data);
				return TRUE;
			}else{

				$this->error += array('not_recive_data' => lang('common.empty_data'));
				return FALSE;
			}
			return TRUE;
		}
		$this->error += array( 'name' => $this->ci->form_validation->error('name'));
		$this->error += array( 'middle_name' => $this->ci->form_validation->error('middle_name'));
		$this->error += array( 'last_name' => $this->ci->form_validation->error('last_name'));
		$this->error += array( 'phone' => $this->ci->form_validation->error('phone'));
	
		return TRUE;
	}


	/**
	 * Редактирование организационного профайла
	 *
	 * @return boolean
	 * @author alex.strigin
	 **/
	public function edit_organization_profile()
	{
		$this->ci->load->model('users/m_organization');
		$this->ci->form_validation->set_rules($this->ci->m_organization->get_validation_rules());
		if($this->ci->form_validation->run()){
			$data = array();
			/*
			*
			* собираем данные для изменения т.к правила валидации required не
			* предусматривают
			*
			*/
			if($this->ci->input->post('name')) $data['name'] = $this->ci->input->post('name');

			if($this->ci->input->post('email')) $data['email'] = $this->ci->input->post('email');

			if($this->ci->input->post('phone')) $data['phone'] = $this->ci->input->post('phone');

			if(!empty($data)){
				$this->ci->m_organization->update($this->get_org_id(),$data,true);
				return TRUE;
			}else{

				$this->error += array('not_recive_data' => lang('common.empty_data'));
				return FALSE;
			}
			return TRUE;
		}
		$this->error += array( 'name' => $this->ci->form_validation->error('name'));
		$this->error += array( 'email' => $this->ci->form_validation->error('email'));
		$this->error += array( 'phone' => $this->ci->form_validation->error('phone'));
		
		return false;
	}

	/**
	 * Редактирование системной информации
	 *
	 * @return void
	 * @author alex.strigin
	 **/
	public function edit_system_profile()
	{
		$this->ci->form_validation->set_rules($this->ci->m_admin->get_validation_rules());
		if($this->ci->form_validation->run()){
			$data = array();
			/*
			*
			* собираем данные для изменения т.к правила валидации required не
			* предусматривают
			*
			*/
			if($this->ci->input->post('password')) $data['password'] = $this->ci->input->post('password');

			if(!empty($data)){
				$this->ci->m_organization->update($this->get_org_id(),$data,true);
				return TRUE;
			}else{

				$this->error += array('not_recive_data' => lang('common.empty_data'));
				return FALSE;
			}
			return TRUE;
		}
		$this->error += array( 'password' => $this->ci->form_validation->error('password'));
		return false;
	}

	/**
	 * Изменение должности сотрудника. Id user'a и role находятся в input->post.
	 *
	 *
	 * @return void
	 * @author alex.strigin
	 **/
	public function change_position_employee()
	{
		/*
		*
		*	Проверить данные, выполнить валидацию, если что не так, то ни чего не делать, и возвратить исключение.	
		*	Если с данными все впорядке, то проверить права меняющего, если не ceo, то разрешено назначить  только агента или менеджера.
		*/
		$this->ci->form_validation->set_rules($this->ci->m_admin->get_validation_rules());

		/*
		*
		*  Данные, которые понадобятся мне во время работы
		*/
		$data = array('id','role');
		/*
		*
		* Во время проверки передаю также ответственного за правила, то есть m_admin_user, модуль валидации будет общаться только с ним.
		*/
		if($this->ci->form_validation->run($this->ci->m_admin)){

			/*
			* все данные должны быть переданы
			*/
			if($this->ci->input->post('id')&&$this->ci->input->post('role') ){

				/*
				* user не может изменить себя
				*/
               if($this->ci->input->post('id') != $this->get_user_id())
               {
               	
					/*
					*
					* директор может менять все, что ему пожелается
					*/
					if($this->is_ceo($this->get_user_id())){
						/*
						* Если мы дошли до сюда, то внезависимости от возвращенного результата мы возвращаем success
						*/
						$this->ci->m_admin->update($this->ci->input->post('id'),array('role'=>$this->ci->input->post('role')),true);
					}else{

						/*
	               		* админ может изменить любого, кроме админа
	               		*/
	               		if(!$this->is_admin($this->ci->input->post('id'))){
							/*
							* Обычный админ может поменять должность на любую отличную от админ.
							*
							*/
							if($this->ci->input->post('role') != M_User::USER_ROLE_ADMIN){
								$this->ci->m_admin->update($this->ci->input->post('id'),array('role'=>$this->ci->input->post('role')),true);
							}else{
								throw new AnbaseRuntimeException(lang("no_enough_right"));
							}	
							return;
	               		}
	               		throw new AnbaseRuntimeException(lang("no_enough_right"));

					}
					return;
				}
				
				throw new AnbaseRuntimeException(lang("cant_apply_yourself"));
			}
			/*
			*
			* Ошибка, пустого Update_data быть не должно
			*/
			throw new AnbaseRuntimeException(lang("common.empty_data"));
			return;
		}

		/*
		*
		* Если я попал сюда, то значит были обнаружены ошибки уровня валидации.
		*/
		throw new ValidationException(array('id' => $this->ci->form_validation->error('id'),'role'=>$this->ci->form_validation->error('role')));
	}

	/**
	 * Метод, выполняющий обновление данных сессии
	 *
	 * @return void
	 * @author alex.strigin
	 **/
	public function update_admin_session_data($data)
	{
		$this->ci->session->set_userdata($data);
	}
}


