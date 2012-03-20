<?php defined("BASEPATH") or die("No direct access to script");


/*
* гл. модель для работы с user
*/
if(!class_exists("M_User"))
	require_once APPPATH."modules/users/models/m_user.php";

/**
*
*
*	@author  - Alex.strigin <apstrigin@gmail.com>
*	@company - Flyweb 
*
*/
class M_Agent extends M_User{


	public function __construct(){

		parent::__construct();

	}
}