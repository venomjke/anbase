<?php defined("BASEPATH") or die("No direct access to script");


/**
 * Класс, скрывающий логику действий над моделью настроек
 *
 * @package default
 * @author alex.strigin
 **/
class Settings_Organization
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
		* библиотека
		*/
		$this->ci->load->library("users/users");
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
		return $this->ci->m_settings_org->get(array('org_id'=>$this->ci->users->get_org_id()));
	}

} // END class Admin_Settings