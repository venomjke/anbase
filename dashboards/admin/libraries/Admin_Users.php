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
			return $this->ci->m_invite_user->is_exists_invite($key_id,$email,M_User::USER_ROLE_ADMIN);
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
	 * Выбор всех менеджеров данной организации
	 *
	 * @return array
	 * @author alex.strigin
	 **/
	public function get_all_managers()
	{
		return $this->ci->m_admin->get_all_managers($this->get_org_id());
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
						$this->ci->m_admin->change_position($this->ci->input->post('id'),$this->get_user_role($this->ci->input->post('id')),$this->ci->input->post('role'));
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
								$this->ci->m_admin->change_position($this->ci->input->post('id'),$this->get_user_role($this->ci->input->post('id')),$this->ci->input->post('role'));
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
	 * Назначение менеджера агенту
	 *
	 * @return void
	 * @author alex.strigin
	 **/
	public function assign_manager_agent()
	{

		$manager_id = $this->ci->input->post('manager_id');
		$user_id    = $this->ci->input->post('user_id');
		$this->ci->form_validation->set_rules($this->ci->m_manager_user->get_validation_rules());
		/*
		*	Проверить данные. Оба должны существовать, быть активными.
		*/
		if($this->ci->form_validation->run($this->ci->m_manager_user)){

			/*
			*
			*	Один должен быть менеджер, а второй агент, также агент должен не иметь менеджера
			*/
			if($this->is_manager($manager_id) && $this->is_agent($user_id) && !$this->has_manager($user_id)){

				$this->ci->m_manager_user->insert($this->ci->input->post(),true);
				return;
			}
			throw new AnbaseRuntimeException(lang("not_legal_data"));
		}

		/*
		* Валидацию не прошли, возвращаем исключение
		*/
		throw new ValidationException(array('manager_id'=>$this->ci->form_validation->error('manager_id'),'user_id'=>$this->ci->form_validation->error('user_id')));
	}


	/**
	 * Удаление инвайтов
	 *
	 * @return void
	 * @author alex.strigin
	 **/
	public function del_invites()
	{
		$this->ci->load->model('m_invite_user');
		 /*
		 * выбираем список ids на удаление
		 */
		 $ids_invites = $this->ci->input->post('ids_invites',array());

		 /*
		 *
		 * Если есть, что удалять, то удаляем
		 */ 
		 if(!empty($ids_invites)){

		 	foreach($ids_invites as $id_invite){

		 		if(is_numeric($id_invite)){
			 		/*
			 		* Если удалить не удалось
			 		*/
			 		if(!$this->ci->m_invite_user->delete($id_invite)){
			 			throw new AnbaseRuntimeException(lang('common.delete_error'));
			 		}
			 	}else{
			 		throw new AnbaseRuntimeException(lang('common.not_legal_data'));
			 	}

		 	}
		 	return;
		 }
		 throw new AnbaseRuntimeException(lang('common.not_legal_data'));
	}

	/**
	 * Выбор всех инвайтов
	 *
	 * @return void
	 * @author alex.strigin
	 **/
	public function get_all_invites()
	{
		$this->ci->load->model("m_invite_user");
		return $this->ci->m_invite_user->get_all(array("org_id"=>$this->get_org_id()));
	}

	/**
	 * Отправка инвайта для регистрации пользователя с правами администратор
	 *
	 * @return void
	 * @author alex.strigin
	 **/
	public function send_invite_admin()
	{
		$this->send_invite('admin/register',M_User::USER_ROLE_ADMIN);
	}

	/**
	 * Отправка инвайта для регистрации пользователя с правами менеджер
	 *
	 * @return void
	 * @author alex.strigin
	 **/
	public function send_invite_manager()
	{
		$this->send_invite('manager/register',M_User::USER_ROLE_MANAGER);
	}

	/**
	 * Оптравка инвайта для регистрации пользователя с правами агента
	 *
	 * @return void
	 * @author alex.strigin
	 **/
	public function send_invite_agent()
	{
		$this->send_invite('agent/register',M_User::USER_ROLE_AGENT);
	}


	/**
	 * Отправка инвайта. Валидация данных, создание ключа, запись в БД и отправка по mail уведомелния.
	 *
	 * @return void
	 * @author alex.strigin
	 **/
	private function send_invite($uri = '',$role = '')
	{
		$this->ci->load->model('m_invite_user');

		$data = array('manager_id','email');
		/*
		* задаем правила валидации
		*/
		$this->ci->form_validation->set_rules($this->ci->m_invite_user->get_validation_rules());

		if($this->ci->form_validation->run($this->ci->m_invite_user)){

			$insert_data = array();

			/*
			* сборка данных для добавления
			*/
			$insert_data['role']  = $role;
			$insert_data['email'] = $this->ci->input->post('email');
			$insert_data['manager_id'] = $this->ci->input->post('manager_id')?$this->ci->input->post('manager_id'):null;
			$insert_data['key_id']   = substr(md5(uniqid(rand())), 0, 24); // generate key;
			$insert_data['org_id']= $this->get_org_id();

			/*
			*
			* При добавлении инвайта обязательно должен быть задан email и быть уникальным, а manager_id может быть не задан, но если
			* задан то обязательно должен быть нормальным id
			*/
			if((!empty($insert_data['email']) and $this->ci->m_admin->is_email_available($insert_data['email'])) && (empty($insert_data['manager_id']) or $this->is_manager($insert_data['manager_id']))) {

				if( ( $invite_id = $this->ci->m_invite_user->insert($insert_data,true)) )
				{
					/*
					*
					*	отправка почтой
					*/
					/*
					$this->ci->load->library('email');

					$this->ci->email->to('mail');
					$this->ci->email->from('mail');
					$this->ci->email->subject('blabla');
					$this->ci->email->message('messages');
					$this->ci->email->send();
					*/
					return; 
				}

				throw new AnbaseRuntimeException(lang("common.insert_error"));		
			}

			throw new AnbaseRuntimeException(lang("common.not_legal_data"));
		}
		/*
		* Валидация не пройдена, генерим исключение
		*/
		throw new ValidationException(array('manager_id' => $this->ci->form_validation->error('manager_id'), 'email'=>$this->ci->form_validation->error('email')));
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


	/**
	 * Регистрация админа в системе
	 *
	 * @return void
	 * @author 
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
		$this->ci->form_validation->set_rules($this->ci->m_admin->register_validation_rules);

		$register_data = array();

		/*
		* Выполняем валидацию
		*/
		if($this->ci->form_validation->run($this->ci->m_admin)){

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
}


