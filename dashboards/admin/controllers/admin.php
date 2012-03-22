<?php defined('BASEPATH') or die('No direct access to script');




/**
* @author  - Alex.strigin
* @company - Flyweb 
*/
class Admin extends MX_Controller
{
	
	function __construct()
	{
		parent::__construct();

		/*
		*
		*	Загрузка либ.
		*/
		$this->load->library('admin_users');

		if( !$this->admin_users->is_logged_in_as_admin() ){
			redirect('');
		}

		/*
		*
		*	Настройки темы
		*/
		$this->template->set_theme('dashboard');
		$this->template->set_partial('dashboard_head','dashboard/dashboard_head');
	}


	public function index(){
		
		$this->template->build('index');
	}
}