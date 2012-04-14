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

} // END class M_Agent_order extends M_Order