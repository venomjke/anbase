<?php defined("BASEPATH") or die("No direct access to script");

/**
 * Статистика организации
 *
 * @package default
 * @author alex.strigin 
 **/
class Org_Statistic
{
	private $ci;

	public function __construct()
	{
		$this->ci = get_instance();

		$this->ci->load->library('users/users');
		/*
		* Подключаем модели, через которые будем отдавать статистику
		*/
		$this->ci->load->model('m_order');
	}

	public function count_all_free_orders()
	{
		return $this->ci->m_order->count_all_free_orders($this->ci->users->get_org_id());
	}

	public function count_all_delegate_orders()
	{
		return $this->ci->m_order->count_all_delegate_orders($this->ci->users->get_org_id());
	}

	public function count_all_finish_orders()
	{
		return $this->ci->m_order->count_all_off_orders_org($this->ci->users->get_org_id());
	}

	public function count_orders_org_group_by_date()
	{
		$cnt_orders = $this->ci->m_order->count_orders_org_group_by_date($this->ci->users->get_org_id());
		$data = array();
		foreach($cnt_orders as $cnt_order){
			$data[] = array($cnt_order->create_date,$cnt_order->cnt_date);
		}
		return $data;
	}
} // END class Org_Statistic