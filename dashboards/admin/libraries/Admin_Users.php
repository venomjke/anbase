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
		$this->ci->load->model('m_order');
		$this->ci->load->model('m_order_user');
	}

	/**
	 * Возврат uri на домашнюю страницу админа
	 *
	 * @return string
	 * @author alex.strigin
	 **/
	public function get_home_page()
	{
		return "admin/orders";
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
	 * Получить список категорий заявок
	 *
	 * @return array
	 * @author alex.strigin
	 **/
	public function get_category_list()
	{
		return $this->ci->m_order->get_category_list();
	}

	/**
	 * Получить список сделок
	 *
	 * @return array
	 * @author alex.strigin
	 **/
	public function get_dealtype_list()
	{
		return $this->ci->m_order->get_dealtype_list();
	}

	/**
	 * Получить список районов
	 * [ my_notice: мне кажется, что расположение get_region_list здесь неправильное ]
	 * @return array
	 * @author alex.strigin
	 **/
	public function get_region_list()
	{
		$this->ci->load->model('m_region');
		return $this->ci->m_region->get_region_list();
	}

	/**
	 * Получить список метро
	 * [ my_notice: аналогично get_region_list мне не нравится расположение здесь get_metro_list]
	 * @return array
	 * @author alex.strigin
	 **/
	public function get_metro_list()
	{
		$this->ci->load->model('m_metro');
		return $this->ci->m_metro->get_metro_list();
	}

	/**
	 * Редактирование персонального профайла
	 *
	 * @return boolean
	 * @author alex.strigin
	 **/
	public function edit_personal_profile()
	{
		$fields = array('name','middle_name','last_name','phone');
		$this->ci->form_validation->set_rules($this->ci->m_admin->personal_profile_validation_rules);

		if($this->ci->form_validation->run($this->ci->m_admin)){
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
				$this->ci->m_admin->update($this->get_user_id(),$data,true);
				$this->update_admin_session_data($data);
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
	 * Редактирование организационного профайла
	 *
	 * @return boolean
	 * @author alex.strigin
	 **/
	public function edit_organization_profile()
	{
		$this->ci->load->model('users/m_organization');

		$fields = array('name','phone','email');

		$this->ci->form_validation->set_rules($this->ci->m_organization->edit_validation_rules);
		if($this->ci->form_validation->run($this->ci->m_organization)){
			$data = array();
			
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
				$this->ci->m_organization->update($this->get_org_id(),$data,true);
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
		$this->ci->form_validation->set_rules($this->ci->m_admin->system_profile_validation_rules);

		if($this->ci->form_validation->run($this->ci->m_admin)){

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
		$this->ci->form_validation->set_rules($this->ci->m_admin->change_position_employee_validation_rules);

		/*
		*
		*  Данные, которые понадобятся мне во время работы
		*/
		$data = array('id','role');
		/*
		*
		* Во время проверки передаю также ответственного за правила, то есть m_admin, модуль валидации будет общаться только с ним.
		*/
		if($this->ci->form_validation->run($this->ci->m_admin)){

			$employee_id = $this->ci->input->post('id');
			$role    = $this->ci->input->post('role');

			/*
			* user не может изменить себя
			*/
           if($employee_id != $this->get_user_id())
           {
           	
				/*
				*
				* директор может менять все, что ему пожелается
				*/
				if($this->is_ceo($this->get_user_id())){
					/*
					* Если мы дошли до сюда, то внезависимости от возвращенного результата мы возвращаем success
					*/
					$this->ci->m_admin->change_position($employee_id,$this->get_user_role($employee_id),$this->ci->input->post('role'));
				}else{

					/*
               		* админ может изменить любого, кроме админа
               		*/
               		if(!$this->is_admin($employee_id)){
						/*
						* Обычный админ может поменять должность на любую отличную от админ.
						*
						*/
						if($role != M_User::USER_ROLE_ADMIN){
							$this->ci->m_admin->change_position($employee_id,$this->get_user_role($employee_id),$role);
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

		$errors_validation = array();

		if(has_errors_validation($data,$errors_validation)){
			/*
			*
			* Если я попал сюда, то значит были обнаружены ошибки уровня валидации.
			*/
			throw new ValidationException($errors_validation);
		}
		return false;
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
			if($this->is_agent($user_id) && !$this->has_manager($user_id)){

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
	 * Отвязываем агента от менеджера.
	 *
	 * @return void
	 * @author alex.strigin
	 **/
	public function unbind_manager()
	{
		$this->ci->form_validation->set_rules($this->ci->m_manager_user->unbind_manager_validation_rules);

		/*
		* Проверяем данные
		*/
		if($this->ci->form_validation->run($this->ci->m_manager_user)){

			/*
			* Выдергиваем user_id. Чел должен быть агент, и иметь менеджера.
			*/
			$agent_id = $this->ci->input->post('user_id');
			if($this->is_agent($agent_id) && $this->has_manager($agent_id)){
				$this->ci->m_manager_user->unbind_manager($agent_id);
				return;
			}
			throw new AnbaseRuntimeException(lang("common.not_legal_data"));
		}

		$errors_validation = array();

		if(has_errors_validation(array('user_id'),$errors_validation)){
			throw new ValidationException($errors_validation);
		}
		return false;
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

		 		/*
		 		* Инвайт должен быть numeric, а также принадлежать к опр. организации
		 		*/
		 		if(is_numeric($id_invite) && $this->ci->m_invite_user->belongs_org($id_invite,$this->get_org_id())) {
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
	 * Удаление членов организации
	 *
	 * @return void
	 * @author alex.strigin
	 **/
	public function del_staff()
	{
		$this->del_users();
	}

	/**
	 * Удаление админов
	 *
	 * @return void
	 * @author alex.strigin
	 **/
	public function del_admins()
	{
		$this->del_users();
	}

	/**
	 * Удаление пользователей
	 *
	 * @return void
	 * @author alex.strigin
	 **/
	public function del_users()
	{

		/*
		* выбираем список ids на удаление
		*/
		$ids_users = $this->ci->input->post('ids_users');
		/*
		* Если есть, что удалять.
		*/
		if(!empty($ids_users)){

			foreach($ids_users as $id_user){

				/*
				*
				* User не может удалить сам себя
				*/
				if($id_user == $this->get_user_id()){
					throw new AnbaseRuntimeException(lang('no_enough_right'));
				};

				/*
				*
				* ceo может удалять всех без вопросов
				*/
				if($this->is_ceo($this->get_user_id())){
					/*
					*
					* Юзер должен относиться к текущей организации
					*/
					if(is_numeric($id_user) && $this->ci->m_user->is_exists($id_user,$this->get_org_id())){
						/*
						* Если удалить не удалось
						*/
						if(!$this->ci->m_user->delete($id_user)){
							throw new AnbaseRuntimeException(lang('common.delete_error'));
						}
						return;
					}
					throw new AnbaseRuntimeException(lang('no_enough_right'));
				}else{

					/*
					* Админ может удалять всех кроме админов
					*/
					if(is_numeric($id_user) && $this->ci->m_user->is_exists($id_user,$this->get_org_id()) && !$this->is_admin($id_user)){
						/*
						* Если удалить не удалось
						*/
						if(!$this->ci->m_user->delete($id_user)){
							throw new AnbaseRuntimeException(lang('common.delete_error'));
						}
						return;	
					}
					throw new AnbaseRuntimeException(lang('no_enough_right'));
				}
			}
			return;
		}
		throw new AnbaseRuntimeException(lang('common.delete_error'));
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
		*
		* При добавлении инвайта обязательно должен быть задан email и быть уникальным, а manager_id может быть не задан, но если
		* задан то обязательно должен быть нормальным id и принадлежать к текущей организации
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
		/*
		* Валидация не пройдена, генерим исключение
		*/
		throw new ValidationException(array('manager_id' => $this->ci->form_validation->error('manager_id'), 'email'=>$this->ci->form_validation->error('email')));
	}

	/**
	 * Добавление заявки.
	 * [ my_notice: видимо с дуру запихал методы работы с заявкой в admin_users... надо бы перенести в admin_order ]
	 * @return void
	 * @author alex.strigin
	 **/
	public function add_order()
	{
		/*
		* Устанавливаем правила валидации	
		*/
		$insert_fields = array('number','create_date','category','deal_type','price','description','delegate_date','finish_date','phone','state');

		$this->ci->form_validation->set_rules($this->ci->m_order->insert_validation_rules);

		if($this->ci->form_validation->run($this->ci->m_order)){

			/*
			* Валидацию прошли, теперь можем наши данные собрать воедино вместе с пред. определенными
			*/
			$insert_data = array_intersect_key($this->ci->input->post(),array_flip($insert_fields));
			$insert_data['org_id'] = $this->get_org_id();

			if( ($org_id = $this->ci->m_order->insert($insert_data,true))){
				return $org_id;
			}

			throw new AnbaseRuntimeException(lang('common.insert_error'));
		}

		$errors_validation = array();

		if(has_errors_validation($insert_fields,$errors_validation)){
			throw new ValidationException($errors_validation);
		}
		return false;
	}
	
	/**
	 * Удаление заказов
	 *
	 * @return void
	 * @author alex.strigin
	 **/
	public function del_orders()
	{
		$orders_ids = $this->ci->input->post('orders_ids');

		if(is_array($orders_ids)){
			foreach($orders_ids as $order_id){

				if(is_numeric($order_id) && $this->ci->m_order->is_exists($order_id,$this->get_org_id())) {
					/*
					* Будем просто удалять, а получилось или нет, это уже не наша забота. 
					* P.S Если пользователь не хакер, то все получится.
					*/
					$this->ci->m_order->delete($order_id);
				}
			}
			return;
		}
		throw new AnbaseRuntimeException(lang('common.not_legal_data'));
	}

	/**
	 * Назначение заявки члену персонала
	 *
	 * @return void
	 * @author alex.strigin
	 **/
	public function delegate_order()
	{
		/*
		* Правила валидации прикрепляем
		*/
		$this->ci->form_validation->set_rules($this->ci->m_order_user->delegate_validation_rules);

		if($this->ci->form_validation->run($this->ci->m_order_user)){

			/*
			*
			* Т.к к одной заявке может быть привязан только один человек, то для удаления записи в таблице orders_users
			* достаточно знать order_id
			*/
			$order_id = $this->ci->input->post('order_id');
			$user_id  = $this->ci->input->post('user_id');
			if($this->ci->m_order_user->delegate_order($order_id,$user_id)){
				return true;
			}
			throw new AnbaseRuntimeException(lang("common.insert_error"));
		}

		$errors_validation = array();

		if(has_errors_validation(array('order_id','user_id'),$errors_validation)){
			throw new ValidationException($errors_validation);
		}
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


