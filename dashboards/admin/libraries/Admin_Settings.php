<?php defined("BASEPATH") or die("No direct access to script");

/**
 * Класс, скрывающий логику действий над моделью настроек
 *
 * @package default
 * @author alex.strigin
 **/
class Admin_Settings
{

	private $ci;

	public function __construct()
	{
		$this->ci = get_instance();

		/*
		* Подключение исключение
		*/
		$this->ci->load->exception('AnbaseRuntimeException');
		$this->ci->load->exception('ValidationException');

		/*
		* Модели
		*/	
		$this->ci->load->model('m_settings_org');
	}


	public function get_settings_org()
	{
		/*
		* У любоей организации всегда есть 1 объект настроек, поэтому пустых значений быть не может 
		*/
		return $this->ci->m_settings_org->get(array('org_id'=>$this->ci->admin_users->get_org_id()));
	}


	public function edit()
	{
		$fields = array('default_category','default_dealtype','price_col','regions_col','metros_col','phone_col');

		$this->ci->form_validation->set_rules($this->ci->m_settings_org->edit_validation_rules);

		if($this->ci->form_validation->run($this->ci->m_settings_org)){

			$data = array_intersect_key($this->ci->input->post(), array_flip($fields));

			if(!empty($data)){
				$this->ci->m_settings_org->edit($this->ci->admin_users->get_org_id(),$data);
			}else{
				throw new AnbaseRuntimeException(lang("common.not_legal_data"));
			}
		}

		$errors_validation = array();

		if(has_errors_validation($fields,$errors_validation)){
			throw new ValidationException($errors_validation);
		}
	}
} // END class Admin_Settings