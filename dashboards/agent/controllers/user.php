<?php defined("BASEPATH") or die("No direct access to script");

/**
* Класс, реализующий логику контроллера, обеспечивающего выполнение 
* различных операций над пользователями
*
* @package anbase
* @author alex.strigin
*/
class User extends MX_Controller
{
	
	function __construct()
	{
		/*
		*
		* Загрузка либ
		*
		*/
		$this->load->library('agent/Agent_Users');
		$this->load->library('Ajax');

		if(!$this->agent_users->is_logged_in_as_agent()){
			redirect('');
		}

		/*
		*
		* Загрузка пакета сообщений
		*
		*/
		$this->load->language('agent/messages');


		/*
		*
		* Настройка шаблона
		*
		*/
		$this->template->set_theme('dashboard');
		$this->template->set_partial('dashboard_head','dashboard/dashboard_head');


		/*
		*
		*	Загрузка скриптов и всякой другой мета инфы
		*
		*/
		$this->template->append_metadata('<script type="text/javascript" src="'.site_url("dashboards/agent/js/agent.js").'"></script>');
		$this->template->append_metadata('<script type="text/javascript">
			agent.init({ baseUrl:"'.base_url().'"})
		</script>');

	}


	/**
	 * Редирект на user/staff/
	 *
	 * @return void
	 * @author alex.strigin
	 **/
	public function index()
	{
		redirect('agent/user/staff');
	}


	/**
	 * Управление разделом сотрудники
	 *
	 * @return void
	 * @author alex.strigin
	 **/
	public function staff()
	{
		$section = $this->input->get('act')?$this->input->get('act'):'view';

		switch ($section) {
			case 'view':
			default:
				/*
				* Отображение списка сотрудников
				*
				*/
				$this->_view_staff();
				break;
		}
		
	}

	/**
	 * Управление разделом админы
	 *
	 * @return void
	 * @author alex.strigin
	 **/
	public function admins()
	{
		$section = $this->input->get('act')?$this->input->get('act'):'view';

		switch ($section) {
			case 'view':
			default:
				/*
				* Отображение списка администраторов
				*
				*/
				$this->_view_admins();
				break;
		}
	
	}

	/**
	 * Вывод списка администраторов
	 *
	 * @return void
	 * @author alex.strigin
	 **/
	private function _view_admins()
	{
		$this->template->set_partial('sidebar','dashboard/user/sidebar');

		$admins = $this->agent_users->get_list_admins();

		$this->template->build('user/admins',array('admins' => $admins));
	}

	/**
	 * Вывод списка членов организации
	 *
	 * @return void
	 * @author alex.strigin
	 **/
	private function _view_staff()
	{
		$this->template->set_partial('sidebar','dashboard/user/sidebar');

		$staff = $this->agent_users->get_list_staff();
		$this->template->build('user/staff',array('staff' => $staff));
	}
}// END User class