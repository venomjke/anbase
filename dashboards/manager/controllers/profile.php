<?php

/**
* Класс, реализующий логику контроллера profile, отвечающего за обработку действий над профилем
*
* @package agent
* @author alex.strigin
*/
class Profile extends MX_Controller
{
	
	function __construct()
	{
		parent::__construct();

		/*
		*
		*	
		*	загрузка либ
		*
		*/
		$this->load->library("manager/manager_users");


		if(!$this->manager_users->is_logged_in_as_manager()){
			redirect('');
		}

		$this->template->set_theme('dashboard');
		$this->template->set_partial('dashboard_head','dashboard/dashboard_head');
	}



	/**
	 * Индексный action,redirect
	 *
	 * @return void
	 * @author alex.strigin
	 **/
	public function index()
	{
		redirect('manager/profile/view');
	}


	/**
	 * Маршрутизатор
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
			* обработка действий
			*
			*/

			switch ($section) {
				case 'personal':
					/*
					*
					* личное инфо
					*
					*/
					$this->_personal_profile();
					break;
				case 'org':
					/*
					*
					*	организационное ифно ( менеджер меня его не может )
					*
					*/
					$this->_organization_profile();
				break;
				case 'system':
					/*
					*
					* инфо об аккаунте
					*
					*/
					$this->_system_info();
				break;
				default:
					/*
					*
					* по умолчанию показывает личное инфо
					*/
					$this->_personal_profile();
					break;
			}
		} else {
			/*
			*
			* действие по умолчанию
			*
			*/
			$this->_personal_profile();
		}
		
	}


	/**
	 * Вывод личной инфы
	 *
	 * @return void
	 * @author alex.strigin
	 **/
	private function _personal_profile()
	{
		$this->template->set_partial('dashboard_tabs','dashboard/profile/tabs');


		$this->template->build('profile/personal');
	}


	/**
	 * Орг. инфа
	 *
	 * @return void
	 * @author alex.strigin
	 **/
	private function _organization_profile()
	{
		$this->template->set_partial('dashboard_tabs','dashboard/profile/tabs');

		$this->template->build('profile/organization');
	}


	/**
	 * системное инфо
	 *
	 * @return void
	 * @author alex.strigin
	 **/
	private function _system_info()
	{
		$this->template->set_partial('dashboard_tabs','dashboard/profile/tabs');

		$this->template->build('profile/system_info');
	}
}// END Profile class