<?php defined("BASEPATH") or die("No direct access to script");



if(!class_exists("M_Order")){
	require APPPATH."models/m_order.php";
}


/**
 * Модель заявок менеджера, и управляемая из под менеджера
 *
 * @package anbase
 * @author 
 **/
class M_Manager_order extends M_Order
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
	 * Выбор всех заявок агентов, которых курирует данный менеджер
	 *
	 * @param int manager_id
	 * @param int org_id
	 * @param array filter
	 * @param int limit
	 * @param int offset
	 * @return array
	 * @author Alex.strigin
	 **/
	public function get_all_delegate_orders($user_id,$filter=array(),$limit=false,$offset=false,$fields=array())
	{
		/*
		*
		* метод build_select для  формирования селекта на выбор всех заявок
		* + join на выбор заявок из managers_users
		*/
		$this->build_select($fields);
		$this->join("managers_users","managers_users.user_id = orders_users.user_id");
		/*
		*
		* Установка фильтров 
		*
		*/
		$this->set_filter($filter);

		/*
		*
		*	Установка ограничений
		*
		*/
		$this->limit($limit,$offset);

		/*
		*	Выборка.
		*	Указываем только manager_id, т.к org_id подразумевается верным потому, что manager_id не может быть связан с теми user_id, которые не принадлежат к текущей организации.
		*/
		return $this->get_all(array("managers_users.manager_id" => $user_id));
	}

} // END class M_Manager_order extends M_Order