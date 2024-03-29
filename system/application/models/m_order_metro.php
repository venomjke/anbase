<?php defined("BASEPATH") or die("No direct access to script");


/**
 * Модель, отвечающая за выбор метро принадлежащих заявкам
 *
 * @package default
 * @author alex.strigin
 **/
class M_Order_metro extends MY_Model 
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
		* Определение структуры модели
		*/
		$this->table = 'orders_metros';
		/*
		* [my_notice: У этой таблицы orders_metros первичный ключ составной, поэтому пока оставляем пустым это поле, для составных полей MY_Model не заточена]
		*/
		$this->primary_key = '';
		$this->fields      = array('order_id','metro_id');
		$this->result_mode = 'object';
		/*
		* Правила валидации
		*/
		$this->validate = array();
	}


	/**
	 * Проверка того, что metro_id является реальным
	 *
	 * @return boolean
	 * @author alex.strigin
	 **/
	public function exists($metro_id)
	{
		$this->db->where('id',$metro_id);
		return $this->db->count_all_results('metros') == 0?false:true;
	}

	/**
	 * Выбор все метро, относящихся к заявке
	 *
	 * @param int
	 * @param bool
	 * @return array
	 * @author alex.strigin
	 **/
	public function get_order_metros($order_id,$in_array=true,$with_names=false)
	{
		$metros;
		if(!$with_names){
			$this->select("orders_metros.order_id");
			$this->select("orders_metros.metro_id");
			$this->select("metros.line");

			$this->join("metros","orders_metros.metro_id = metros.id");

			$metros = $this->get_all(array("order_id"=>$order_id));

			if($in_array){
				$metros_ids = array();
				foreach($metros as $metro){
					$metros_ids[$metro->line][] = $metro->metro_id;
				}
				return $metros_ids;
			}	
		}else{
			$this->select("metros.line");
			$this->select("metros.name");

			$this->join("metros","orders_metros.metro_id = metros.id");

			$metros = $this->get_all(array("order_id"=>$order_id));

			if($in_array){
				$metros_names = array();
				foreach($metros as $metro){
					$metros_names[$metro->line][] = $metro->name;
				}
				return $metros_names;
			}
		}
		
		return $metros;
	}

	/**
	 * Связать заяку с новыми метро
	 *
	 * @return void
	 * @author alex.strigin
	 **/
	public function bind_order_metros($order_id,$metros)
	{
		/*
		* Удаляем старую связь
		*/
		$this->delete(array('order_id'=>$order_id));

		/*
		* [my_notice] в случае, если передан не массив, то ошибок не выдаем, типо все ок.
		* Это связано с тем, что если пользователь решил удалить все выбранные метро, то полюбому массив будет пустым
		* перебирать его не надо, но вот старые связи удалить нужно.
		*/
		if(is_array($metros)){
			foreach($metros as $k=>$metro_line){
				/*
				* В целях безопасности проверяем все metro_line  т.к они могут быть пустыми
				* если пустой, то ничего не делаем
				*/
				if(is_array($metro_line)){
					foreach($metro_line as $metro){
						if(is_numeric($metro) && $this->exists($metro))
							$this->insert(array('order_id'=>$order_id,'metro_id'=>$metro));
					}
				}
			}
		}
	}
} // END class M_Order_metro 