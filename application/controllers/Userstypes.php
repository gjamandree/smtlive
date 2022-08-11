<?php

	class Userstypes extends CI_Controller{

		public function __construct(){
			parent::__construct();

			$this->load->model(array('users_types_model'));
		}

		public function index(){

		}

		public function create_usertype(){
			if($GLOBALS['ready']){
				$client_current_datetime = $this->input->post('client_current_datetime');
				
				$data = array(
					'user_type_name' => $this->input->post('user_type_name'),
					'date_created' => $client_current_datetime
				);

				$usertype = $this->users_types_model->create($data);
				if(!$usertype['status']){
					$response['type'] = 'error';
					$response['errormessage'] = $usertype['message'];
				}
			}
		}

		public function update_usertype(){
			if($GLOBALS['ready']){
				$client_current_datetime = $this->input->post('client_current_datetime');
				$user_type_id = $this->input->post('user_type_id');

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
			}	
		}

		public function delete_usertype(){
			if($GLOBALS['ready']){
				$usertype = $this->users_types_model->delete($user_type_id, $condition);
				if(!$usertype['status']){
					$response['type'] = 'error';
					$response['errormessage'] = $usertype['message'];
				}
			}
		}
	}

?>