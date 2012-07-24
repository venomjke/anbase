<?php defined("BASEPATH") or die("No direct access to script");

/**
 * Модель отвечает за привязывание заявок менеджерам или агентам
 *
 * @package default
 * @author alex.strigin
 **/
class M_Order_user extends MY_Model
{

	/*
	* Правила валидации при назначении заявки агенту
	*
	*/
	public $delegate_validation_rules = array(
		array('field'=>'order_id', 'label' => 'ORDER_ID',  'rules'=>'required|valid_order_id'),
		array('field'=>'user_id',  'label' => 'USER_ID',   'rules'=>'required|callback_valid_user_id')
	);

	public function __construct(){
		parent::__construct();

		/*
		*
		* Задаем структуру модели
		*/
		$this->table       = 'orders_users';
		$this->primary_key = 'order_id';
		$this->fields      = array('order_id','user_id');

		$this->result_mode = 'object';

		/*
		* А дальше ничего
		*/
	}

	public function does_order_belong_user($order_id,$user_id)
	{
		return $this->count_all_results(array('order_id'=>$order_id,'user_id'=>$user_id)) == 0?false:true;
	}
	/**
	 * Проверка user_id. Т.к с user_id передается еще -1, то просто is_valid_user_id использовать нельзя
	 *
	 * @return boolean
	 * @author alex.strigin
	 **/
	public function valid_user_id($user_id='')
	{
		/*
		*
		* Загружаем отдельно библиотеку для работы с пользователями
		*/
		$this->load->library('users/users');
		if(!empty($user_id)){
			/*
			* Во время выполнения delegate операции -1 служит указателем на то, что заявку нужно перестать делегировать.
			*/
			if($user_id == -1){
				return true;
			}
			if($this->m_user->is_exists($user_id,$this->users->get_org_id())){
				return true;
			}
		}
		$this->form_validation->set_message('valid_user_id',lang('user.validation.valid_user_id'));
		return false;
	}

	/**
	 * Делегирование заявки агенту
	 *
	 * @return int
	 * @author alex.strigin
	 **/
	public function delegate_order($order_id,$user_id)
	{
		/*
		* Если user_id = -1, то удалить запись о делегировании заявки, и ничего не назначать.
		* Прежде чем делегировать заявку агенту, удаляем возможную запись о делегировании заявки другому агенту
		* Делегировать другому агенту
		* Поменять дату делегирования в таблице orders
		*/
		if($user_id == -1){
			$this->delete(array('order_id'=>$order_id));
			return true;
		}else{
			$this->delete(array('order_id'=>$order_id));
			$this->insert(array('order_id'=>$order_id,'user_id'=>$user_id));
			$this->db->where('orders.id',$order_id);
			$this->db->update('orders',array('delegate_date'=>date('Y-m-d'),'delegated'=>date('Y-m-d H:i:s')));
		}
		return false;
	}
} // END class M_Order_user extends MY_Model