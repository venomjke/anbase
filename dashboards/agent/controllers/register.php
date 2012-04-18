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
		*
		* Обычных агентов мы сюда не пускаем, только тех, кто хочет зарегистрироваться, 
		* а их мы определяем по инвайтам
		*
		*/
		if($this->agent_users->is_logged_in() or !$this->agent_users->has_invite()){
			redirect();
		}
		/*
		* Загрузка пакета сообщений
		*
		*/
		$this->load->language('agent/messages');

		$this->template->set_theme('start');
		$this->template->set_partial('menu','common/menu',array('current' => ''));

		/*
		*
		* Загрузка мета инфы всякой
		*
		*/		
		$this->template->append_metadata('<script type="text/javascript" src="'.site_url("dashboards/agent/js/register.js").'"> </script>');
		$this->template->append_metadata('<script type="text/javascript">$(function(){register.init({baseUrl:"'.base_url().'"});});</script>');

	}


	/**
	 * Штука,которая будет выполнять все, что касается регистрации агента
	 *
	 * 
	 * @return void
	 * @author alex.strigin
	 **/
	public function _remap()
	{
		/*
		* Загрузка инвайта
		*/
		$invite = $this->agent_users->load_invite($this->input->get('key'),$this->input->get('email'));
		/*
		* данные для шаблона
		*/
		$data = array();

		$data['invite'] = $invite;

		try{

			/*
			* Если пользователь попал сюда первый раз, то ему надо отобразить форму
			*/
			if($this->agent_users->register($invite)){
				/*
				*
				* Если запрос аяксовый, то отправляем код об успешной регистрации, если нет, redirect на главную
				*
				*/
				if($this->ajax->is_ajax_request()){
					$response['code'] = 'success_register_agent';
					$response['data'] = lang('success_register_agent');
					$this->ajax->build_json($response);
				}else{
					redirect('');
				}
			}else{
				/*
				* Отображение формы
				*/
				$this->template->build('register',$data);
			}
		}catch(ValidationException $ve){
			if($this->ajax->is_ajax_request()){
				$response['code'] = 'error_register_agent';
				$response['data']['errors'] = $ve->get_error_messages();
				$this->ajax->build_json($response);
			}else{
				$this->template->build('register',$data);
			}
		}catch(RuntimeException $re){

			if($this->ajax->is_ajax_request()){
				$response['code'] = 'error_register_agent';
				$response['data']['errors'] = array($re->get_error_message());
				$this->ajax->build_json($response);
			}else{
				$data['runtime_error'] = $re->get_error_message();
				$this->template->build('register',$data);
			}
		}
	}
}// END Register class