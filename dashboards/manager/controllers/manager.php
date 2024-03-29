<?php defined("BASEPATH") or die("No direct access to script");



/**
* @author  - Alex.strigin
* @company - Flyweb 
*/
class Manager extends MX_Controller
{
	
	function __construct()
	{
		parent::__construct();

		/*
		*
		*	Загрузка основных либ
		*
		*/
		$this->load->library('manager/Manager_Users');


		if(!$this->manager_users->is_logged_in_as_manager()){
			redirect('');
		}
	}
	/**
	 * redirect на manager/orders
	 *
	 * @return void
	 * @author Alex.strigin
	 * @company Flyweb
	 **/
	public function index ()
	{
		redirect("manager/orders");
	}
}