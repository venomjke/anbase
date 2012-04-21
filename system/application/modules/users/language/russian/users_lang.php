<?php

$lang['auth_incorrect_login']    = 'Неправильный логин';
$lang['auth_incorrect_password'] = 'Неправильный пароль';
$lang['auth_incorrect_captcha']  = 'Неверно введен код';
$lang['not_activated']			 = 'Не активирован';
$lang['auth_message_logged_out'] = 'Вы вышли из системы';


$lang['success_register']		 = 'Вы успешно зарегистрировались';
$lang['register_org_error']		 = 'Возникла ошибка во время регистрации организации';
$lang['register_user_error']	 = 'Возникла ошибка во время регистрации пользователя';


$lang['users.error_change_password'] = 'Текущие пароли не совпадают';
/*
*
* Модель M_User
*
**/
$lang['label_user_id']  = 'Идентификатор пользователя';
$lang['label_login']    = 'Логин';
$lang['label_email']	= 'Email';
$lang['label_password'] = 'Пароль';
$lang['label_name']     = 'Имя';
$lang['label_middle_name'] = 'Отчество';
$lang['label_last_name']= 'Фамилия';
$lang['label_phone']	= 'Телефон';
$lang['label_role']		= 'Роль';

/*
*
* M_User:callbacks и правила валидации для user
*
*/
$lang['user.validation.valid_user_id'] = 'В поле "%s" указан не верный идентификатор пользователя';
$lang['user.validation.is_email_available'] = 'Пользователь с указанным в поле "%s" email уже существует';
$lang['user.validation.is_manager_org'] = 'Указанный в поле "%s" не является менеджером, а также сотрудником текущей организации';
$lang['user.validation.is_valid_user_id'] = 'Указанный в поле "%s" идентификатор пользователя не действительный';
$lang['user.validation.is_valid_user_role'] = 'В поле "%s" указана неверная должность';
$lang['error_check_role'] = $lang['user.validation.is_valid_user_role'];

/*
*
* Модель M_Organization
*/
$lang['label_org_name']  = 'Название организации';
$lang['label_org_phone'] = 'Телефон организации';
$lang['label_org_email'] = 'Email организации';

