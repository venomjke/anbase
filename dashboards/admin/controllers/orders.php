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

		/*
		* Загрузка драйверов
		*/
		$this->load->driver('Minify');

		/*
		* Загрузка либ
		*/
		$this->load->library("admin/Admin_Users");
		$this->load->library("admin/Admin_Orders");
		$this->load->library("Ajax");

		if( !$this->admin_users->is_logged_in_as_admin() ){
			redirect("");
		}

		/*
		*
		* Загрузка языковых сообщений
		*/
		$this->load->language('admin/messages');

		/*
		*	загрузка моделей
		*/
		$this->load->model('m_order');
		$this->load->model('m_region');
		$this->load->model('m_metro');
		$this->load->model('m_metro_image');
		$this->load->model('m_region_image');

		/*
		* Настройка шаблона
		*/
		$this->template->set_theme("dashboard");
		$this->template->set_partial("dashboard_head","dashboard/dashboard_head");
		$this->template->set_partial('dashboard_user','dashboard/dashboard_user');
		$this->template->set_partial('dashboard_menu','dashboard/dashboard_menu');
		

		/*
		* Загружаем другую метаинфу
		* [my_notice] Это все ресурсы, используемые во время работы. У меня есть такое мнение, что их стоит выделить 
		* в какой-то класс AppAssets
		*/
		$this->_load_app_assets();


		$this->template->append_metadata('<script type="text/javascript" src="'.site_url('dashboards/admin/js/admin.js').'"></script>');
		$this->template->append_metadata('<script type="text/javascript">$(function(){admin.init({baseUrl:"'.site_url("admin/orders").'"}); admin.orders.init();}); </script>');
	}

	private function _load_app_assets($value='')
	{
		
		$regions = $this->m_region->get_region_list("json");
		$metros  = $this->m_metro->get_metro_list("json");
		$metros_images = $this->m_metro_image->get_images();
		$regions_images = $this->m_region_image->get_images();
		$staff_list  = json_encode($this->admin_users->get_list_staff());
		$category_list = json_encode($this->m_order->get_category_list());
		$dealtype_list = json_encode($this->m_order->get_dealtype_list());

		$assets = array(
			"common.regions=$regions",
			"common.metros=$metros",
			"common.metros_images=$metros_images",
			"common.regions_images=$regions_images",
			"common.staff_list=$staff_list",
			"common.category_list=$category_list",
			"common.dealtype_list=$dealtype_list",
			""
		);
		/*
		* Подключение скриптов
		*/
		$this->template->append_metadata('<script type="text/javascript">'.implode(';',$assets).'</script>');
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
				$this->template->set('current',$section);
				$this->template->set_partial('dashboard_tabs','dashboard/dashboard_tabs');
				$this->template->set_partial('dashboard_filter','dashboard/dashboard_filter');

				switch ($section) {
					case 'off':
						$this->_off_orders();
						break;
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
				$response['code'] = 'success_load_data';
				$response['data'] = $orders;
			}catch(AnbaseRuntimeException $re){
				$response['code'] = 'error_load_data';
				$response['data']['errorType'] = 'runtime';
				$response['data']['errors']= array($re->get_error_message());
			}catch(ValidationException $ve){
				$response['code'] = 'error_load_data';
				$response['data']['errorType'] = 'validation';
				$response['data']['errors'] = $ve->get_error_messages();
			}

			$this->ajax->build_json($response);
		}else{	
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
			$this->template->build('orders/view',array('section'=>'free'));
		}
	}

	/**
	*
	*	"Закрытый" метод, выводящий делегированные заявки
	*	@author Alex.strigin
	*	@company Flyweb
	*/
	private function _view_delegate_orders(){
		if($this->ajax->is_ajax_request()){
			try{
				$orders = $this->admin_orders->get_all_delegate_orders();
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
			$this->template->build('orders/view',array('section'=>'delegate'));
		}
	}


	private function _off_orders()
	{
		if($this->ajax->is_ajax_request()){
			try{
				$orders = $this->admin_orders->get_all_off_orders();
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
			$this->template->build('orders/view',array('section'=>'off'));
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
				$this->admin_orders->add_order();
				$response['code'] = 'success_add_data';
				$response['data'] = lang('success_add_order');	
			}catch(AnbaseRuntimeException $re){
				$response['code'] = 'error_add_data';
				$response['data']['errorType'] = 'runtime';
				$response['data']['errors'] = array($re->get_error_message());
			}catch(ValidationException $ve){
				$response['code'] = 'error_add_data';
				$response['data']['errorType'] = 'validation';
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
				$this->admin_orders->edit_order();
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
				$this->admin_orders->del_orders();
				$response['code'] = 'success_del_data';
				$response['data'] = lang('success_del_order');
			}catch(AnbaseRuntimeException $re){
				$response['code'] = 'error_del_data';
				$response['data']['errorType'] = 'runtime';
				$response['data']['errors'] = array($re->get_error_message());
			}catch(ValidationException $ve){
				$response['code'] = 'error_del_data';
				$response['data']['errorType'] = 'validation';
				$response['data']['errors'] = $ve->get_error_messages();
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
				$this->admin_orders->delegate_order();
				$response['code'] = 'success_delegate_order';
				$response['data'] = lang('success_delegate_order');
			}catch(AnbaseRuntimeException $re){
				$response['code'] = 'error_delegate_order';
				$response['data']['errorType'] = 'runtime';
				$response['data']['errors'] = array($re->get_error_message());
			}catch(ValidationException $ve){
				$response['code'] = 'error_delegate_order';
				$response['data']['errorType'] = 'validation';
				$response['data']['errors'] = $ve->get_error_messages();
			}
			$this->ajax->build_json($response);
		}else{
			redirect($this->admin_users->get_home_page());
		}
	}

} // END class Orders extends MX_Controller