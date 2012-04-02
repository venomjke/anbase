<?php defined("BASEPATH") or die("No direct access to script");



if (!function_exists("make_official_name")) {
	/**
	 * Составить из трех честей имени одно полное
	 *
	 * @return string
	 * @author alex.strigin
	 **/
	function make_official_name($name,$middle_name,$last_name)
	{
		return mb_strtoupper(mb_substr($last_name,0,1)).mb_substr($last_name,1).' '.mb_strtoupper(mb_substr($name,0,1)).'.'.mb_strtoupper(mb_substr($middle_name,0,1));
	}
}