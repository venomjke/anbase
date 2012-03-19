<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');


/*
*
*	@author  - Alex.strigin <apstrigin@gmail.com>
*	@company - Flyweb
*
*/
class Site extends MX_Controller {


	public function __construct(){

		parent::__construct();

		$this->template->set_theme('start');
	}


	public function index(){

		$this->form_validation->set_rules('test_field','test_field','required');

		if($this->form_validation->run()){
			$this->template->build('site/form');
			return true;
		}

		$this->template->build('site/index');
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