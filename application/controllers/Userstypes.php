<?php

	class Userstypes extends CI_Controller{

		public function __construct(){
			parent::__construct();

			$this->load->model(array('users_types_model'));
		}

		public function index(){

		}

		public function get_users_types(){
			$response = array('type' => 'success', 
							  'errormessage' => '',
							  'user_types' => array());

			$user = $this->globallib->authenticate();
			if($user->num_rows() == 1){
				$response['user_types'] = $this->users_types_model->get_users_types()->result_array();
			} else {
				$response['type'] = 'info';
				$response['errormessage'] = 'User does not exists';
			}

			echo json_encode($response);
		}

		public function create_usertype(){
			$response = array('type' => 'success', 
							  'errormessage' => '',
							  'id' => '');	

			$user = $this->globallib->authenticate();
			if($user->num_rows() == 1){
				$client_current_datetime = $this->input->post('client_current_datetime');
				
				$user = $user->row();

				$data = array(
					'user_type_name' => $this->input->post('user_type_name'),
					'date_created' => $client_current_datetime
				);

				$usertype = $this->users_types_model->create($data);
				if(!$usertype['status']){
					$response['type'] = 'error';
					$response['errormessage'] = $usertype['message'];
				} else {
					$response['id'] = $usertype['last_insert_id'];
				}
			} else {
				$response['type'] = 'info';
				$response['errormessage'] = 'User does not exists';	
			}

			echo json_encode($response);
		}

		public function update_usertype(){
			$response = array('type' => 'success', 
							  'errormessage' => '');	

			$user = $this->globallib->authenticate();
			if($user->num_rows() == 1){
				$client_current_datetime = $this->input->post('client_current_datetime');
				$user_type_id = $this->input->post('user_type_id');

				$user = $user->row();
				$data = array(
					'user_type_name' => $this->input->post('user_type_name'),
					'date_modified' => $client_current_datetime
				);
				
				$condition = array(
					'id' => $user_type_id
				);

				$usertype = $this->users_types_model->update($user_type_id, $data, $condition);
				if(!$usertype['status']){
					$response['type'] = 'error';
					$response['errormessage'] = $usertype['message'];
				}
			} else {
				$response['type'] = 'info';
				$response['errormessage'] = 'User does not exists';	
			}

			echo json_encode($response);	
		}

		public function delete_usertype(){
			$response = array('type' => 'success', 
							  'errormessage' => '');	

			$user = $this->globallib->authenticate();
			if($user->num_rows() == 1){
				$user_type_id = $this->input->post('user_type_id');

				$user = $user->row();
				$condition = array(
					'id' => $user_type_id
				);

				$usertype = $this->users_types_model->delete($user_type_id, $condition);
				if(!$usertype['status']){
					$response['type'] = 'error';
					$response['errormessage'] = $usertype['message'];
				}
			} else {
				$response['type'] = 'info';
				$response['errormessage'] = 'User does not exists';	
			}

			echo json_encode($response);	
		}
	}

?>