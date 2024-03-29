<?php defined("BASEPATH") or die("No direct access to script");


/**
 * Класс, реализующий логику контроллера заявок агента
 *
 * @package anbase
 * @author Alex.strigin
 **/
class Orders extends MX_Controller
{
	/**
	 * конструктор
	 *
	 * @return void
	 * @author Alex.strigin
	 **/
	public function __construct()
	{
		parent::__construct();

		/*
		* Загрузка драйверов
		*/
		$this->load->driver('Minify');
		/*
		*	Загрузка либ
		*/
		$this->load->library("agent/Agent_Users");
		$this->load->library("agent/Agent_Orders");
		$this->load->library("Settings_Organization");
		$this->load->library("Ajax");

		if(!$this->agent_users->is_logged_in_as_agent()){
			redirect('');
		}

		/*
		* Загрузка пакета языковых сообщений
		*/
		$this->load->language('agent/messages');
		/*
		*	Загрузка доп. моделей
		*/
		$this->load->model('m_region');
		$this->load->model('m_metro');
		$this->load->model('m_metro_image');
		$this->load->model('m_region_image');


		$this->template->set_theme('dashboard');
		$this->template->set_partial('dashboard_head','dashboard/dashboard_head');
		$this->template->set_partial('dashboard_user','dashboard/dashboard_user');
		$this->template->set_partial('dashboard_menu','dashboard/dashboard_menu');



		$this->_load_app_assets();
		
		$this->template->append_metadata('<script type="text/javascript" src="'.site_url("dashboards/agent/js/agent.js").'"></script>');
		$this->template->append_metadata('<script type="text/javascript">$(function(){agent.init({baseUrl:"'.site_url("agent/orders").'"});agent.orders.init();});</script>');

	}

	private function _load_app_assets()
	{

		$this->settings_org = $this->settings_organization->get_settings_org();
		/*
		* [my_notice: Не знаю, как лучше обыграть задачу загрузки ресурсов, но пока так.]
		*/
		$settings_org = json_encode($this->settings_org);
		$assets[] = "common.settings_org=".$settings_org;

		$regions = $this->m_region->get_region_list("json");
		$assets[] = "common.regions=".$regions;

		$metros  = $this->m_metro->get_metro_list("json");
		$assets[] = "common.metros=".$metros;

		$metros_images = $this->m_metro_image->get_images();
		$assets[] = "common.metros_images=".$metros_images;

		$regions_images = $this->m_region_image->get_images();
		$assets[] = "common.regions_images=".$regions_images;

		$this->load->model('m_order');
		$category_list = json_encode($this->m_order->get_category_list());
		$assets[] = "common.category_list=".$category_list;

		$dealtype_list = json_encode($this->m_order->get_dealtype_list());
		$assets[] = "common.dealtype_list=".$dealtype_list;

		$finishstatus_list = json_encode($this->m_order->get_finishstatus_list());
		$assets[] = "common.finishstatus_list=".$finishstatus_list;
		/*
		* это дополнительно, чтобы в конце был знак ;
		*/
		$assets[]="";

		/*
		* Подключение скриптов
		*/
		$this->template->append_metadata('<script type="text/javascript" src="'.base_url().'themes/dashboard/js/slick_grid/slick.remotemodel.js"></script>');
		$this->template->append_metadata('<script type="text/javascript">common.regions='.implode(';',$assets).'</script>');
	}

	/**
	 * Маленьки маршрутизатор
	 *
	 * @return void
	 * @author Alex.strigin
	 **/
	public function _remap()
	{
		$action = $this->input->get('act')?$this->input->get('act'):'view';

		/*
		* Обрабатываем действие
		*/
		switch ($action) {
			case 'view':
			default:

				/*
				* Обработка действия view: выбираем section, и отображаем её
				*/
				$section = $this->input->get('s')?$this->input->get('s'):'my';
				/*
				* задаем текущую секцию, и загружаем вкладки.
				*/
				$this->template->set('current',$section);
				$this->template->set('settings_org',$this->settings_org);
				$this->template->set_partial('dashboard_tabs','dashboard/dashboard_tabs');
				$this->template->set_partial('dashboard_filter','dashboard/dashboard_filter');
				switch ($section) {
					case 'my':
					default:
						/*
						* Отображение заявок агента
						*/
						$this->_view();
						break;
					case 'free':
						/*
						* Отображение свободных
						*/
						$this->_free_orders();
						break;
					case 'off':
						/*
						* Отображение отключенных заявок
						*/
						$this->_off_orders();
					break;		
				}
				break;
			case 'edit':
				/*
				* Редактирование cвоих заявок
				*/
				$this->_edit_order();
				break;
			case 'print':
				/*
				* Распечатываем заявки ( мои )
				*/
				$this->_print_orders();
				break;
			case 'comments':
				$this->_comments();
				break;
		}
	}

	/**
	 * Отображение заявок агента
	 *
	 * @return void
	 * @author 
	 **/
	private function _view(){

		/*
		* Загрузка данных происходит после загрузки страницы, поэтому если ajax, то загрузка.
		*/
		if($this->ajax->is_ajax_request()){
			$response = array();
			try{
				$orders = $this->agent_orders->get_all_agent_orders();
				$response['code'] = 'success_load_data';
				$response['data'] = $orders;
			}catch(AnbaseRuntimeException $re){
				$response['code'] = 'error_load_data';
				$response['data']['errorType'] = 'runtime';
				$response['data']['errors'] = array($re->get_error_message());
			}catch(ValidationException $ve){
				$response['code'] = 'error_load_data';
				$response['data']['errorType'] = 'validation';
				$response['data']['errors'] = $ve->get_error_messages();
			}
			$this->ajax->build_json($response);
		}else{
			/*
			*	Вывод данных
			*/
			$this->template->build('orders/view');
		}
	}



	private function _free_orders(){

		/*
		* Загрузка данных происходит после загрузки страницы, поэтому если не ajax, просто загружаем страницу.
		*/

		if($this->ajax->is_ajax_request()){
			$response = array();
			try{
				$orders = $this->agent_orders->get_all_free_orders();
				$response['code'] = 'success_load_data';
				$response['data'] = $orders;
			}catch(AnbaseRuntimeException $re){
				$response['code'] = 'error_load_data';
				$response['data']['errorType'] = 'runtime';
				$response['data']['errors'] = array($re->get_error_message());
			}catch(ValidationException $ve){
				$response['code'] = 'error_load_data';
				$response['data']['errorType'] = 'validation';
				$response['data']['errors'] = $ve->get_error_messages();
			}
			$this->ajax->build_json($response);
		}else{
			/*
			*	Вывод данных
			*/
			$this->template->build('orders/free');
		}
	}

	private function _off_orders()
	{
		if($this->ajax->is_ajax_request()){
			$response = array();
			try{
				$orders = $this->agent_orders->get_all_off_orders();
				$response['code'] = 'success_load_data';
				$response['data'] = $orders;
			}catch(AnbaseRuntimeException $re){
				$response['code'] = 'error_load_data';
				$response['data']['errorType'] = 'runtime';
				$response['data']['errors'] = array($re->get_error_message());
			}catch(ValidationException $ve){
				$response['code'] = 'error_load_data';
				$response['data']['errorType'] = 'runtime';
				$response['data']['errors'] = $ve->get_error_messages();
			}
			$this->ajax->build_json($response);
		}else{
			/*
			* Вывод страницы и скрипта загрузки таблицы
			*/
			$this->template->build('orders/off');
		}
	}
	/**
	 * Редактирование заявки
	 *
	 * @return void
	 * @author alex.strigin
	 **/
	private function _edit_order()
	{
		/*
		* Редактирование возможно только с использованием ajax
		*/
		if($this->ajax->is_ajax_request()){
			$response = array();
			try{
				$this->agent_orders->edit_order();
				$response['code'] = 'success_edit_order';
				$response['data'] = lang('success_edit_order');
			}catch(AnbaseRuntimeException $re){
				$response['code'] = 'error_edit_order';
				$response['data']['errors'] = array($re->get_error_message());
			}catch(ValidationException $ve){
				$response['code'] = 'error_edit_order';
				$response['data']['errors'] = $ve->get_error_messages();
			}
			$this->ajax->build_json($response);
		}else{
			redirect($this->agent_users->get_home_page());
		}
	}


	private function _print_orders()
	{
		$this->load->library('table');
		try{
			$orders = $this->agent_orders->get_print_orders();
			$this->template->build('orders/print',array('orders'=>$orders));
		}catch(AnbaseRuntimeException $re){
			redirect($this->admin_users->get_home_page());
		}catch(ValidationException $ve){
			redirect($this->admin_users->get_home_page());
		}
	}

	private function _comments()
	{
		if($this->ajax->is_ajax_request()){
			try{
				$comments = $this->agent_orders->update_comments();
				$response['code'] = 'success_comments';
				$response['data'] = $comments;
			}	catch (ValidationException $ve){
				$response['code'] = 'error_comments';
				$response['data']['errorType'] = 'validation';
				$response['data']['errors'] = $ve->get_error_messages();
			} catch (AnbaseRuntimeException $re){
				$response['code'] = 'error_comments';
				$response['data']['errorType'] = 'runtime';
				$response['data']['errors'] = array($re->get_error_message());
			}
			$this->ajax->build_json($response);
		} else {
			redirect($this->agent_users->get_home_page());
		}
	}

} // END class Orders extends MX_Controller