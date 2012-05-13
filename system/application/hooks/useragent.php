<?php defined("BASEPATH") or die("No direct access to script");


function check_browser(){
	/*
	* Определить модель браузера. Сверить с заранее рекомендуемыми, и вывеси предупреждение в случае необходимости
	*/
	$available_browsers = array('Chrome','Opera','Firefox','Safari');
	$app = get_instance();
	$app->load->library('user_agent');

	if(!in_array($app->agent->browser(),$available_browsers)){
		$app->session->set_userdata('browser','bad');
	}else if($app->session->userdata('browser')){
		$app->session->unset_userdata('browser');
	}


};