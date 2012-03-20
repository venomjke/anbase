<?php defined("BASEPATH") or die("No direct access to script");



/*
* гл. модель для работы с user
*/
if(!class_exists("M_User"))
	require_once APPPATH."modules/users/models/m_user.php";

/**
*
*	@author  - Alex.strigin
*	@company - Flyweb 
*
*/
class M_Admin extends M_User{

	function __construct(){

		parent::__construct();
	}

}