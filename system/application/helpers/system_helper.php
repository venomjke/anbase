<?php defined("BASEPATH") or die("No direct access to script");


if(!function_exists("is_production_mode")){
	function is_production_mode(){
		return ENVIRONMENT == ANBASE_PROD;
	}
}