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
			$login_email = $this->input->post('login_email');
			$login_password = $this->input->post('login_password');
			
			$this->form_validation->set_rules('login_email', 'Email address', 'trim|valid_email');
			$this->form_validation->set_rules('login_password', '', 'trim');
			$this->form_validation->set_rules('login_form', '', 'callback_login_valid');
			
			if($this->form_validation->run() == false){
				$this->load->view('login');
			} else {
				if(!$this->session->has_userdata('current_user')){
					$user = $this->users_model->get_user_by_credentials($login_email, $login_password);

					$this->session->set_userdata('current_user', $user->row_array());

					redirect(base_url(), 'refresh');
				}	
			}		
		}

		public function login_valid(){
			$login_email = $this->input->post('login_email');
			$login_password = $this->input->post('login_password');
			
			if((!isset($login_email)) || (empty($login_email))){
				$this->form_validation->set_message('login_valid', 'Email is required');
				return false;
			} else if((!isset($login_password)) || (empty($login_password))){
				$this->form_validation->set_message('login_valid', 'Password is required');
				return false;
			} else {
				$user = $this->users_model->get_user_by_credentials($login_email, $login_password);
				if($user->num_rows() <= 0){
					$this->form_validation->set_message('login_valid', 'User not recognized');
					return false;
				} else {
					return true;
				}
			}
		}

		public function logout(){
			if($this->session->has_userdata('current_user')){
				$this->session->unset_userdata('current_user');
			}

			redirect(base_url(), 'refresh');	
		}

		public function create_user(){
			$response = array();

			$this->form_validation->set_rules('register_firstname', 'Firstname', 'trim|required');
			$this->form_validation->set_rules('register_lastname', 'Lastname', 'trim|required');
			$this->form_validation->set_rules('register_email', 'Email address', 'trim|required|valid_email|is_unique[users.email]');
			$this->form_validation->set_rules('register_password', 'Password', 'trim|required');
			$this->form_validation->set_rules('register_repeat_password', 'Repeat Password', 'trim|required|matches[register_password]');

			if($this->form_validation->run() == false){
				$this->load->view('register');
			} else {		
				$user_type_id = $this->input->post('user_type_id');		
				$user_type_id = (isset($user_type_id) ? $user_type_id : '2');

				$client_current_datetime = $this->input->post('client_current_datetime');
				$client_current_datetime = (isset($client_current_datetime) ? $client_current_datetime : date('Y-m-d H:i:s'));

				$data = array(
					'email' => $this->input->post('register_email'),
					'password' => $this->input->post('register_password'),
					'firstname' => $this->input->post('register_firstname'),
					'lastname' => $this->input->post('register_lastname'),
					'user_type_id' => $user_type_id,
					'date_created' => $client_current_datetime
				);

				$userrecord = $this->users_model->create($data);
				if(!$userrecord['status']){
					$response['custom_error'] = $userrecord['message'];

					$this->load->view('register', $response);	
				} else {
					$response['success_message'] = 'Registered Successfully';

					$this->load->view('login', $response);
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