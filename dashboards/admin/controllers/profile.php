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
		* Загрузка либ
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
		$this->template->set_partial("dashboard_user","dashboard/dashboard_user");
		$this->template->set_partial("dashboard_menu","dashboard/dashboard_menu");
	
		/*
		*	Загрузка скриптов и всякой другой мета инфы
		*/
		$this->template->append_metadata('<script type="text/javascript" src="'.site_url("dashboards/admin/js/admin.js").'"></script>');
		$this->template->append_metadata('<script type="text/javascript">$(function(){admin.init({ baseUrl:"'.site_url("admin/profile").'"}); admin.profile.init(); });</script>');
	}

	/**
	 * Отображение профиля
	 *
	 * @return void
	 * @author alex.strigin
	 **/
	public function index()
	{
		$this->template->build("profile/info");
	}

	/**
	 * Редактирование разделов профиля
	 *
	 * @return void
	 * @author alex.strigin
	 **/
	public function edit()
	{
		/*
		* Редактирование доступно только с использованием ajax
		*/
		if($this->ajax->is_ajax_request()){
			$section = $this->input->get('sct')?$this->input->get('sct'):'';

			switch ($section) {
				case 'personal':
					$this->_edit_personal_profile();
					break;
				case 'system':
					$this->_edit_system_profile();
					break;
				case 'organization':
					$this->_edit_organization_profile();
					break;
				default:
					/*
					* Ничего не делаем, пускай помучаются
					*/
					return;
					break;
			}
		}else{
			redirect('admin/profile');
		}
	}
	/**
	 * Личная информация
	 *
	 * @return void
	 * @author alex.strigin
	 **/
	private function _edit_personal_profile()
	{	
		/*
		*  
		* Если данные переданы с использованием ajax, 
		* значит мы пытаемся редактировать поля, если нет
		* то просто выводим список полей
		*/
		if($this->ajax->is_ajax_request()){
			try{
				$this->admin_users->edit_personal_profile();
				$response['code'] = 'success_edit_profile';
				$response['data'] = lang('success_edit_personal_profile');
			}catch(ValidationException $ve){
				$response['code'] = 'error_edit_profile';
				$response['data']['errorType'] = 'validation';
				$response['data']['errors'] = $ve->get_error_messages();
			}catch(AnbaseRuntimeException $re){
				$response['code'] = 'error_edit_profile';
				$response['data']['errorType'] = 'runtime';
				$response['data']['errors'] = array($re->get_error_message());
			}
			$this->ajax->build_json($response);
		}else{
			redirect("admin/profile");
		}
	}


	/**
	 * Редактирование системный инфы 
	 *
	 * @return void
	 * @author alex.strigin
	 **/
	private function _edit_system_profile()
	{
		if($this->ajax->is_ajax_request()){
			$response = array();
			try{
				$this->admin_users->edit_system_profile();
				$response['code'] = 'success_edit_profile';
				$response['data'] = lang('success_edit_sys_profile');
			}catch(ValidationException $ve){

				$response['code'] = 'error_edit_profile';
				$response['data']['errorType'] = 'validation';
				$response['data']['errors'] = $ve->get_error_messages();
			}catch(AnbaseRuntimeException $re){
				$response['code'] = 'error_edit_profile';
				$response['data']['errorType'] = 'runtime';
				$response['data']['errors'] = array($re->get_error_message());
			}
			$this->ajax->build_json($response);
		}else{
			redirect("admin/profile");
		}
	}

	/**
	 * Редактирование информации об организации
	 *
	 * @return void
	 * @author alex.strigin
	 **/
	private function _edit_organization_profile()
	{
		if($this->ajax->is_ajax_request()){
			$response = array();
			try{
				$this->admin_users->edit_organization_profile();
				$response['code'] = 'success_edit_profile';
				$response['data'] = lang('success_edit_org_profile');
			}catch(ValidationException $ve){

				$response['code'] = 'error_edit_profile';
				$response['data']['errorType'] = 'validation';
				$response['data']['errors'] = $ve->get_error_messages();
			}catch(AnbaseRuntimeException $re){
				$response['code'] = 'error_edit_profile';
				$response['data']['errorType'] = 'runtime';
				$response['data']['errors'] = array($re->get_error_message());
			}
			$this->ajax->build_json($response);
		}else{
			redirect("admin/profile");
		}
	}

}// END Profile class