<?php defined("BASEPATH") or die("No direct access to script");

/**
* Контроллер, отвечающий за регистрацию пользователей ( агентов ).
*
* @package default
* @author alex.strigin
*/
class Register extends MX_Controller
{
	
	function __construct()
	{
		/*
		* Загрузка либ
		*
		*/
		$this->load->library('agent/Agent_Users');
		$this->load->library('Ajax');
		/*
		* Загрузка пакета сообщений
		*
		*/
		$this->load->language('agent/messages');

		$this->template->set_theme('start');
		$this->template->set_partial('menu','common/menu',array('current' => ''));

		/*
		* Загрузка мета инфы всякой
		*/		
		$this->template->append_metadata('<script type="text/javascript" src="'.base_url().'themes/dashboard/js/common/register.js'.'"></script>');
		$this->template->append_metadata('<script type="text/javascript" src="'.base_url().'dashboards/agent/js/register.js'.'"> </script>');
		$this->template->append_metadata('<script type="text/javascript">$(function(){register.init({baseUrl:"'.base_url().'"});});</script>');

	}


	/**
	 * Штука,которая будет выполнять все, что касается регистрации агента
	 *
	 * 
	 * @return void
	 * @author alex.strigin
	 **/
	public function index()
	{

		/*
		*
		* Обычных агентов мы сюда не пускаем, только тех, кто хочет зарегистрироваться, 
		* а их мы определяем по инвайтам
		*
		*/
		if($this->agent_users->is_logged_in() or !$this->agent_users->has_invite()){
			redirect();
		}

		/*
		* Загрузка инвайта
		*/
		$invite = $this->agent_users->load_invite($this->input->get('key'),$this->input->get('email'));
		/*
		* данные для шаблона
		*/
		$data = array();

		$data['invite'] = $invite;
		$this->template->set('loginBox',$this->load->view('users/login',array(),true));
		try{

			/*
			* Если пользователь попал сюда первый раз, то ему надо отобразить форму
			*/
			if($this->agent_users->register($invite)){
				$this->session->set_flashdata('redirect',true);
				redirect('agent/register/redirect');
			}else{
				$this->template->build('register',$data);
			}
		}catch(ValidationException $ve){
			$this->template->build('register',$data);
		}catch(RuntimeException $re){
			$data['runtime_error'] = $re->get_error_message();
			$this->template->build('register',$data);
		}
	}


	public function redirect(){
		if($this->session->flashdata('redirect')){
			$this->template->set('loginBox',$this->load->view('users/login',array(),true));
			$this->template->build('register/redirect');
		}else{
			redirect('');
		}
	}
}// END Register class