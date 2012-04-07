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
		$this->load->library('admin/Admin_Users');

		if( !$this->admin_users->is_logged_in_as_admin() ){
			redirect('');
		}

		redirect($this->admin_users->get_home_page());
	}


	public function index(){
	}
}