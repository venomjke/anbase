<?php

/**
* undocumented class
*
* @package default
* @author 
*/
class Profile extends MX_Controller
{
	
	function __construct()
	{
		parent::__construct();
		/*
		*
		*
		* Загрузка либ
		*
		*
		*/

		$this->load->library("admin/Admin_Users");


		if(!$this->admin_users->is_logged_in_as_admin()){
			redirect("");
		}

		/*
		* 
		* Загрузка моделей и настройка шаблона
		*/
		$this->template->set_theme("dashboard");
		$this->template->set_partial("dashboard_head","dashboard/dashboard_head");
	}

	/**
	 * Маршрутизирующий метод
	 *
	 * @return void
	 * @author Alex.strigin
	 **/
	public function _remap()
	{
		$section = $this->input->get('s');

		 if (!empty($section)) {
		 	switch ($section) {
		 		case 'personal':
		 			/*
		 			*
		 			* Отображение персональной инфы
		 			*
		 			*/
		 			$this->_personal_profile();
		 			break;
		 		case 'org':
		 			/*
		 			*
		 			*	Отображение инфы организации
		 			*
		 			*/
		 			$this->_organization_profile();
		 		break;
		 		case 'system':
		 			/*
		 			*
		 			* Системная информация
		 			*
		 			*/
		 			$this->_system_info();
		 		break;
		 		default:
		 			/*
		 			*
		 			* действие по умолчанию
		 			*
		 			*/
		 			$this->_organization_profile();
		 			break;
		 	}
		 } else {

		 	/*
		 	*
		 	*	Действие по умолчанию
		 	*
		 	*/
		 	$this->_personal_profile();
		 }
		 
	}


	/**
	 * Вывод личной информации
	 *
	 * @return void
	 * @author Alex.strigin
	 **/
	private function _personal_profile()
	{
		$this->template->set_partial("dashboard_tabs","dashboard/profile/tabs");

		$this->template->build("profile/personal");
	}


	/**
	 * Вывод информации об организации
	 *
	 * @return void
	 * @author Alex.strigin
	 **/
	private function _organization_profile()
	{
		$this->template->set_partial("dashboard_tabs","dashboard/profile/tabs");

		$this->template->build("profile/organization");
	}


	/**
	 * Вывод системной информации
	 *
	 * @return void
	 * @author Alex.strigin
	 **/
	private function _system_info()
	{
		$this->template->set_partial("dashboard_tabs","dashboard/profile/tabs");

		$this->template->build("profile/system_info");
	}

	/**
	 * Редирект на profile/view/?s=personal
	 *
	 * @return void
	 * @author 
	 **/
	public function index()
	{
		redirect("profile/view/?s=personal");
	}
}// END Profile class