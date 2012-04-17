<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');


/**
*
*	@author  - Alex.strigin <apstrigin@gmail.com>
*	@company - Flyweb
*
*/
class Site extends MX_Controller {


	public function __construct(){

		parent::__construct();

		$this->template->set_theme('start');
		$this->template->set_partial('menu','common/menu',array("current" => $this->router->fetch_method()));
	}
	/*
	*	Главная страница:
	*		- Форма входа
	*		- Краткий обзор системы
	*/
	public function index(){

		$this->load->library('users/users');
		/*
		*	
		*	Если User залоггинен, то redirect на свою панель, нечего на главной ошиваться;
		*	пусть работет=)
		*/
		if($this->users->is_logged_in()){
			redirect($this->users->resolve_user_redirect_uri());
		}else{
			$loginBox = Modules::run('users/auth/login'); 
			$this->template->set('loginBox',$loginBox);
			$this->template->build('site/index');
		}
	}

	/*
	*
	*	Главная страница -> "О нас"
	*
	*/
	public function company(){
		$this->template->build('site/company');
	}

	/*
	*
	*	Главная страница -> "Цены"
	*
	*/
	public function prices(){
		$this->template->build('site/prices');
	}

	/*
	*
	*	Главная страница -> "О системе"
	*
	*/
	public function about(){
		$this->template->build('site/about');
	}



	/*
	*
	*	faq всякий
	*/
	public function faq(){
		$this->template->build('site/faq');
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