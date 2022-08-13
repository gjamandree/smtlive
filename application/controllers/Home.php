<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Home extends CI_Controller {

	public function index()
	{
		if(!$this->session->has_userdata('current_user')){
			$this->load->view('login');
		} else {
			$this->load->view('index');
		}
	}

	public function test_post(){
		$param = $this->input->post('param');

		if(!isset($param)){
			$param = 'not set';
		}

		$response = array('param' => $param);

		echo json_encode($response);
	}

	public function test_login(){
		$user = $this->globallib->authenticate('');
	}
	
}
