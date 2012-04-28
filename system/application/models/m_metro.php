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

	public function check_metros($metros)
	{
		if(!empty($metros)){
			if(is_array($metros)){
				foreach($metros as $metro_line){
					if(!empty($metro_line)&&is_array($metro_line)){
						foreach($metro_line as $metro_id){
							if(!is_numeric($metro_id) || $metro_id <= 0 || !$this->is_exists($metro_id)){
								return false;
							}
						}
					}else{
						return false;
					}
				}
				return true;
			}else{
				return false;
			}
		}
		return true;
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
	 * Получить список метро. Получить список метро можно в двух форматах.
	 * 1. Нормальный - возвращаем результат запроса
	 * 2. Json - парсим массив, выдергиваем записи и вставляем их в другой массив.
	 *
	 * @return array
	 * @author alex.strigin
	 **/
	public function get_metro_list($format = "normal")
	{

		$metros = $this->get_all();
		switch ($format) {
			case 'normal':
			default:
				return $metros;
				break;
			case 'json':
				$metros_json = array();
				foreach($metros as $metro){
					$metros_json[$metro->line][$metro->id] = $metro->name;
				}
				return json_encode($metros_json);
			break;
		}
	}
}// END M_Region class