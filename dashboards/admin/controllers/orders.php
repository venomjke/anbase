<?php defined("BASEPATH") or die("No direct access to script");



/**
 * Контроллер заявок админа
 *
 * @package Anbase
 * @author Alex.strigin 
 **/
class Orders extends MX_Controller
{

	public function __construct(){

		parent::__construct();

		$this->load->library("admin/Admin_Users");
		$this->load->library("admin/Admin_Orders");
		$this->load->library("Ajax");

		if( !$this->admin_users->is_logged_in_as_admin() ){
			redirect("");
		}

		/*
		*	загрузка моделей
		*/
		$this->load->model('m_region');
		$this->load->model('m_metro');

		/*
		*
		* Загрузка языковых сообщений
		*/
		$this->load->language('admin/messages');

		/*
		* Настройка шаблона
		*/
		$this->template->set_theme("dashboard");
		$this->template->set_partial("dashboard_head","dashboard/dashboard_head");
		
		/*
		* Загружаем другую метаинфу
		*/
		$regions = $this->m_region->get_region_list("json");
		$metros  = $this->m_metro->get_metro_list("json");

		$this->template->append_metadata('<script type="text/javascript" src="'.site_url('dashboards/admin/js/admin.js').'">$(function(){admin.init({baseUrl:"'.base_url().'"}); admin.orders.init();});</script>');
		$this->template->append_metadata('<script type="text/javascript">common.regions='.$regions.';common.metros='.$metros.'</script>');
	}


	/*
	*
	*	Маленький маршрутизатор
	*/
	public function _remap(){

		$action  = $this->input->get('act')?$this->input->get('act'):'view';
		/*
		* обрабатываем действие
		*/
		switch ($action) {
			default:
			case 'view':
				/*
				* обработка действия view
				*/
				$section = $this->input->get('s')?$this->input->get('s'):'all';
				switch ($section) {
					case 'free':
						$this->_view_free_orders();
						break;
					case 'delegate':
						$this->_view_delegate_orders();
						break;
					case 'all':
					default:
						$this->_view_all();
				}
			break;
			case 'add':
				/*
				* 
				* Обработка добавления объявления
				*/
				$this->_add_order();
			break;
			case 'edit':
				/*
				* обработка редактирования объявления
				*
				*/
				$this->_edit_order();
			break;
			case 'del':
				/*
				* Обработка удаления объявления
				*
				*/
				$this->_del_orders();
			break;
			case 'delegate':
				/*
				* Назначить члену персонала ( Агенту или Менеджеру )
				*/
				$this->_delegate_order();
			break;
		}
	}

	/**
	*
	*	Действие, отвечающее за отображение всех заявок
	*	
	*	@author Alex.strigin
	*	@company Flyweb	
	*
	*/
	private function _view_all(){
		if($this->ajax->is_ajax_request()){
			$response = array();
			try{
				$orders = $this->admin_orders->get_all_orders_org();
				$response['code'] = 'success_view_orders';
				$response['data'] = $orders;
			}catch(AnbaseRuntimeException $re){
				$response['code'] = 'error_view_orders';
				$response['data']['errors']= array($re->get_error_message());
			}
			$this->ajax->build_json($response);
		}else{	
			/*	
			*	Базовые установки шаблона
			*/
			$this->template->set_partial('dashboard_tabs','dashboard/dashboard_tabs');
			$this->template->set_partial('orders_toolbar','orders/partials/toolbar');
			/*
			* Вывод
			*/
			$this->template->build('orders/view',array('section'=>'all'));

		}
	}


	/**
	*
	*	"Закрытый" метод, выводящий список свободных заявок
	*
	*	@author Alex.strigin
	*	@company Flyweb
	*/
	private function _view_free_orders(){
		if($this->ajax->is_ajax_request()){
			$response = array();

			try{
				$orders = $this->admin_orders->get_all_free_orders();
				$response['code'] = 'success_view_orders';
				$response['data'] = $orders;
			}catch(AnbaseRuntimeException $re){
				$response['code'] = 'error_view_orders';
				$response['data'] = array($re->get_error_message());
			}
			$this->ajax->build_json($response);
		}else{		
			/*
			*	Базовые установки шаблона
			*/
			$this->template->set_partial('dashboard_tabs','dashboard_tabs');

			/*
			*	Вывод данных
			*/
			$this->template->build('orders/view',array('section'=>'free'));
		}
	}

	/**
	*
	*	"Закрытый" метод, выводящий делегированные заявки
	*	@author Alex.strigin
	*	@company Flyweb
	*/
	public function _view_delegate_orders(){
		if($this->ajax->is_ajax_request()){
			try{
				$orders = $this->admin_orders->get_all_delegate_orders();
				$response['code'] = 'success_view_orders';
				$response['data'] = $orders;
			}catch(AnbaseRuntimeException $re){
				$response['code'] = 'error_view_orders';
				$response['data']['errors'] = array($re->get_error_message()); 
			}
			$this->ajax->build_json($response);
		}else{
			/*
			*	Базовые установки шаблона
			*/
			$this->template->set_partial('dashboard_tabs','dashboard/dashboard_tabs');
			/*
			*	Вывод данных
			*/
			$this->template->build('orders/view',array('section'=>'delegate'));
		}
	}

	/**
	 * Добавление заявки.
	 * Добавление доступно только для ajax requests. Для всех остальных будем давать redirect.
	 *
	 * @return void
	 * @author alex.strigin
	 **/
	private function _add_order()
	{
		/*
		* Если не Ajax, то redirect
		*/
		if($this->ajax->is_ajax_request()){
			/*
			* Пытаемся добавить запись
			*/
			try{
				if($order_id = $this->admin_users->add_order()){
					$response['code'] = 'success_add_order';
					$response['data']['id'] = $order_id;
					$response['data']['msg'] = lang('success_add_order');	
				}else{
					$response['code'] = 'error_add_order';
					$response['data']['errors'] = array(lang('common.insert_error')); 
				}
			}catch(AnbaseRuntimeException $re){
				$response['code'] = 'error_add_order';
				$response['data']['errors'] = array($re->get_error_message());
			}catch(ValidationException $ve){
				$response['code'] = 'error_add_order';
				$response['data']['errors'] = $ve->get_error_messages();
			}

			$this->ajax->build_json($response);
		}else{
			redirect($this->admin_users->get_home_page());
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
		* Если не Ajax, то redirect
		*/
		if($this->ajax->is_ajax_request()){
			/*
			*	Пытаемся изменить запись
			*/
			try{
				$this->admin_users->edit_order();
				$response['code'] = 'success_edit_order';
				$response['data'] =  lang('success_edit_order');
			}catch(ValidationException $ve){
				$response['code'] = 'error_edit_order';
				$response['data']['errors'] = $ve->get_error_messages();
			}catch(AnbaseRuntimeException $re){
				$response['code'] = 'error_edit_order';
				$response['data']['errors'] = array($re->get_error_message());
			}
			$this->ajax->build_json($response);
		}else{
			redirect($this->admin_users->get_home_page());
		}
	}


	/**
	 * Удаление заявки(заявок)
	 *
	 * @return void
	 * @author 
	 **/
	private function _del_orders()
	{
		/*
		* Если не ajax, то redirect
		*/
		if($this->ajax->is_ajax_request()){
			/*
			* Пытаемся изменить
			*/
			try{
				$this->admin_users->del_orders();
				$response['code'] = 'success_del_order';
				$response['data'] = lang('success_del_order');
			}catch(AnbaseRuntimeException $re){
				$response['code'] = 'error_edit_order';
				$response['data']['errors'] = array($re->get_error_message());
			}
			$this->ajax->build_json($response);
		}else{
			redirect($this->admin_users->get_home_page());
		}
	}

	/**
	 * Назначить заявку члену персонала
	 *
	 * @return void
	 * @author alex.strigin
	 **/
	private function _delegate_order()
	{
		/*
		* Если не ajax, то redirect
		*/
		if($this->ajax->is_ajax_request()){
			/*
			* Пытаемся назначить
			*/
			try{
				$this->admin_users->delegate_order();
				$response['code'] = 'success_delegate_order';
				$response['data'] = lang('success_delegate_order');
			}catch(AnbaseRuntimeException $re){
				$response['code'] = 'error_delegate_order';
				$response['data']['errors'] = array($re->get_error_message());
			}catch(ValidationException $ve){
				$response['code'] = 'error_delegate_order';
				$response['data']['errors'] = array($ve->get_error_messages());
			}
			$this->ajax->build_json($response);
		}else{
			redirect($this->admin_users->get_home_page());
		}
	}

} // END class Orders extends MX_Controller