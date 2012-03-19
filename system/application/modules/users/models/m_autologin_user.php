<?php defined("BASEPATH") or exit("No direct access to script file");


/*
*
*   Model users represents autologin data
*   tables: autologin_users
*
* @author  - Alex.strigin 
* @company - Flyweb 
*
*/

class M_Autologin_user extends MY_Model{


    public function __construct(){

        parent::__construct();

        /*
        *
        *   Структура модели
        *
        */
        $this->table       = 'autologin_users';
        $this->primary_key = 'key_id';
        $this->fields      = array('key_id','user_id','user_agent','last_ip','last_login');
        $this->result_mode = 'object';
        /*
        *
        *   Правила валидации
        *
        */
        $this->validate   = array();
    }
}