<?php
	/*
	*
	*	Генерация меню.
	*
	*/
	$menu = array(
		anchor("","Главная")."|",
		anchor("demo","Демо")."|",
		anchor("register","Регистрация")."|",
		anchor("about","О системе")."|",
		anchor("prices","Цены")."|",
		anchor("company","О нас")."|",
		anchor("faq","Faq")
	);

	$attributes = array('id'=>'menu');
	echo ul($menu,$attributes);
?>