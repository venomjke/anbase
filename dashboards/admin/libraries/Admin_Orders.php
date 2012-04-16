<?php defined("BASEPATH") or die("No direct access to script");


/*
* Подключение исключений
*/
if(!class_exists("ValidationException")){
	require_once APPPATH."exceptions/ValidationException.php";
}

if(!class_exists("AnbaseRuntimeException")){
	require_once APPPATH."exceptions/AnbaseRuntimeException.php";
}


/**
 * Класс, управляющий заявками админа
 *
 * @package default
 * @author alex.strigin
 **/
class Admin_Orders
{
	/*
	* Объект приложения
	*/
	private $ci;

	public function __construct()
	{
		$this->ci = get_instance();

		$this->ci->load->library("admin/Admin_Users");
		$this->ci->load->library("Orders_Organization");
		$this->ci->load->model("m_admin_order");
	}

	/**
	 * Выбор всех заявок агентства
	 *
	 * @return array
	 * @author alex.strigin
	 **/
	public function get_all_orders_org()
	{
		return $this->ci->orders_organization->get_all_orders_org($this->ci->admin_users->get_org_id());
	}
	/**
	 * Выбор всех свободных заявок
	 *
	 * @return array
	 * @author alex.strigin
	 **/
	public function get_all_free_orders()
	{
		return $this->ci->orders_organization->get_all_free_orders($this->ci->admin_users->get_org_id());
	}

	/**
	 * Выбор всех делегированных заявок
	 *
	 * @return array
	 * @author alex.strigin
	 **/
	public function get_all_delegate_orders()
	{
		return $this->ci->orders_organization->get_all_delegate_orders($this->ci->admin_users->get_org_id());
	}
} // END class Admin_Orders