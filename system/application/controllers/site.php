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

	/*
	*	Главная страница:
	*		- Форма входа
	*		- Краткий обзор системы
	*/
	public function index($page = 0){

		$this->load->library('users/users');	
		$this->load->library('pagination');
		$this->load->model('m_news');
		$this->load->helper('russian_date_helper');

		// TODO (alex): вынести лимит записей в конфиг
		$limit = 5;
		$config['uri_segment'] = 2;
		$config['total_rows']  = $this->m_news->total_news();
		$config['per_page']    = $limit;
		$config['base_url']    = site_url('news');  

		$this->pagination->initialize($config); 
		$last_news = $this->m_news->get_last_news(5, $page);
		
		$this->template->build('site/index', array('items' => $last_news, 'pagination' => $this->pagination->create_links()));
	}

	/*
	*
	*	Главная страница -> "О нас"
	*
	*/
	public function company(){

		$this->load->library('users/users');
		$this->load->model('m_page');
		$this->template->build('site/company', array('page' => $this->m_page->get(2)));
	}

	/*
	*
	*	Главная страница -> "Цены"
	*
	*/
	public function prices(){
		$this->load->library('users/users');
		$this->load->model('m_page');
		$this->template->build('site/prices', array('page' => $this->m_page->get(4)));			
	}

	/*
	*
	*	Главная страница -> "О системе"
	*
	*/
	public function about(){
		$this->load->model('m_page');
		$this->template->build('site/about', array('page' => $this->m_page->get(3)));
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
		$this->load->model('m_page');
		$this->template->build('site/demo', array('page' => $this->m_page->get(1)));
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