<?php

/**
* Класс, реализующий логику контроллера, обеспечивающего выполнение различных операций над пользователями
** @package default
* @author alex.strigin
*/
class Users extends MX_Controller
{
	/**
	 * Конструктор
	 *
	 * @return void
	 * @author alex.strigin
	 **/
	public function __construct()
	{
		/*
		*
		* Загрузка либ
		*
		*/
		$this->load->library('admin/Admin_Users');

		if(!$this->admin_users->is_logged_in_as_admin()){
			redirect('');
		}

		/*
		*
		* настройка шаблона
		*
		*/
		$this->template->set_theme('dashboard');
		$this->template->set_partial('dashboard_head','dashboard/dashboard_head');
	}

	/**
	*
	*
	* 1. Просмотр списка администрации
	* 2. Просмотр списка сотрудников
	* 3. Добавление новый пользователей (инвайты)
	*    3.1 Инвайты для агентов
	*    3.2 Инвайты для менеджеров
	*    3.3 Инвайты для администраторов
	*/
}// END Users class