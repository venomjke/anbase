<?php defined("BASEPATH") or die("No direct access to script");



if(!function_exists("load_partial_template")){

	/**
	 * Функция берет объект шаблон, и пытается загрузить partial
	 *	предварительно проверив, существует ли он.
	 *
	 * @return void
	 * @author Alex.strigin <apstrigin@gmail.com> 
	 **/
	function load_partial_template(&$template,$partial)
	{
		if(!empty($template['partials'][$partial])){
			echo $template['partials'][$partial];
		}else{
			echo "partial {$partial} not found";
		}
	}
}