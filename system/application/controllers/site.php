<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Site extends CI_Controller {

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