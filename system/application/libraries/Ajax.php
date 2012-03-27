<?php
/**
 * Промежуточный класс между CI и Контроллером, предназначенный для формировния ajax запросов 
 *
 * @package default
 * @author Alex.strigin<apstrigin@gmail.com>
 **/
class Ajax
{

	/*
	*  
	* instance приложения
	*/ 
	private $_ci;

	/**
	 * Конструктор
	 *
	 * @return void
	 * @author 
	 **/
	public function __construct()
	{
		$this->_ci = get_instance();
	}


	/**
	 * Оберетка над CI is_ajax_request, мне не нравится request=)
	 *
	 * @return void
	 * @author 
	 **/
	function is_ajax_request()
	{
		return $this->_ci->input->is_ajax_request();
	}


	/**
	 * Сборка и отправка запроса ajax в формате json
	 *
	 * @return void
	 * @author Alex.strigin
	 **/
	public function build_json($data = array())
	{
		$this->_ci->output->set_header('Content-Type: application/json; charset=utf-8');
		$this->_ci->output->set_output(json_encode($data));
	}

	/**
	 * Получение ajax запроса и его разборка
	 *
	 * @return void
	 * @author 
	 **/
	public function unbuild_json($data)
	{
		return json_decode($data);
	}
} // END class Ajax