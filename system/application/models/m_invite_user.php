<?php defined("BASEPATH") or die("No direct access to script");



/**
 * Модель таблицы invites_users
 *
 * @package default
 * @author alex.strigin
 **/
class M_Invite_user extends MY_Model
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
		* Определение свойств
		*
		*/
		$this->table = "invites_users";
		$this->primary_key = "id";
		$this->fields = array('id','key_id','email','role','create_date','manager_id','org_id');
		$this->result_mode = 'object';
		/*
		*
		* Правила валидации
		*/
		$this->validate = array(
			array('field'=>'email', 'label'=>'lang:label_email', 'rules'=>'required|xss_clean|valid_email|max_length[100]|is_email_available'),
			array('field'=>'manager_id', 'label'=>'Manager Id', 'rules'=>'is_natural|is_manager_org')
		);
	}

	/**
	 * Получение правил валидации
	 *
	 * @return void
	 * @author 
	 **/
	public function get_validation_rules()
	{
		return $this->validate;
	}

	/**
	 * Проверка того, что инвайт принадлежит опр. организации
	 *
	 * @return boolean
	 * @author alex.strigin
	 **/
	public function belongs_org($id_invite,$org_id)
	{
		return $this->count_all_results(array('id'=>$id_invite,'org_id'=>$org_id))==0?false:true;
	}
	/**
	 * Проверка, существует ли инвайт с заданными key_id и email
	 *
	 * @return boolean
	 * @author alex.strigin
	 **/
	public function is_exists_invite($key_id,$email,$role)
	{
		return $this->count_all_results(array('key_id'=>$key_id,'email'=>$email,'role'=>$role)) == 0?false:true;
	}


	/**
	 * Загрузка инвайта
	 *
	 * @return object
	 * @author alex.strigin
	 **/
	public function load_invite($key,$email)
	{
		$this->select($this->table.".*");
		$this->select("organizations.name as org_name");
		$this->select("organizations.phone as org_phone");
		$this->select("users.name as ceo_name");
		$this->select("users.middle_name as ceo_middle_name");
		$this->select("users.last_name as ceo_last_name");
		$this->from($this->table);
		$this->join("organizations","organizations.id = invites_users.org_id")
		     ->join("users","users.id = organizations.ceo");
		$this->where($this->table.".key_id",$key);
		$this->where($this->table.".email",$email);
		$this->limit(1);
		return $this->db->get()->row();
	}
} // END class M_Invite_user extends MY_Model