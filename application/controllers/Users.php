<?php
	defined('BASEPATH') OR exit('No direct script access allowed');

	class Users extends CI_Controller {

		public function __construct(){
			parent::__construct();

			$this->load->model(array('users_model'));
		}

		public function index(){

		}

		public function login(){
			
		}

		public function logout(){
			//	
		}

		public function create_user(){
			if($GLOBALS['ready']){
				$email_exists = $this->users_model->email_exists($this->input->post('email'));
				$username_exists = $this->users_model->username_exists($this->input->post('username'));

				$response['type'] = 'info';
				if($email_exists){
					$response['errormessage'] = 'Email already exists';
				} else if($username_exists){
					$response['errormessage'] = 'Username already exists';	
				} else {
					$response['type'] = 'success';
					
					$data = array(
						'email' => $this->input->post('email'),
						'username' => $this->input->post('username'),
						'password' => $this->input->post('password'),
						'firstname' => $this->input->post('firstname'),
						'lastname' => $this->input->post('lastname'),
						'user_type_id' => $this->input->post('user_type_id'),
						'date_created' => $this->input->post('client_current_datetime')
					);

					$userrecord = $this->users_model->create($data);
					if(!$userrecord['status']){
						$response['type'] = 'error';
						$response['errormessage'] = $userrecord['message'];
					} else {
						$response['id'] = $userrecord['last_insert_id'];
						$response['client_current_datetime'] = $data['date_created'];
					}	
				}
			}
		}

		public function update_user(){
			if($GLOBALS['ready']){
				$email_exists = $this->users_model->email_exists($this->input->post('email'));
				$username_exists = $this->users_model->username_exists($this->input->post('username'));
				$user_record = $this->users_model->get_user_by_fieldname('username', $this->input->post('username'))->row();

				$response['type'] = 'info';
				if($email_exists){
					$response['errormessage'] = 'Email already exists';
				} else if(($username_exists) && ($user_record->id != $this->input->post('user_id'))){
					$response['errormessage'] = 'Username already exists';	
				} else {
					$response['type'] = 'success';

					$data = array(
						'email' => $this->input->post('email'),
						'username' => $this->input->post('username'),
						'password' => $this->input->post('password'),
						'firstname' => $this->input->post('firstname'),
						'lastname' => $this->input->post('lastname'),
						'user_type_id' => $this->input->post('user_type_id'),
						'date_modified' => $this->input->post('client_current_datetime')
					);

					$condition = array(
						'id' => $this->input->post('user_id')
					);

					$userrecord = $this->users_model->update($condition['id'], $data, $condition);
					if(!$userrecord['status']){
						$response['type'] = 'error';
						$response['errormessage'] = $userrecord['message'];
					} else {
						$data['id'] = $condition['id'];
						$response['user'] = $data;
					}
				}
			}
		}

		public function delete_user(){
			if($GLOBALS['ready']){
				$condition = array(
					'id' => $this->input->post('user_id')
				); 
				
				$userrecord = $this->users_model->delete($this->input->post('user_id'), $condition);
				if(!$userrecord['status']){
					$response['type'] = 'error';
					$response['errormessage'] = $userrecord['message'];		
				}
			}
		}
	}
?>