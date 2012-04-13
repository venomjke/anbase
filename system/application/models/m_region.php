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
	 * Получить список районов. Получить список районов можно в двух различных видах
	 * 1. Нормальный, будет возвращен список строк в виде объектов
	 * 2. Один большой json объект, который удобно передавать исп. ajax, или просто сувать в js код.
	 *
	 * @param string
	 * @return array
	 * @author alex.strigin
	 **/
	public function get_region_list($format = "normal")
	{
		$regions = $this->get_all();
		switch ($format) {
			case 'normal':
			default:
				/*
				* Просто возвращаем список районов
				*/
				return $regions;
				break;
			case 'json':
				/*
				* Пересобираем массив, и возвращаем json_encode
				*/
				$json_regions = array();
				foreach($regions as $region){
					$json_regions[$region->id] = $region->name;
				}
				return json_encode($json_regions);
				break;
		}
	}

}// END M_Region class