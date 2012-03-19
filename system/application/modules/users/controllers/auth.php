<?php defined('BASEPATH') or die('No direct access script file');


/*
*
*	Authentication controller provides several actions:
*	
*		1. login
*		2. logout
*		3. register
*			-	In the v1.0 release registration will be able only with invite
*
*	@author  -  Alex.strigin <apstrigin@gmail.com>
*	@company -  Flyweb
*/

class Auth extends MX_Controller{


	public function __construct(){

		parent::__construct();

		$this->template->set_theme('start');

	}


	/*
	*
	*	Yeahhhh!
	*
	*
	*/
	public function login(){

	}


	/*
	*
	*	Oh! No!!! Don't do it, man!
	*
	*/
	public function logout(){


	}


	/*
	*
	*	Ok, you are in my trap=)
	*
	*/
	public function register(){


	}
}