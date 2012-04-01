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
                $this->db->select("users".'.email');
                $this->db->select("users".'.role');
                $this->db->select("users".'.name');
                $this->db->select("users".'.middle_name');
                $this->db->select("users".'.last_name');
                $this->db->select("users".'.phone');
                $this->db->from("users");

                $this->db->join($this->table, $this->table.'.user_id = '."users".'.id');
                $this->db->where($this->table.'.user_id', $user_id);
                $this->db->where($this->table.'.key_id', $key);
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
                $this->insert(array(
                        'user_id'               => $user_id,
                        'key_id'                => $key,
                        'user_agent'    => substr($this->input->user_agent(), 0, 149),
                        'last_ip'               => $this->input->ip_address()
                ),true);
                return TRUE;
        }

        /**
         * Удалить данные для autologin
         *
         * @param       int
         * @param       string
         * @return      void
         */
        function remove($user_id, $key)
        {
           return $this->delete(array('user_id' => $user_id, 'key_id' => $key));
        }

        /**
         * Удалить все autologin для выбранного user_id
         *
         * @param       int
         * @return      void
         */
        function clear($user_id)
        {
            return $this->delete('user_id',$user_id);
        }

        /**
         * 
         * @param       int
         * @return      void
         */
        function purge($user_id)
        {
           return $this->delete(array( 'user_id'=>$user_id, 'user_agent'=>substr($this->input->user_agent(), 0, 149), 'last_ip'=>$this->input->ip_address()));
        }
}