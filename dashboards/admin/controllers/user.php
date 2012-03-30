<?php

/**
* Класс, реализующий логику контроллера, обеспечивающего выполнение различных операций над пользователями
** @package default
* @author alex.strigin
*/
class User extends MX_Controller
{
	/**
	 * Конструктор
	 *
	 * @return void
	 * @author alex.strigin
	 **/
	public function __construct()
	{
		/*
		*
		* Загрузка либ
		*
		*/

		$this->load->library('admin/Admin_Users');
		$this->load->library('Ajax');

		if(!$this->admin_users->is_logged_in_as_admin()){
			redirect('');
		}

		/*
		*
		* Загрузка пакета сообщений
		*
		*/
		$this->load->language('admin/messages');
		/*
		*
		* настройка шаблона
		*
		*/
		$this->template->set_theme('dashboard');
		$this->template->set_partial('dashboard_head','dashboard/dashboard_head');
	
		/*
		*
		*	Загрузка скриптов и всякой другой мета инфы
		*
		*/
		$this->template->append_metadata('<script type="text/javascript" src="'.site_url("dashboards/admin/js/admin.js").'"></script>');
		$this->template->append_metadata('<script type="text/javascript">
			admin.init({ baseUrl:"'.base_url().'"})
		</script>');

	}


	/**
	 * Редирект на user/view/?s=admins
	 *
	 * @return void
	 * @author alex.strigin
	 **/
	public function index()
	{
		redirect('admin/user/staff');
	}


	/**
	 * Управление разделом сотрудники
	 *
	 * @return void
	 * @author alex.strigin
	 **/
	public function staff()
	{
		$section = $this->input->get('act')?$this->input->get('act'):'view';

		switch ($section) {
			case 'view':
				/*
				* Отображение списка сотрудников
				*
				*/
				$this->_view_staff();
				break;
			case 'del':
				/*
				*
				* Удаление списка сотрудников
				*/
				$this->_del_staff();
			break;
			default:
				/*
				*
				* По умолчанию вывод списка сотрудников
				*
				*/
				$this->_view_staff();
				break;
		}
		
	}

	/**
	 * Управление разделом админы
	 *
	 * @return void
	 * @author alex.strigin
	 **/
	public function admins()
	{
		$section = $this->input->get('act')?$this->input->get('act'):'view';

		switch ($section) {
			case 'view':
				/*
				* Отображение списка администраторов
				*
				*/
				$this->_view_admins();
				break;
			case 'del':
				/*
				*
				* Удаление списка администраторов
				*/
				$this->_del_admins();
			break;
			default:
				/*
				*
				* По умолчанию вывод списка администраторов
				*
				*/
				$this->_view_admins();
				break;
		}
	
	}

	/**
	 * Вывод списка администраторов
	 *
	 * @return void
	 * @author alex.strigin
	 **/
	private function _view_admins()
	{
		$this->template->set_partial('sidebar','dashboard/user/sidebar');

		$admins = $this->admin_users->get_list_admins();

		$this->template->build('user/admins',array('admins' => $admins));
	}

	/**
	 * Вывод списка членов организации
	 *
	 * @return void
	 * @author alex.strigin
	 **/
	private function _view_staff()
	{
		$this->template->set_partial('sidebar','dashboard/user/sidebar');

		$staff = $this->admin_users->get_list_staff();
		$this->template->build('user/staff',array('staff' => $staff));
	}


	/**
	 * Удалить список сотрудников
	 *
	 * @return void
	 * @author alex.strigin
	 **/
	private function _del_staff()
	{
		$this->template->set_partial('sidebar','dashboard/user/sidebar');

		/*
		*
		* Пытаемся удалить пользователей
		*/
		try{

			$this->admin_users->del_staff();

			/*
			*
			* В зависимости от способа передачи подготавливаем и возвращаем овтет.
			*/
			if($this->ajax->is_ajax_request()){
				$response['code'] = 'success_delete';
				$response['data'] = lang('success_delete_staff');
			}else{
				redirect('admin/user/staff');
			}

		}catch( RuntimeException $re ){

			if($this->ajax->is_ajax_request()){
				$response['code'] = 'error_delete_staff';
				$response['data'] = lang('error_delete_staff');
			}else{
				redirect('admin/user/staff');
			}
		}
	}



	/**
	 * Создание и отправка инвайта для 
	 * админа, менеджера, агента
	 *
	 * @return void
	 * @author alex.strigin
	 **/
	public function invites()
	{
		$section = $this->input->get('s');

		if (!empty($section)) {
			switch ($section) {
				case 'agents':
					/*
					*
					*	Инвайты агентам
					*	
					*/
					$this->_agents_invites();
					break;
				case 'managers':
					/*
					*
					*  Инвайты менеджера
					*
					*/
					$this->_managers_invites();
				break;
				case 'admins':
					/*
					*
					*
					* Инвайты админам
					*
					*/
					$this->_admins_invites();
				break;
				default:
					redirect('admin/user/view');
					break;
			}
		} else {
			/*
			*По умолчанию ничего нет, поэтому redirect на user/view 
			*/
			redirect('admin/user/view');
		}
		
	}


	/**
	 * Инвайты админам
	 *
	 * @return void
	 * @author alex.strigin
	 **/
	private function _admins_invites()
	{

	}


	/**
	 * Инвайты менеджерам
	 *
	 * @return void
	 * @author alex.strigin
	 **/
	private function _managers_invites()
	{

	}

	/**
	 * Инвайты агентов
	 *
	 * @return void
	 * @author alex.strigin
	 **/
	private function _agents_invites()
	{

	}

}// END Users class