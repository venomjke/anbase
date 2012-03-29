<?php defined("BASEPATH") or die("No direct access to script");


/**
 * Класс исключения, имеющий отношение к операциям валидации, проводимым над формами
 *
 * @package default
 * @author alex.strigin
 **/
class ValidationException extends Exception
{
	protected $validation_errors;

	/**
	 * Конструктор исключения. Конструктор принимает либо массив ошибок, либо одну строку.
	 *
	 * @return void
	 * @author 
	 **/
	public function __construct($validation_errors)
	{
		parent::__construct();

		$this->validation_errors = $validation_errors;
	}


	/**
	 * возврат ошибок валидации
	 *
	 * @return mixed
	 * @author alex.strigin
	 **/
	public function get_error_messages()
	{
		return $this->validation_errors;
	}
} // END class ValidationException extends Exception