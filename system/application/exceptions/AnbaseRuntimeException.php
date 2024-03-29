<?php defined("BASEPATH") or die("No direct access to script");

/**
 * Класс, реализущию логику исключения времени выполнения
 *k
 * @package default
 * @author alex.strigin
 **/
class AnbaseRuntimeException extends RuntimeException
{
	/**
	 * Конструктор
	 *
	 * @return void
	 * @author 
	 **/
	public function __construct($message='',$code=0)
	{
		parent::__construct($message,$code);
	}

	/**
	 * возварт сообщения об ошибке
	 *
	 * @return string
	 * @author alex.strigin
	 **/
	public function get_error_message()
	{
		return $this->getMessage();
	}

	/**
	 * Возврат кода об ошибке
	 *
	 * @return int
	 * @author alex.strigin
	 **/
	public function get_error_code()
	{
		return $this->getCode();
	}
} // END class RuntimeException