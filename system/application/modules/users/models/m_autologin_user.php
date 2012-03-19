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


        /**
         * Выбор user-data для auto-logged юзера
         * Return NULL если заданный key или id не верен.
         *
         * @param       int
         * @param       string
         * @return      object
         */
        function get($user_id, $key)
        {


                $this->db->select("users".'.id');
                $this->db->select("users".'.login');
                $this->db->from("users");
                $this->db->join($this->table_name(), $this->table_name().'.user_id = '."users".'.id');
                $this->db->where($this->table_name().'.user_id', $user_id);
                $this->db->where($this->table_name().'.key_id', $key);
                $query = $this->db->get();
                if ($query->num_rows() == 1) return $query->row();
                return NULL;
        }

        /**
         *
         * Сохранить данные об autologin
         *
         * @param       int
         * @param       string
         * @return      bool
         */
        function set($user_id, $key)
        {
                return $this->insert(array(
                        'user_id'               => $user_id,
                        'key_id'                => $key,
                        'user_agent'    => substr($this->input->user_agent(), 0, 149),
                        'last_ip'               => $this->input->ip_address(),
                ),true);
        }

        /**
         * Удалить данные для autologin
         *
         * @param       int
         * @param       string
         * @return      void
         */
        function delete($user_id, $key)
        {
           $this->db->where('user_id', $user_id);
           $this->db->where('key_id', $key);
           return $this->delete();
        }

        /**
         * Удалить все autologin для выбранного user_id
         *
         * @param       int
         * @return      void
         */
        function clear($user_id)
        {
           $this->db->where('user_id', $user_id);
            return $this->delete();
        }

        /**
         * 
         * @param       int
         * @return      void
         */
        function purge($user_id)
        {
           $this->delete(array( 'user_id'=>$user_id, 'user_agent'=>substr($this->input->user_agent(), 0, 149), 'last_ip'=>$this->input->ip_address()));
        }
}