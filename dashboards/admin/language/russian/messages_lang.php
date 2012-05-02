<?php defined("BASEPATH") or die("No direct access to script");



/*
*
*
* Сообщения admin
*
*/
$lang['no_enough_right'] = 'Недостаточно прав для выполнения данной операции';
/*
*
* Сообщения profile
*
*/
$lang['success_edit_personal_profile'] = 'Изменения сохранены';
$lang['success_edit_org_profile']      = $lang['success_edit_personal_profile'];
$lang['success_edit_sys_profile']	   = $lang['success_edit_personal_profile'];

/*
*
*
* Сообщения user
*
*/

$lang['success_delete_staff']		   = 'Сотрудники успешно уволены.';
$lang['success_delete_admins']         = 'Члены администрации успешно уволены.';

$lang['error_delete_staff']			   = 'Не удалось уволить сотрудников, попробуйте еще раз.';
$lang['error_delete_admins']		   = 'Не удалось уволить членов администрации, попробуйте еще раз.';

$lang['confirm_change_position']	   = 'Вы уверены, что желаете изменить должность?';
$lang['success_change_position_employee']	   = 'Должность сотрудника успешно отредактирована';

$lang['success_assign_manager_agent']  = 'Агент успешнр привязан к менеджеру';

$lang['not_legal_data']				   = 'Не допустимые данные';


$lang['success_send_agent_invite']   = 'Инвайт создан, и отправлен будущему агент';
$lang['success_send_manager_invite'] = 'Инвайт создан, и отправлен будущему менеджеру';
$lang['success_send_admin_invite']   = 'Инвайт создан, и отправлен будущему админу';

$lang['success_del_user_invites'] = 'Инвайт(ы) успешно удалены';
$lang['confirm_delete_invite'] = 'Удалить запись инвайта?';


$lang['success_unbind_manager'] = 'Агент больше не привязан к менеджеру';
$lang['unbind_manager'] = 'Вы действительно хотите отвязаять агента от менеджера?';

$lang['success_del_staff']  = 'Член(ы) персонала успешно удален(ы)';
$lang['success_del_admins'] = 'Администратор(ы) успешно удален(ы)';
/*
* Регистрация админа
*/
$lang['success_register_admin'] = 'Поздравляем, Вы успешно зарегистрировались как администратор';
/*
*
* Заявки
*/
$lang['success_add_order'] = 'Заявка добавлена';
$lang['success_edit_order'] = 'Изменения сохранены';
$lang['success_del_order'] = 'Заявки успешно удалены';
$lang['success_delegate_order'] = 'Заявка успешно делегирована';