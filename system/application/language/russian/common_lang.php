<?php defined("BASEPATH") or die("No direct access to script");


/*
*
* Ошибки БД и валидации
*
*/
$lang['common.empty_data'] = 'Данные не получены';
$lang['common.update_error']    = 'Не удалось выполнить обновление записи';
$lang['common.insert_error']    = 'Возникла ошибка во время добавления, попробуйте еще раз';
$lang['common.delete_error']    = 'Возникла ошибка во время удаления, попробуйте еще раз';
/*
* 
* Логические ошибки
*/
$lang['cant_apply_yourself'] = 'Собственную должность изменить нельзя';


$lang['common.not_legal_data'] = 'Переданные данные недопустимы';


/*
*
* Заявки ( orders )
*/
$lang['order.label_number'] = 'Номер заявки';
$lang['order.label_create_date'] = 'Дата создания';
$lang['order.label_category'] = 'Категория';
$lang['order.label_valid_dealtype'] = 'Тип сделки';
$lang['order.label_region_id'] = 'Район';
$lang['order.label_metro_id'] = 'Метро';
$lang['order.label_price'] = 'Цена';
$lang['order.label_description'] = 'Описание';
$lang['order.label_delegate_date'] = 'Дата делегирования';
$lang['order.label_finish_date'] = 'Дата закрытия';
$lang['order.label_phone'] = 'Телефон';
$lang['order.label_state'] = 'Состояние';
$lang['order.label_any_metro'] = 'Любое метро';

$lang['order.validation.valid_state'] = 'Поле "%s" содержит неверное состояние';
$lang['order.validation.valid_dealtype'] = 'Поле "%s" содержит неверный вид сделки';
$lang['order.validation.valid_category'] = 'Поле "%s" содержит неверную категорию';
$lang['order.validation.valid_order_id'] = 'Поле "%s" содержит неправильный id заявки';

/*
* Инвайты
*/
$lang['invites.email_not_available'] = 'Указанный в поле "%s" адрес уже используется.';
/*
*
* Валидация
*
*/
$lang['common.validation.valid_date'] = 'Поле "%s" должно содержать дату правильного формата';
$lang['common.validation.valid_datetime'] = 'Поле "%s" должно содержать дату и время правильного формата';
$lang['common.validation.valid_region_id'] = 'Поле "%s" должно содержать id существующего района';
$lang['common.validation.valid_metro_id'] = 'Поле "%s" должно содержать id существующего метро';
$lang['common.validation.valid_agent_order_id'] = 'Поле "%s" должно содержать верный id заявки';
$lang['common.validation.valid_order_category'] = $lang['order.validation.valid_category'];
$lang['common.validation.valid_order_deal_type'] = $lang['order.validation.valid_dealtype'];
$lang['common.validation.convert_valid_date'] = 'Поле "%s" содержит не валидную дату';
$lang['common.validation.valid_phone'] = 'Поле "%s" содержит неправильный номер телефона';
$lang['common.validation.exists_email'] = 'Пользователя с указанным адресом электронной почты не существует';