<?php

/**
* Класс, реализующий логику контроллера, отвечающего за действия над профилем
*
* @package anbase
* @author Alex.strigin
*/
class Profile extends MX_Controller
{
	
	function __construct()
	{
		parent::__construct();
		/*
		*
		*	Загрузка либ
		*
		*/
		$this->load->library('agent/Agent_Users');

		/*
		*
		* Контроль входа	
		*
		*/
		if(!$this->agent_users->is_logged_in_as_agent()){
			redirect('');
		}

		$this->template->set_theme('dashboard');
		$this->template->set_partial("dashboard_head","dashboard/dashboard_head");
	}


	/**
	 * Редирект на маршрутизатор profile/view
	 *
	 * @return void
	 * @author Alex.strigin
	 **/
	public function index()
	{
		redirect('agent/profile/view');
	}


	/**
	 * Маршрутизатор профиля
	 *
	 * @return void
	 * @author alex.strigin
	 **/
	public function view()
	{
		$section = $this->input->get('s');

		if (!empty($section)) {
			/*
			*
			*  Запуск действия
			*/
			switch ($section) {
				case 'personal':
					/*
					*
					* Персональная информация
					*
					*/
					$this->_personal_profile();
					break;
				case 'org':

					/*
					*
					*	Организационная информация
					*/
					$this->_organization_profile();
				break;
				case 'system':
					/*
					*
					*	Системная информация
					*
					*/
					$this->_system_info();
				break;
				default:
					/*
					*
					* действие по умолчанию
					*/

					$this->_personal_profile();
					break;
			}
		} else {
			/*
			*
			* Запуск действия по умолчанию
			*/
			$this->_personal_profile();
		}
		
	}

	/**
	 * Личная информация
	 *
	 * @return void
	 * @author alex.strigin
	 **/
	private function _personal_profile()
	{	
		$this->template->set_partial("dashboard_tabs","dashboard/profile/tabs");

		$this->template->build("profile/personal");
	}

	/**
	 * Организационная информация
	 *
	 * @return void
	 * @author alex.strigin
	 **/
	private function _organization_profile()
	{
		$this->template->set_partial("dashboard_tabs","dashboard/profile/tabs");

		$this->template->build("profile/organization");
	}

	/**
	 * Информация об аккаунте
	 *
	 * @return void
	 * @author 
	 **/
	private function _system_info()
	{
		$this->template->set_partial("dashboard_tabs","dashboard/profile/tabs");

		$this->template->build("profile/system_info");
	}
}// END Profile class