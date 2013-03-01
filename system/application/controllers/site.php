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

		$this->load->library('users/users');
		if($this->users->is_logged_in()){
			redirect($this->users->resolve_user_redirect_uri());
		}else{
			$loginBox = Modules::run('users/auth/login');
			$this->template->set('loginBox',$loginBox);
		}
	}

	public function migration()
	{
		$this->load->library('migration');

		if ( ! $this->migration->latest())
		{
			show_error($this->migration->error_string());
		}
	}

	/*
	*	Главная страница:
	*		- Форма входа
	*		- Краткий обзор системы
	*/
	public function index(){

		$this->load->library('users/users');	
		$this->template->build('site/index');
	}

	/*
	*
	*	Главная страница -> "О нас"
	*
	*/
	public function company(){

		$this->load->library('users/users');
		$this->template->build('site/company');
	}

	/*
	*
	*	Главная страница -> "Цены"
	*
	*/
	public function prices(){
		$this->load->library('users/users');
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
	*	faq всякий
	*/
	public function faq($section='admin'){
		$this->load->helper('directory');

		$sections = array('agent','manager','admin');

		/*
		* Основные данные
		*/
		if(!in_array($section,$sections)){
			$section = 'admin';
		}

		/*
		* В директории $section будут находится только файлы
		*/
		$section_map = directory_map(APPPATH."views/site/faq/$section");

		$page = $this->input->get('page')?$this->input->get('page'):'main.html';

		if(!in_array($page.EXT,$section_map)){
			$page = 'main';
		}

		/*
		* Загружаем шаблон и все с ним связанное
		*/
		$this->template->set_partial('faq_navigation','site/faq/faq_navigation');
		switch($section){
			case 'admin':
				$this->template->set('loginBox',$this->load->view("site/faq/$section/navigation",array(),true));
				break;
			case 'manager':
				$this->template->set('loginBox',$this->load->view("site/faq/$section/navigation",array(),true));
				break;
			case 'agent':
				$this->template->set('loginBox',$this->load->view("site/faq/$section/navigation",array(),true));
				break;
		}

		$this->template->build("site/faq/$section/$page");
	}

	/**
	 * Страницы демо аккаунтов
	 *
	 * @return void
	 * @author 
	 **/
	public function demo()
	{
		$this->template->build('site/demo');
	}

	/*
	*	handler 404 request
	*/
	public function page404(){
		$this->template->build('site/404');
	}

}

/* End of file Site.php */
/* Location: ./application/controllers/site.php */