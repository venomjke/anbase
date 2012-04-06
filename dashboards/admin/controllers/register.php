<?php defined("BASEPATH") or die("No direct access to script");

/**
* Класс, реализующий логику контроллера, отвечающего за регистрацию пользователей
*
* @package default
* @author alex.strigin
*/
class Register extends MX_Controller
{
	
	function __construct()
	{
		/*
		*
		* Загрузка либ
		*/
		$this->load->library('admin/Admin_Users');
		$this->load->library('Ajax');


		/*
		*
		* К регистрации допускаются только "обычные" люди
		*/
		if($this->admin_users->is_logged_in() or !$this->admin_users->has_invite()){
			redirect();
		}

		/*
		* Загрузка пакета сообщений
		*/
		$this->load->language('admin/messages');

		/*
		*
		* Настройка шаблона
		*/
		$this->template->set_theme('start');
		$this->template->set_partial('menu','common/menu',array('current' =>''));

		/*
		* Загрузка мета инфы всякой
		*
		*/
		$this->template->append_metadata('<script type="text/javascript" src="'.site_url("dashboards/admin/js/register.js").'"> </script>');
		$this->template->append_metadata('<script type="text/javascript"> register.init({baseUrl:"'.base_url().'"}); </script>');
	}


	/**
	 * Внезависимости от указанного uri юзер будет попадать сюда для регистрации себя в качестве админа
	 *
	 * @return void
	 * @author alex.strigin
	 **/
	public function _remap()
	{
		/*
		* Загрузка инвайта
		*/
		$invite = $this->admin_users->load_invite($this->input->get('key'),$this->input->get('email'));

		/*
		* данные шаблона
		*/
		$data['invite'] = $invite;

		/*
		* Контейнер данных для ajax ответа
		*/
		$response = array();
		try{

			/*
			* Если пользователь только попал на страницу, то он получит форму для регистрации
			*/
			if($this->admin_users->register($invite)){

				if($this->ajax->is_ajax_request()){
					$response['code'] = 'success_register_admin';
					$response['data'] = lang('success_register_admin');
					$this->ajax->build_json($response);
				}else{
					redirect('');
				}
			}else{
				$this->template->build('register',$data);
			}
		}catch(ValidationException $ve){
			if($this->ajax->is_ajax_request()){
				$response['code'] = 'error_register_admin';
				$response['data']['errors'] = $ve->get_error_messages();
				$this->ajax->build_json($response);
			}else{
				$this->template->build('register',$data);
			}
		}catch(RuntimeException $re){
			if($this->ajax->is_ajax_request()){
				$response['code'] = 'error_register_admin';
				$response['data']['errors'] = array($re->get_error_message());
				$this->ajax->build_json($response);
			}else{
				$data['runtime_error'] = $re->get_error_message();
				$this->template->build('register',$data);
			}
		}
	}
}// END Register class
