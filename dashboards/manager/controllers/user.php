<?php defined("BASEPATH") or die("No direct access to script");

/**
* Класс, реализующий логику контроллера, обеспечивающего выполнение 
* различных операций над пользователями
*
* @package anbase
* @author alex.strigin
*/
class User extends MX_Controller
{
	
	function __construct()
	{
		/*
		*
		* Загрузка либ
		*
		*/
		$this->load->library('manager/Manager_Users');
		$this->load->library('Ajax');

		if(!$this->manager_users->is_logged_in_as_manager()){
			redirect('');
		}

		/*
		*
		* Загрузка пакета сообщений
		*
		*/
		$this->load->language('manager/messages');


		/*
		*
		* Настройка шаблона
		*
		*/
		$this->template->set_theme('dashboard');
		$this->template->set_partial('dashboard_head','dashboard/dashboard_head');
		$this->template->set_partial('dashboard_user','dashboard/dashboard_user');
		$this->template->set_partial('dashboard_menu','dashboard/dashboard_menu');


		/*
		*
		*	Загрузка скриптов и всякой другой мета инфы
		*
		*/
		$this->template->append_metadata('<script type="text/javascript" src="'.site_url("dashboards/manager/js/manager.js").'"></script>');
		$this->template->append_metadata('<script type="text/javascript">$(function(){manager.init({ baseUrl:"'.site_url("manager/user").'"});});</script>');

	}

	/**
	 * Редирект на user/staff/
	 *
	 * @return void
	 * @author alex.strigin
	 **/
	public function index()
	{
		redirect('manager/user/staff');
	}


	/**
	 * Управление разделом сотрудники
	 *
	 * @return void
	 * @author alex.strigin
	 **/
	public function staff()
	{
		$this->template->set_partial('dashboard_tabs','dashboard/user/tabs',array('current'=>'staff'));
		$section = $this->input->get('act')?$this->input->get('act'):'view';

		switch ($section) {
			case 'view':
			default:
				/*
				* Отображение списка сотрудников
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
		$this->template->set_partial('dashboard_tabs','dashboard/user/tabs',array('current'=>'admins'));
		$section = $this->input->get('act')?$this->input->get('act'):'view';

		switch ($section) {
			case 'view':
			default:
				/*
				* Отображение списка администраторов
				*
				*/
				$this->_view_admins();
				break;
		}
	
	}


	/**
	 * Управление разделом агенты
	 *
	 * @return void
	 * @author alex.strigin
	 **/
	public function agents()
	{
		$this->template->set_partial('dashboard_tabs','dashboard/user/tabs',array('current'=>'agents'));
		$section = $this->input->get('act')?$this->input->get('act'):'view';

		switch ($section) {
			case 'view':
			default:
				/*
				* Отображение списка администраторов
				*
				*/
				$this->_view_agents();
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
		if($this->ajax->is_ajax_request()){
			$response = array();
			try{
				$admins = $this->manager_users->get_list_admins();	
				$response['code'] = 'success_view_user';
				$response['data'] = $admins;
			}catch(ValidationException $ve){
				$response['code'] = 'error_view_user';
				$response['data'] = $ve->get_error_message();
			}catch(AnbaseRuntimeException $re){
				$response['code'] = 'error_view_user';
				$response['data'] = array($re->get_error_message());
			}
			$this->ajax->build_json($response);
			return "";
		}
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
		if($this->ajax->is_ajax_request()){
			$response = array();
			try{
				$staff = $this->manager_users->get_list_staff();	
				$response['code'] = 'success_view_user';
				$response['data'] = $staff;
			}catch(ValidationException $ve){
				$response['code'] = 'error_view_user';
				$response['data'] = $ve->get_error_message();
			}catch(AnbaseRuntimeException $re){
				$response['code'] = 'error_view_user';
				$response['data'] = array($re->get_error_message());
			}
			$this->ajax->build_json($response);
			return "";
		}
		$this->template->build('user/staff');
	}


	/**
	 * Вывод списка членов агентов
	 *
	 * @return void
	 * @author alex.strigin
	 **/
	private function _view_agents()
	{
		if($this->ajax->is_ajax_request()){
			$response = array();
			try{
				$staff = $this->manager_users->get_manager_agents();	
				$response['code'] = 'success_view_user';
				$response['data'] = $staff;
			}catch(ValidationException $ve){
				$response['code'] = 'error_view_user';
				$response['data'] = $ve->get_error_message();
			}catch(AnbaseRuntimeException $re){
				$response['code'] = 'error_view_user';
				$response['data'] = array($re->get_error_message());
			}
			$this->ajax->build_json($response);
			return;
		}
		$this->template->build('user/agents');
	}	
}// END User class