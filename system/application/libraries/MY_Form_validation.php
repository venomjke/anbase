<?php if (!defined('BASEPATH')) exit('No direct script access allowed');

class MY_Form_validation extends CI_Form_validation 
{


	/**
	 * Валидный номер телефона.
	 * Мнимальная длина номера - 7 цифр.
	 * Максимальная длица 13 цифр.
	 * 
	 * Допускаяются только цифры
	 * @return boolean
	 * @author alex.strigin
	 **/
	public function valid_phone($phone)
	{
		if(is_numeric($phone) && ($phone >= 0 ) && ((strlen($phone)>= 7 and strlen($phone)<=13) or $phone == 0)) {
			return true;
		}
		$this->set_message('valid_phone',lang('common.validation.valid_phone'));
		return false;
	}
	/**
	 * Конвертирование валидной даты для mysql
	 *
	 * @return string
	 * @author alex.strigin
	 **/
	public function convert_valid_date($valid_date,$format = 'dd/mm/yyyy')
	{
		$delimiter = "@[/\.-]@";
		switch($format){
			default:
				$date_set = preg_split($delimiter,$valid_date);
				if(count($date_set) == 3){
					$mysql_date = $date_set[2]."-".$date_set[1]."-".$date_set[0];
					return $mysql_date;
				}
			break;
			case 'yyyy/mm/dd':
				return $valid_date;
			break;
			case 'mm/dd/yyyy':
				$date_set = preg_split($delimiter,$valid_date);
				if(count($date_set) == 3){

					return $date_set[2]."-".$date_set[0]."-".$date_set[1];
				}
			break;
		}
		$this->set_message('convert_valid_date',lang('common.validation.convert_valid_date'));
		return FALSE;
	}
	/**
	 * valid_date
	 *
	 * check if a date is valid
	 *
	 * @access    public
	 * @param    string
	 * @param    string
	 * @return    boolean
	 */

	 function valid_date($str,$format= 'dd/mm/yyyy')
	 {
	        switch($format)
	        {

	            case 'yyyy/mm/dd':
	                if(preg_match("/^(19\d\d|2\d\d\d|\d\d)[\/|-|\.](0?[1-9]|1[012])[\/|-\.](0?[1-9]|[12][0-9]|3[01])$/", $str,$match) && checkdate($match[2],$match[3],$match[1]))
	                {
	                    return TRUE;
	                }
	            break;
	            case 'mm/dd/yyyy':
	                if(preg_match("/^(0?[1-9]|1[012])[\/|-|\.](0?[1-9]|[12][0-9]|3[01])[\/|-|\.](19\d\d|2\d\d\d|\d\d)$/", $str,$match) && checkdate($match[1],$match[2],$match[3]))
	                {
	                    return TRUE;
	                }
	            break;
	            default: // 'dd/mm/yyyy'
	                if(preg_match("/^(0?[1-9]|[12][0-9]|3[01])[\/|-|\.](0?[1-9]|1[012])[\/|-|\.](19\d\d|2\d\d\d|\d\d)$/", $str,$match) && checkdate($match[2],$match[1],$match[3]))
	                {
	                return TRUE;
	                }
	            break;

	        }
	        $this->set_message('valid_date',lang('common.validation.valid_date'));
	        return FALSE;
	 }


	 /**
	 * valid_datetime
	 *
	 * check if a datetime is valid
	 *
	 * @access    public
	 * @param    string
	 * @param    string
	 * @return    boolean
	 */

	 function valid_datetime($str,$format= 'dd/mm/yyyy h:m:s')
	 {
	        switch($format)
	        {

	            case 'yyyy/mm/dd h:m:s':
	                if(preg_match("/^(19\d\d|2\d\d\d|\d\d)[\/|-|\.](0?[1-9]|1[012])[\/|-|\.](0?[1-9]|[12][0-9]|3[01]) (0?[0-9]|1[0-9]|2[0-4]):([0-5]?[0-9]):([0-5]?[0-9])$/", $str,$match) && checkdate($match[2],$match[3],$match[1]))
	                {
	                    return TRUE;
	                }
	            break;
	            case 'mm/dd/yyyy h:m:s':
	                if(preg_match("/^(0?[1-9]|1[012])[\/|-|\.](0?[1-9]|[12][0-9]|3[01])[\/|-|\.](19\d\d|2\d\d\d|\d\d) (0?[0-9]|1[0-9]|2[0-4]):([0-5]?[0-9]):([0-5]?[0-9])$/", $str,$match) && checkdate($match[1],$match[2],$match[3]))
	                {
	                    return TRUE;
	                }
	            break;
	            default: // 'dd/mm/yyyy h:m:s'
	                if(preg_match("/^(0?[1-9]|[12][0-9]|3[01])[\/|-|\.](0?[1-9]|1[012])[\/|-|\.](19\d\d|2\d\d\d|\d\d) (0?[0-9]|1[0-9]|2[0-4]):([0-5]?[0-9]):([0-5]?[0-9])$/", $str,$match) && checkdate($match[2],$match[1],$match[3]))
	                {
	                return TRUE;
	                }
	            break;

	        }
	        $this->set_message('valid_datetime',lang('common.validation.valid_datetime'));
	        return FALSE;
	 }

	/**
	 * Проверка region_id
	 *
	 * @return boolean
	 * @author alex.strigin
	 **/
	public function valid_region_id($region_id='')
	{
		$this->CI->load->model('m_region');
		if(!empty($region_id)){
			return $this->CI->m_region->is_exists($region_id);
		}
		$this->set_message('valid_region_id',lang('common.validation.valid_region_id'));
		return false;
	}

	/**
	 * Проверка metro_id
	 *
	 * @return boolean
	 * @author alex.strigin
	 **/
	public function valid_metro_id($metro_id='')
	{
		$this->CI->load->model('m_metro');
		if(!empty($metro_id)){
			return $this->CI->m_metro->is_exists($metro_id);
		}
		$this->set_message('valid_metro_id',lang('common.validation.valid_metro_id'));
		return false;
	}

	/**
	 * Проверка id заявки.
	 * - заявка должна существовать
	 * - заявка должна принадлежать текущей организации
	 *
	 * @return boolean
	 * @author alex.strigin
	 **/
	public function valid_order_id($order_id)
	{
		$this->CI->load->library('users/users');
		$this->CI->load->model('m_order');
		if(!empty($order_id)){
			if($this->CI->m_order->is_exists($order_id,$this->CI->users->get_org_id())){
				return true;
			}
		}
		$this->set_message('valid_order_id',lang('order.validation.valid_order_id'));
		return false;

	}

	/**
	 * Проверка, доступен ли email
	 *
	 * @return boolean
	 * @author alex.strigin
	 **/
	public function is_email_available($email)
	{
		$this->CI->load->model('users/m_user');
		if(!empty($email)){
			if($this->CI->m_user->is_email_available($email)){
				return true;
			}
		}
		$this->set_message('is_email_available',lang('user.validation.is_email_available'));
		return false;
	}

	/**
	 * Проверка является ли указанный id, id менеджера принадлежащего текущей организации
	 *
	 * @return boolean
	 * @author alex.strigin
	 **/
	public function is_manager_org($manager_id)
	{
		$this->CI->load->library('users/users');
		$this->CI->load->model('users/m_user');
		if(!empty($manager_id)){
			if($this->CI->m_user->is_manager_org($manager_id,$this->CI->users->get_org_id())){
				return true;
			}
		}
		$this->set_message('is_manager_org',lang('user.validation.is_manager_org'));
		return false;
	}

	/**
	 * Проверка, является ли указанный id, id пользователя принадлежащего текущей  организации
	 *
	 * @return boolean
	 * @author alex.strigin
	 **/
	public function is_valid_user_id($user_id)
	{
		$this->CI->load->library('users/users');
		$this->CI->load->model('users/m_user');
		if(!empty($user_id)){
			if($this->CI->m_user->is_exists($user_id,$this->CI->users->get_org_id())){
				return true;
			}
		}
		$this->set_message('is_valid_user_id',lang('user.validation.is_valid_user_id'));
		return false;
	}

	/**
	 * Проверка, валидная ли роль пользователя
	 *
	 * @return boolean
	 * @author alex.strigin
	 **/
	public function is_valid_user_role($user_role)
	{
		$this->CI->load->model('users/m_user');
		if(!empty($user_role)){
			if($this->CI->m_user->is_valid_role($user_role)){
				return true;
			}
		}
		$this->set_message('is_valid_user_role',lang('user.validation.is_valid_user_role'));
		return false;
	}

	/**
	 * Проверка, валидный ли order_id
	 * - Заявка должна существовать, принадлежать агенты
	 *
	 * @return boolean
	 * @author 
	 **/
	public function valid_agent_order_id($order_id)
	{
		$this->CI->load->library('users/users');
		$this->CI->load->model('m_order_user');
		if(!empty($order_id)){
			if($this->CI->m_order_user->does_order_belong_user($order_id,$this->CI->users->get_user_id())){
				return true;
			}
		}
		$this->set_message('valid_agent_order_id',lang('common.validation.valid_agent_order_id'));
		return false;
	}

	/**
	* Проверка, валидная ли категория заявки
	* @return boolean
	* @author alex.strigin
	*/
	public function valid_order_category($category){
		$this->CI->load->model('m_order');
		if(!empty($category)){
			if($this->CI->m_order->check_category($category)){
				return true;
			}
		}
		$this->set_message('valid_order_category',lang('common.validation.valid_order_category'));
		return false;
	}

	/**
	* Проверка, валидный ли тип сделки
	* @return boolean
	* @author alex.strigin
	*/
	public function valid_order_deal_type($deal_type){
		$this->CI->load->model('m_order');
		if(!empty($deal_type)){
			if($this->CI->m_order->check_deal_type($deal_type)){
				return true;
			}
		}
		$this->set_message('valid_order_deal_type',lang('common.validation.valid_order_deal_type'));
		return false;
	}
	
    function run($module = NULL, $group = '') {        
        if (is_object($module)) $this->CI =& $module;
        return parent::run($group);
    }

}