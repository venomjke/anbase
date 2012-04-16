<?php defined("BASEPATH") or die("No direct access to script");

/*
*
* Подключение исключений
*
*/
if(!class_exists("ValidationException")){
	require_once APPPATH."exceptions/ValidationException.php";
}

if(!class_exists("AnbaseRuntimeException")){
	require_once APPPATH."exceptions/AnbaseRuntimeException.php";
}


/**
 * Класс, отвечающий за реализацию операций над заявками в панели manager
 *
 * @package default
 * @author alex.strigin 
 **/
class Manager_Orders
{
	/*
	* Объект приложения
	*/
	private $ci;
	
	public function __construct()
	{
		$this->ci = get_instance();

		$this->ci->load->library('manager/Manager_Users');
		$this->ci->load->library('Orders_Organization');

		$this->ci->load->model('m_manager_order');	
	}

	/**
	 * Выбор всех заявок менеджера
	 *
	 * @return array
	 * @author alex.strigin
	 **/
	public function get_all_orders_manager()
	{
		/*
		* Необходимые поля
		*/
		$orders_fields = array('number','create_date','category','deal_type','description','price','phone');
		return $this->ci->orders_organization->get_all_user_orders($this->ci->manager_users->get_user_id(),$orders_fields);
	}

	/**
	 * Выбор всех свободных заявок
	 *
	 * @return array
	 * @author alex.strigin
	 **/
	public function get_all_free_orders()
	{
		$orders_fields = array('number','create_date','category','deal_type','description','price');
		return $this->ci->orders_organization->get_all_free_orders($this->ci->manager_users->get_org_id(),$orders_fields);
	}

	/**
	 * Выбор всех заявок, делегированных агентам, которых курирует админ
	 *
	 * @return array
	 * @author alex.strigin
	 **/
	public function get_all_delegate_orders()
	{
		$orders_fields = array('number','create_date','category','deal_type','description','price','phone');

		/*
		* Определяем фильтры, limit, offset
		*/
		$filter = array();
		$limit  = false;
		$offset = false;
			
		$orders = $this->ci->m_manager_order->get_all_delegate_orders($this->ci->manager_users->get_user_id(),$filter,$limit,$offset,$orders_fields);
		$this->ci->orders_organization->bind_metros($orders);
		$this->ci->orders_organization->bind_regions($orders);
		return $orders;
	}

} // END class Manager_Orders