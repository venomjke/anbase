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
	public function email()
	{
			/********************************************/
			$from_email = 'ore-4@flywebstudio.ru';
			$from_pass  = '4cL0cV3g';
			/********************************************/


			$text = "";
			$subject = '';
			$file = '../spam/mails1.txt';
			$fileText = '../spam/mailText.txt';
			$fileSubject = '../spam/mailSubject.txt';

			$email_content = file_get_contents(FCPATH.$file);
			if(empty($email_content)){ echo "no content load"; return; }

			$email_content = preg_split('/\s/',$email_content);
			if(empty($email_content)){ echo "no content after explode"; return; }

			$text = file_get_contents(FCPATH.$fileText);
			if(empty($text)){ echo "no mail text load"; return;}


			$subject = file_get_contents(FCPATH.$fileSubject);
			if(empty($subject)){ echo "no mail subject load"; return;}

			$this->load->library('email');
			$config['smtp_host'] = 'smtp.flywebstudio.ru';
			$config['smtp_port'] = 25;
			$config['smtp_user'] = $from_email;
			$config['smtp_pass'] = $from_pass;
			$this->email->initialize($config);

			foreach($email_content as $email_record){
				if(!empty($email_record)){
					echo "email_record is $email_record <br/>";
					$this->email->set_newline("\r\n");
					$this->email->from($from_email, '');
					$this->email->to($email_record); 
					$this->email->subject($subject);
					$this->email->message($text);	
					// Set to, from, message, etc.

					$result = $this->email->send();
				}
			}
			echo "spam finished";
			
	}
	public function migration()
	{
		$this->load->library('migration');
		$this->migration->latest();
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
		// $this->load->view('site/404');
	}

}

/* End of file Site.php */
/* Location: ./application/controllers/site.php */