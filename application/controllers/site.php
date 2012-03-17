<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Site extends CI_Controller {

	public function index(){

		$this->load->view('site/index');
	}
}

/* End of file Site.php */
/* Location: ./application/controllers/site.php */