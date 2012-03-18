<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');


require APPPATH."libraries/MX/Controller.php";

class Site extends MX_Controller {

	public function index(){

		$this->load->view('site/index');
	}


	/*
	*
	*	handler 404 request
	*
	*
	*/
	public function page404(){
		$this->load->view('site/404');
	}
}

/* End of file Site.php */
/* Location: ./application/controllers/site.php */