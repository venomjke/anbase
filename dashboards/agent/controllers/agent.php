<?php defined("BASEPATH") or die("No direct access to script");



/**
*
*	@author  - Alex.strigin <apstrigin@gmail.com>
*	@company - Flyweb 
*	
*/
class Agent extends MX_Controller{


	public function __construct(){
		parent::__construct();
	
		/*
		*	Загрузка основных либ
		*/
		$this->load->library('agent/agent_users');
		/*
		*	доступ есть только у агентов
		*/
		if(!$this->agent_users->is_logged_in_as_agent()){

			redirect('');
		}
	}

	public function index(){

		echo "Hello world and my dear AGENT!";
	}
}