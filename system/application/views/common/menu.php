<?php
	/*
	*	Генерация меню.
	*/
	$menu = array(
		anchor("","Главная"),
		anchor("demo","Демо"),
		anchor("register","Регистрация"),
		anchor("about","О системе"),
		anchor("prices","Цены"),
		anchor("company","О нас"),
		anchor("wiki","wiki")
	);
	echo ul($menu);
?>