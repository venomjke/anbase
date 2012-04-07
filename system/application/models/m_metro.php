<?php defined("BASEPATH") or die("No direct access to script");

/**
* Модель метро
*
* @package default
* @author alex.strigin
*/
class M_Metro extends MY_Model
{
	
	function __construct()
	{
		/*
		*	Структура таблицы
		*/
		$this->table = 'metros';
		$this->primary_key = 'id';
		$this->fields = array('id','name');
		$this->result_mode = 'object';
		/*
		* Правила валидации
		*/
		$this->validate = array();
	}


	/**
	 * Проверка, существует ли такой metro_id
	 *
	 * @return boolean
	 * @author alex.strigin
	 **/
	public function is_exists($metro_id)
	{
		return $this->count_all_results($metro_id) == 0?false:true;
	}

	/**
	 * Получить список метро
	 *
	 * @return array
	 * @author alex.strigin
	 **/
	public function get_metro_list()
	{
		return $this->get_all();
	}
}// END M_Region class