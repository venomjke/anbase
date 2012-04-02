<?php defined("BASEPATH") or die("No direct access to script");



/*
* гл. модель для работы с user
*/
if(!class_exists("M_User"))
	require_once APPPATH."modules/users/models/m_user.php";

/**
*
*	@author  - Alex.strigin
*	@company - Flyweb 
*
*/
class M_Admin extends M_User{

	function __construct(){

		parent::__construct();
	}

	/**
	 * Метод возвращает правила валидации
	 *
	 * @return array
	 * @author alex.strigin
	 **/
	public function get_validation_rules()
	{
		return $this->validate;
	}


	/**
	 * Смена должности сотрудника. Правила преобразования при смене описаны в док. "Управление пользователями"
	 *
	 * @param int
	 * @param string
	 * @param string
	 * @return void
	 * @author 	alex.strigin
	 **/
	public function change_position($user_id,$old_role,$new_role)
	{
		/*
		*
		* Изменения происходят только при переходах Агент -> ( Менеджер | Админ ), а также ( Менеджер | Админ ) -> Агент
		*/
		if( ($old_role == M_User::USER_ROLE_MANAGER or $old_role == M_User::USER_ROLE_ADMIN) && $new_role == M_User::USER_ROLE_AGENT){

			$this->update($user_id,array('role' => $new_role),true);
			/*
			*
			* освободить бедных агентов из рабства =)
			*/
			$this->db->delete('managers_users',array('manager_id'=>$user_id)); 
		}else if( $old_role == M_User::USER_ROLE_AGENT and ($new_role == M_User::USER_ROLE_ADMIN or $new_role == M_User::USER_ROLE_MANAGER) ){
			$this->update($user_id,array('role' =>$new_role),true);
			/*
			*
			* высвободить агента из рабства
			*/
			$this->db->delete('managers_users',array('user_id'=>$user_id));
		}else{
			$this->update($user_id,array('role'=>$new_role),true);
		}
	}

}