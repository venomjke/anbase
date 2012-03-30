<?php defined("BASEPATH") or die("No direct access to script");

/*
*	Это джигурда для того, чтобы не возникло проблем во время Extends
*/
if(!class_exists("Users"))
	require_once APPPATH."modules/users/libraries/users.php";

/**
*
*	@author  - Alex.strigin
*	@company - Flyweb
*
*/
class Admin_Users extends Users{

	function __construct() {
		
		parent::__construct();
		
		/*
		*
		*	Загрузка моделей
		*
		*/
		$this->ci->load->model('admin/m_admin');
	}


	/**
	 * Залогинен ли пользователь как админ, а?
	 *
	 * @return void
	 * @author alex.strigin
	 **/
	public function is_logged_in_as_admin()
	{
		if ($this->is_logged_in() && $this->is_admin()) {	
			return true;
		}
		return false;
	}


	/**
	 * Редактирование персонального профайла
	 *
	 * @return boolean
	 * @author alex.strigin
	 **/
	public function edit_personal_profile()
	{
		$this->ci->form_validation->set_rules($this->ci->m_admin->get_validation_rules());
		if($this->ci->form_validation->run()){
			$data = array();
			/*
			*
			* собираем данные для изменения т.к правила валидации required не
			* предусматривают
			*
			*/
			if($this->ci->input->post('name')) $data['name'] = $this->ci->input->post('name');
			if($this->ci->input->post('middle_name')) $data['middle_name'] = $this->ci->input->post('middle_name');
			if($this->ci->input->post('last_name')) $data['last_name'] = $this->ci->input->post('last_name');
			if($this->ci->input->post('phone')) $data['phone'] = $this->ci->input->post('phone');

			if(!empty($data)){
				$this->ci->m_user->update($this->get_user_id(),$data,true);
				$this->update_admin_session_data($data);
				return TRUE;
			}else{

				$this->error += array('not_recive_data' => lang('common.empty_data'));
				return FALSE;
			}
			return TRUE;
		}
		$this->error += array( 'name' => $this->ci->form_validation->error('name'));
		$this->error += array( 'middle_name' => $this->ci->form_validation->error('middle_name'));
		$this->error += array( 'last_name' => $this->ci->form_validation->error('last_name'));
		$this->error += array( 'phone' => $this->ci->form_validation->error('phone'));
	
		return TRUE;
	}


	/**
	 * Редактирование организационного профайла
	 *
	 * @return boolean
	 * @author alex.strigin
	 **/
	public function edit_organization_profile()
	{
		$this->ci->load->model('users/m_organization');
		$this->ci->form_validation->set_rules($this->ci->m_organization->get_validation_rules());
		if($this->ci->form_validation->run()){
			$data = array();
			/*
			*
			* собираем данные для изменения т.к правила валидации required не
			* предусматривают
			*
			*/
			if($this->ci->input->post('name')) $data['name'] = $this->ci->input->post('name');

			if($this->ci->input->post('email')) $data['email'] = $this->ci->input->post('email');

			if($this->ci->input->post('phone')) $data['phone'] = $this->ci->input->post('phone');

			if(!empty($data)){
				$this->ci->m_organization->update($this->get_org_id(),$data,true);
				return TRUE;
			}else{

				$this->error += array('not_recive_data' => lang('common.empty_data'));
				return FALSE;
			}
			return TRUE;
		}
		$this->error += array( 'name' => $this->ci->form_validation->error('name'));
		$this->error += array( 'email' => $this->ci->form_validation->error('email'));
		$this->error += array( 'phone' => $this->ci->form_validation->error('phone'));
		
		return false;
	}

	/**
	 * Редактирование системной информации
	 *
	 * @return void
	 * @author alex.strigin
	 **/
	public function edit_system_profile()
	{
		$this->ci->form_validation->set_rules($this->ci->m_admin->get_validation_rules());
		if($this->ci->form_validation->run()){
			$data = array();
			/*
			*
			* собираем данные для изменения т.к правила валидации required не
			* предусматривают
			*
			*/
			if($this->ci->input->post('password')) $data['password'] = $this->ci->input->post('password');

			if(!empty($data)){
				$this->ci->m_organization->update($this->get_org_id(),$data,true);
				return TRUE;
			}else{

				$this->error += array('not_recive_data' => lang('common.empty_data'));
				return FALSE;
			}
			return TRUE;
		}
		$this->error += array( 'password' => $this->ci->form_validation->error('password'));
		return false;
		return false;
	}


	/**
	 * Метод, выполняющий обновление данных сессии
	 *
	 * @return void
	 * @author alex.strigin
	 **/
	public function update_admin_session_data($data)
	{
		$this->ci->session->set_userdata($data);
	}
}


