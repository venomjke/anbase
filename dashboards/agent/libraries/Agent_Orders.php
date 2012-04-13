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
		$this->ci->load->model('m_agent_order');
	}
} // END class Agent_Orders