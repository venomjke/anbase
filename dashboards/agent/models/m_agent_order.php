<?php defined("BASEPATH") or die("No direct access to script");


if(!class_exists("M_Order")){
	require APPPATH."models/m_order.php";
}

/**
 * Класс модели заявок агента
 *
 * @package anbase
 * @author Alex.strigin
 **/
class M_Agent_order extends M_Order
{
	/**
	 * Конструктор
	 *
	 * @return void
	 * @author Alex.strigin
	 **/
	public function __construct()
	{
		parent::__construct();
	}


	/**
	 * Выбор всех заявок агента
	 *
	 * @param int
	 * @param array
	 * @param int
	 * @param int
	 * @return void
	 * @author Alex.strigin
	 **/
	public function get_all_orders_agent($user_id,$filter=array(),$limit=false,$offset=false)
	{
		return $this->get_all_orders_users($user_id,$filter,$limit,$offset);
	}


	/**
	 * Выбор всех свободных полей
	 *
	 * @param int
	 * @param array
	 * @param int
	 * @param int
	 * @return void
	 * @author Alex.strigin
	 **/
	public function get_all_free_orders($org_id,$filter=array(),$limit=false,$offset=false)
	{
		$this->db->where('orders_users.user_id IS NULL');
		return $this->get_all_orders_org($org_id,$filter,$limit,$offset);
	}
} // END class M_Agent_order extends M_Order