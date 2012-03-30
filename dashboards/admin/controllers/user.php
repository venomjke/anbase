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

		if(!$this->admin_users->is_logged_in_as_admin()){
			redirect('');
		}

		/*
		*
		* настройка шаблона
		*
		*/
		$this->template->set_theme('dashboard');
		$this->template->set_partial('dashboard_head','dashboard/dashboard_head');
	}


	/**
	 * Редирект на user/view/?s=admins
	 *
	 * @return void
	 * @author alex.strigin
	 **/
	public function index()
	{
		redirect('admin/user/view/?s=admins');
	}

	/**
	 * Просмотр списка пользователей
	 *
	 * @return void
	 * @author alex.strigin
	 **/
	public function view()
	{
		/*
		*
		* 2 раздела:
		*	1. admins
		*	2. staff
		*/
		$section = $this->input->get('s');
	
		if (!empty($section)) {
			switch ($section) {
				case 'staff':
					$this->_view_staff();
					break;
				case 'admins':
					$this->_view_admins();
					break;
				default:
					$this->_view_staff();
					break;
			}
		} else {
			/*
			* 
			* По умолчанию показать коллектив агенты/менеджеры
			*/
			$this->_view_staff();
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
		$this->template->build('user/admins');
	}

	/**
	 * Вывод списка членов организации
	 *
	 * @return void
	 * @author alex.strigin
	 **/
	private function _view_staff()
	{
		$this->template->build('user/staff');
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
					redirect('user/view');
					break;
			}
		} else {
			/*
			*По умолчанию ничего нет, поэтому redirect на user/view 
			*/
			redirect('user/view');
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