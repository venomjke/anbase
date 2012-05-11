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
	 * Поверка того, что region_id является настоящим
	 *
	 * @return boolean
	 * @author alex.strigin
	 **/
	public function exists($region_id)
	{
		$this->db->where('id',$region_id);
		return $this->db->count_all_results('regions') == 0 ? false:true;
	}
	/**
	 * Выбрать все районы заданной order_id
	 *
	 * @param int 
	 * @param bool - [my_notice: не знаю, как лучше эту шнягу назвать, но должно быть ясно, чтобы выбираем ids regions в чистом виде]
	 * @return array
	 * @author alex.strigin
	 **/
	public function get_order_regions($order_id,$in_array=true,$with_names=false)
	{
		$regions;

		if(!$with_names){
			$regions = $this->get_all(array('order_id'=>$order_id));

			if($in_array){
				$regions_ids = array();
				foreach($regions as $region){
					$regions_ids[] = $region->region_id;
				}
				return $regions_ids;
			}	
		}else{
			$this->select('regions.name');
			$this->join("regions","regions.id = orders_regions.region_id");
			$regions = $this->get_all(array('order_id'=>$order_id));
		}
		return $regions;
	}

	/**
	 * Привязываем к заявке районы
	 *
	 * @return void
	 * @author alex.strigin
	 **/
	public function bind_order_regions($order_id,$regions)
	{
		/*
		* Перед тем, как привязать новые районы к заявке, нужно удалить старые связи.
		*/
		$this->delete(array('order_id'=>$order_id));


		if(is_array($regions)){

		 	foreach($regions as $region){
				if(is_numeric($region) && ($region > 0) && $this->exists($region))
					$this->insert(array('order_id'=>$order_id,'region_id'=>$region),true);
			}	
		}
	}
} // END class M_Order_region extends MY_Model