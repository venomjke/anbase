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
 * Класс для управления заявками агента.
 *
 * @package default
 * @author alex.strigin 
 **/
class Agent_Orders
{
	/*
	* Объект приложения
	*/
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

		$this->ci->load->library('agent/agent_users');
		$this->ci->load->library('orders_organization');

		$this->ci->load->model('m_agent_order');
	}


	/**
	 * Выбор всех заявок агента
	 *
	 * @return array
	 * @author alex.strigin
	 **/
	public function get_all_agent_orders()
	{
		/*
		* Поля, которые нужно выбрать
		*/
		$order_fields = array('number','create_date','category','deal_type','description','price','phone');
		return $this->ci->orders_organization->get_all_user_orders($this->ci->agent_users->get_user_id(),$order_fields);
	}

	/**
	 * Выбор всех свободных заявок
	 *
	 * @return array
	 * @author alex.strigin
	 **/
	public function get_all_free_orders()
	{
		$order_fields = array('number','create_date','category','deal_type','description','price');
		return $this->ci->orders_organization->get_all_free_orders($this->ci->agent_users->get_org_id(),$order_fields);
	}

} // END class Agent_Orders