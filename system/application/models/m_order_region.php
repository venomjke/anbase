<?php defined("BASEPATH") or die("No direct access to script");


/**
 * Модель order_region отвечает за выбор регионов принадлежащих той или иной заявке.
 *
 * @package default
 * @author alex.strigin
 **/
class M_Order_region extends MY_Model
{
	/**
	 * Конструктор
	 *
	 * @return void
	 * @author alex.strigin
	 **/
	public function __construct()
	{
		parent::__construct();

		/*
		* Определение структуры заявки
		*/
		$this->table       = 'orders_regions';
		/*
		* [my_notice: У этой табоицы orders_regions первичный ключ составной, поэтому пока оставляем пустым это поле, для составных полей MY_Model не заточена]
		*/
		$this->primary_key = '';
		$this->fields      = array('order_id','region_id');
		$this->result_mode = 'object';

		/*
		 * Правила валидации
		 */ 
		$this->validate = array();
	}

	/**
	 * Выбрать все районы заданной order_id
	 *
	 * @param int 
	 * @param bool - [my_notice: не знаю, как лучше эту шнягу назвать, но должно быть ясно, чтобы выбираем ids regions в чистом виде]
	 * @return array
	 * @author alex.strigin
	 **/
	public function get_order_regions($order_id,$in_array=true)
	{
		$regions = $this->get_all(array('order_id'=>$order_id));

		if($in_array){
			$regions_ids = array();
			foreach($regions as $region){
				$regions_ids[] = $region->id;
			}
			return $regions_ids;
		}
		return $regions;
	}
} // END class M_Order_region extends MY_Model