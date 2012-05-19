<?php defined("BASEPATH") or die("No direct access to script");


/**
 * Класс, реализующий логику формирования и отображения различных графиков и статистических данных.
 *
 * @package default
 * @author 
 **/
class Analytics extends MX_Controller
{
	public function __construct()
	{
		/*
		* Загрузка либ
		*/
		$this->load->library('admin/Admin_Users');
		$this->load->library('Org_Statistic');

		if(!$this->admin_users->is_logged_in_as_admin()){
			redirect('');
		}

		/*
		* Сообщения
		*/
		$this->load->language('admin/messages');

		$this->template->set_theme('dashboard');
		$this->template->set_partial('dashboard_head','dashboard/dashboard_head');
		$this->template->set_partial('dashboard_user','dashboard/dashboard_user');
		$this->template->set_partial('dashboard_menu','dashboard/dashboard_menu');

		$this->template->append_metadata('<script type="text/javascript" src="'.site_url("dashboards/admin/js/admin.js").'"></script>');
		$this->template->append_metadata('<script type="text/javascript"> $(function(){ admin.init({ baseUrl:"'.site_url("admin/analytics").'" }); }); </script>');
	}

	public function index()
	{
		$this->template->build('analytics/info');
	}
} // END class Analytics extends M