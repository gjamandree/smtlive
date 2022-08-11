<?php
defined('BASEPATH') OR exit('No direct script access allowed');

    class Globallib
    {
        private $_CI;
        public function __construct()
        {
            $this->_CI = & get_instance();
            $this->_CI->load->model(array('users_model'));
        }


        function authenticate($access_name = '')
        {
            $username = '';
            $password = '';

            if(isset($_SERVER['PHP_AUTH_USER'])){
                $username = $_SERVER['PHP_AUTH_USER'];  
            }
            
            if(isset($_SERVER['PHP_AUTH_PW'])){
                $password = $_SERVER['PHP_AUTH_PW'];    
            }
            
            $user = $this->_CI->users_model->get_user_by_credentials($username, $password);
            if($user->num_rows() == 1){
                if(!empty($access_name)){
                    return $this->_CI->users_model->get_user_by_credentials_and_permission($user->id, $access_name);  
                } else {
                    return $user;
                }
            } else {
                return $user;
            }
        }
    }
?>