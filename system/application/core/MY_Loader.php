<?php (defined('BASEPATH')) OR exit('No direct script access allowed');

/* load the MX_Loader class */
require APPPATH."libraries/MX/Loader.php";

class MY_Loader extends MX_Loader {

	protected $_ci_exceptions_path = 'exceptions/';
	protected $_ci_exceptions = array();

	/**
	  * Метод для загрузки классов исключение
	  *
	  * @return void
	  * @author alex.strigin
	  **/
	 public function exception($class)
	 {
	 	//$class = strtolower($class);
	 	if(in_array($class,$this->_ci_exceptions)) return false;

	 	if(file_exists(APPPATH.$this->_ci_exceptions_path.$class.'.php')){
	 		include_once(APPPATH.$this->_ci_exceptions_path.$class.'.php');
	 		$this->_ci_exceptions[] = $class;
	 		log_message('debug','Exception file '.$class.' loaded');
	 	}else{
	 		log_message('debug','Exception file '.$class.' isn\'t loaded');
	 	}
	 } 
}