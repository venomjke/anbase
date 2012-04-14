<?php defined("BASEPATH") or die("No direct access to script");



/**
 * Класс, реализующий логику обработки заявок организации
 *
 * @package default
 * @author alex.strigin
 **/
class Orders_Organization
{

	private $ci;
	/**
	 * Конструктор
	 *
	 * @return void
	 * @author alex.strigin
	 **/
	public function __construct()
	{
		$this->ci = get_instance();
		/*
		* Загрузка модели m_order
		*/
		$this->ci->load->model('m_order');

		$this->ci->load->model('m_order_region');
		$this->ci->load->model('m_order_metro');

	}

	/**
	 * Привязать к заявкам регионы
	 *
	 * @return void
	 * @author alex.strigin
	 **/
	public function bind_regions(&$orders)
	{
		foreach($orders as $order){
			$order->regions = $this->ci->m_order_region->get_order_regions($order->id);
		}
	}

	/**
	 * Привязать к заявкам метро
	 *
	 * @return void
	 * @author alex.strigin
	 **/
	public function bind_metros(&$orders)
	{
		foreach($orders as $order){
			$order->metros = $this->ci->m_order_metro->get_order_metros($order->id);
		}
	}

	/**
	 * Выбор всех свободных заявок
	 *
	 * @return void
	 * @author alex.strigin
	 **/
	public function get_all_free_orders($org_id)
	{
		/*
		* 1. Выбор свободных заявок
		* 2. Привязывание regions
		* 3. Привязывание metros
		*/

		$filter = array();
		$limit  = false;
		$offset = false;

		$orders = $this->ci->m_order->get_all_free_orders($org_id,$filter,$limit,$offset);

		$this->bind_regions($orders);

		$this->bind_metros($orders);
		return $orders;
	}

	/**
	 * Выбор всех делегированных заявок
	 *
	 * @return void
	 * @author alex.strigin
	 **/
	public function get_all_delegate_orders($org_id)
	{
		/*
		* 1. Выбор всех свободных заявок
		* 2. Привязывание regions
		* 3. Привязывание metros
		*/
		$filter = array();
		$limit  = false;
		$offset = false;

		$orders = $this->ci->m_order->get_all_delegate_orders($org_id,$filter,$limit,$offset);

		$this->bind_regions($orders);
		$this->bind_metros($orders);
		
		return $orders;
	}

	/**
	 * Выбор всех заявок определенного пользователя
	 *
	 * @return void
	 * @author alex.strigin
	 **/
	public function get_all_user_orders($user_id)
	{
		/*
		* 0. Установка фильтров
		* 1. Выбор всех свободных заявок
		* 2. Привязывание regions
		* 3. Привязывание metros
		*/
		$filters = array();
		$limit   = false;
		$offset  = false;

		$orders = $this->ci->m_order->get_all_orders_user($user_id,$filters,$limit,$offset);

		$this->bind_regions($orders);
		$this->bind_metros($orders);
		return $orders;
	}
	
} // END class OrdersOrganization