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
		$this->load->library("Ajax");

		if(!$this->admin_users->is_logged_in_as_admin()){
			redirect("");
		}

		/*
		*
		* Загрузка языка
		*
		*/
		$this->load->language("admin/messages");
		/*
		* 
		* Загрузка моделей и настройка шаблона
		*/
		$this->template->set_theme("dashboard");
		$this->template->set_partial("dashboard_head","dashboard/dashboard_head");
	
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
	 * Вывод и изменение личной информации
	 *  Ответ передается с использованием AJAX+JSON
	 * @return void
	 * @author Alex.strigin
	 **/
	private function _personal_profile()
	{
		$this->template->set_partial("dashboard_tabs","dashboard/profile/tabs");


		/*
		* Контейнер результата
		*
		*/
		$response = array();

		/*
		*
		* Если данные переданы ajax'ом, то либо мы патаемся редактировать, либо хз..
		* Во все остальных случаях мы просто выводим форму
		*
		*/
		if($this->ajax->is_ajax_request()){

			/*
			*
			*	Пытаемся отредактировать данные
			*
			*/
			if($this->admin_users->edit_personal_profile()){

				/*
				*
				* Ошибки валидации можно обнаружить только проверив errors
				* после выполнения операции
				*
				*/
				if ($errors = $this->admin_users->get_error_message()) {
					$response['code'] = 'error_edit_profile';
					$response['data']['errors'] = $errors;
				} else {
				
					$response['code'] = 'success_edit_profile';
					$response['data'] = lang('success_edit_personal_profile');
				
				}	
				$this->ajax->build_json($response);
				return true;
			}
			$response['code'] = 'error_edit_profile';
			$response['data']['errors'] = $this->admin_users->get_error_message();

			$this->ajax->build_json($response);
			return TRUE;
		}
		$this->template->build('profile/personal');
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
		/*
		* Контейнер результата
		*
		*/
		$response = array();

		/*
		*
		* Если данные переданы ajax'ом, то либо мы патаемся редактировать, либо хз..
		* Во все остальных случаях мы просто выводим форму
		*
		*/
		if($this->ajax->is_ajax_request()){

			/*
			*
			*	Пытаемся отредактировать данные
			*
			*/
			if($this->admin_users->edit_organization_profile()){

				/*
				*
				* Ошибки валидации можно обнаружить только проверив errors
				* после выполнения операции
				*
				*/
				if ($errors = $this->admin_users->get_error_message()) {
					$response['code'] = 'error_edit_profile';
					$response['data']['errors'] = $errors;
				} else {
				
					$response['code'] = 'success_edit_profile';
					$response['data'] = lang('success_edit_org_profile');
				
				}	
				$this->ajax->build_json($response);
				return true;
			}
			$response['code'] = 'error_edit_profile';
			$response['data']['errors'] = $this->admin_users->get_error_message();

			$this->ajax->build_json($response);
			return TRUE;
		}
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
		/*
		* Контейнер результата
		*
		*/
		$response = array();

		/*
		*
		* Если данные переданы ajax'ом, то либо мы патаемся редактировать, либо хз..
		* Во все остальных случаях мы просто выводим форму
		*
		*/
		if($this->ajax->is_ajax_request()){

			/*
			*
			*	Пытаемся отредактировать данные
			*
			*/
			if($this->admin_users->edit_system_profile()){

				/*
				*
				* Ошибки валидации можно обнаружить только проверив errors
				* после выполнения операции
				*
				*/
				if ($errors = $this->admin_users->get_error_message()) {
					$response['code'] = 'error_edit_profile';
					$response['data']['errors'] = $errors;
				} else {
				
					$response['code'] = 'success_edit_profile';
					$response['data'] = lang('success_edit_system_profile');
				
				}	
				$this->ajax->build_json($response);
				return true;
			}
			$response['code'] = 'error_edit_profile';
			$response['data']['errors'] = $this->admin_users->get_error_message();

			$this->ajax->build_json($response);
			return TRUE;
		}
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