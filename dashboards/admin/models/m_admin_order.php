<?php defined("BASEPATH") or die("No direct access to script");


/*
*	
*	Загрузка на всякий случай M_Order в тупую
*/
if(!class_exists("M_Order")){
	require APPPATH."models/m_order.php";
}

/**
 * Расширение модели M_Order специально для панели администратоор
 *
 * @package anbase
 * @author 
 **/
class M_Admin_order extends M_Order
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
	}


	/**
	 * Метод обращается к get_all_orders_org, предварительно установив фильтр NOT NULL
	 * на поле user_id
	 *
	 * @param int
	 * @param array
	 * @param int
	 * @param int
	 * @return void
	 * @author Alex.strigin
	 **/
	function get_all_delegate_orders($org_id,$filter=array(),$limit=FALSE,$offset=FALSE)
	{
		$this->where('orders_users.user_id IS NOT NULL');
		return $this->get_all_orders_org($org_id,$filter,$limit,$offset);
	}
} // END class M_Admin_order extends M_Order