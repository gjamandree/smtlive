<?php
	defined('BASEPATH') OR exit('No direct script access allowed');

	class Users extends CI_Controller {

		public function __construct(){
			parent::__construct();

			$this->load->model(array('users_model'));
		}

		public function index(){

		}

		public function get_users(){
			$response = array('type' => 'success', 
							  'errormessage' => '',
							  'users' => array());

			$user = $this->globallib->authenticate();
			if($user->num_rows() == 1){
				$response['users'] = $this->users_model->get_users()->result_array();
			} else {
				$response['type'] = 'info';
				$response['errormessage'] = 'User does not exists';	
			}

			echo json_encode($response);
		}

		public function login(){
			$response = array('type' => 'success', 
							  'errormessage' => '',
							  'user' => array(),
							  'access_rights' => array());

			$user = $this->globallib->authenticate();
			if($user->num_rows() == 1){
				$user = $user->row();
				$response['user'] = $this->globallib->authenticate()->row_array();
			} else {
				$response['type'] = 'error';
				$response['errormessage'] = 'User does not exists';
			}

			echo json_encode($response);
		}

		public function logout(){
			$response = array('type' => 'success', 
							  'errormessage' => '');

			$user = $this->globallib->authenticate();
			if($user->num_rows() == 1){
				$user = $user->row();
			} else {
				$response['type'] = 'info';
				$response['errormessage'] = 'User does not exists';
			}

			echo json_encode($response);	
		}

		public function create_user(){
			$response = array('type' => 'success', 
							  'errormessage' => '',
							  'id' => '',
							  'client_current_datetime' => '');	

			$user = $this->globallib->authenticate();
			if($user->num_rows() == 1){
				$user = $user->row();
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
			} else {
				$response['type'] = 'info';
				$response['errormessage'] = 'User does not exists';
			}

			echo json_encode($response);
		}

		public function update_user(){
			$response = array('type' => 'success', 
							  'errormessage' => '',
							  'user' => array(),
							  'client_current_datetime' => '');	

			$user = $this->globallib->authenticate();
			if($user->num_rows() == 1){
				$user = $user->row();
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
			} else {
				$response['type'] = 'info';
				$response['errormessage'] = 'User does not exists';
			}

			echo json_encode($response);
		}

		public function delete_user(){
			$response = array('type' => 'success', 
							  'errormessage' => '');	

			$user = $this->globallib->authenticate();
			if($user->num_rows() == 1){
				$user = $user->row();
				
				$condition = array(
					'id' => $this->input->post('user_id')
				); 
				
				$userrecord = $this->users_model->delete($this->input->post('user_id'), $condition);
				if(!$userrecord['status']){
					$response['type'] = 'error';
					$response['errormessage'] = $userrecord['message'];		
				}
			} else {
				$response['type'] = 'info';
				$response['errormessage'] = 'User does not exists';
			}

			echo json_encode($response);
		}
	}
?>