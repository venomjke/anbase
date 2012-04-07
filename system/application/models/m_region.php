<?php defined("BASEPATH") or die("No direct access to script");

/**
* Модель района
*
* @package default
* @author alex.strigin
*/
class M_Region extends MY_Model
{
	
	function __construct()
	{
		/*
		*	Структура таблицы
		*/
		$this->table = 'regions';
		$this->primary_key = 'id';
		$this->fields = array('id','name');
		$this->result_mode = 'object';
		/*
		* Правила валидации
		*/
		$this->validate = array();
	}


	/**
	 * Проверка, существует ли такой region_id
	 *
	 * @return boolean
	 * @author alex.strigin
	 **/
	public function is_exists($region_id)
	{
		return $this->count_all_results($region_id) == 0?false:true;
	}

	/**
	 * Получить список районов
	 *
	 * @return array
	 * @author alex.strigin
	 **/
	public function get_region_list()
	{
		return $this->get_all();
	}
}// END M_Region class