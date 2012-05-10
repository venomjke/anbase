<?php defined("BASEPATH") or die("No direct access to script");

/*
*
*	Правила валидации учетных данных user'a
*
*
*/
$this->form_validation->set_rules('r_login', 'Логин', 'trim|required|xss_clean|min_length[3]|max_length[50]|alpha_dash|is_unique[users.login]');
$this->form_validation->set_rules('r_email', 'Email', 'trim|required|xss_clean|valid_email|is_unique[users.email]');
$this->form_validation->set_rules('r_password', 'Пароль', 'trim|required|xss_clean|min_length[6]|max_length[200]|alpha_dash');
$this->form_validation->set_rules('r_re_password', 'Копия пароля', 'trim|required|xss_clean|matches[r_password]');
/*
*
*
*	Правила валидации личных данных
*
*/

$this->form_validation->set_rules('name','Имя','trim|required|xss_clean|min_length[3]|max_length[15]');
$this->form_validation->set_rules('middle_name','Отчество','trim|required|xss_clean|min_length[3]|max_length[15]');
$this->form_validation->set_rules('last_name','Фамилия','trim|required|xss_clean|min_length[3]|max_length[15]');
$this->form_validation->set_rules('phone','Телефон','valid_phone');

/*
*
*	Правила валидации данных организации
*
*/
$this->form_validation->set_rules('org_name','Имя организации','trim|required|xss_clean|min_length[3]|max_length[150]');
$this->form_validation->set_rules('org_email','Email организации','trim|required|xss_clean|min_length[5]|max_length[100]');
$this->form_validation->set_rules('org_phone','Телефон Организации','required|trim|valid_phone');
/*
*
*	Правила валидации капчи
*
*/
$this->form_validation->set_rules('recaptcha_response_field', 'Код подтверждения', 'trim|xss_clean|required|callback__check_recaptcha');