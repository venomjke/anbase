<?php

/**
* undocumented class
*
* @package default
* @author 
*/
class Settings extends MX_Controller
{
	
	function __construct()
	{
		parent::__construct();
		/*
		* Загрузка либ
		*/

		$this->load->library("admin/Admin_Users");
		$this->load->library("admin/Admin_Settings");
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
		$this->template->append_metadata('<script type="text/javascript">$(function(){admin.init({ baseUrl:"'.site_url("admin/settings").'"}); admin.settings.init(); });</script>');
	}

	/**
	 * Отображение профиля
	 *
	 * @return void
	 * @author alex.strigin
	 **/
	public function index()
	{
		$this->template->build("settings/info",array('settings'=>$this->admin_settings->get_settings_org()));
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
			$response = array();
			try{

				$this->admin_settings->edit();
				$response['code'] = 'success_edit_settings';
				$response['data'] = lang('success_edit_settings');
			}catch(ValidationException $ve){

				$response['code'] = 'error_edit_settings';
				$response['data']['errorType'] = 'validation';
				$response['data']['errors'] = $ve->get_error_messages();
			}catch(AnbaseRuntimeException $re){

				$response['code'] = 'error_edit_settings';
				$response['data']['errorType'] = 'runtime';
				$response['data']['errors'] = array($re->get_error_message());
			}
			$this->ajax->build_json($response);
		}else{
			redirect('admin/settings');
		}
	}

}// END Profile class