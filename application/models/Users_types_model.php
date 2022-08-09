<?php

	class Users_types_model extends CI_Model{

		public function create($data){
			$this->db->trans_start();
	          $this->db->insert("users_types", $data);

	        $result = array('status' => '', 'message' => '', 'last_insert_id' => $this->db->insert_id());
	        $message = '';
	        $error = $this->db->error();

	        if($error['code'] > 0){
	        	$message = $error['message'];
	        }

	        $this->db->trans_complete();
	        if($this->db->trans_status() === false){
	          $this->db->trans_rollback();
	        } else {
	          $this->db->trans_commit();
	        }

	        $result['status'] = $this->db->trans_status();
	        $result['message'] = $message;

	        return $result;
		}

		public function update($id, $data, $condition){
			$this->db->trans_start();
	          $this->db->update("users_types", $data, $condition);

	        $result = array('status' => '', 'message' => '', 'last_updated_id' => $id);
	        $message = '';
	        $error = $this->db->error();

	        if($error['code'] > 0){
	        	$message = $error['message'];
	        }

	        $this->db->trans_complete();
	        if($this->db->trans_status() === false){
	          $this->db->trans_rollback();
	        } else {
	          $this->db->trans_commit();
	        }

	        $result['status'] = $this->db->trans_status();
	        $result['message'] = $message;

	        return $result;
		}

		public function delete($id, $condition){
			$this->db->trans_start();
	          $this->db->delete("users_types", $condition);

	        $result = array('status' => '', 'message' => '', 'last_deleted_id' => $id);
	        $message = '';
	        $error = $this->db->error();

	        if($error['code'] > 0){
	        	$message = $error['message'];
	        }

	        if($error['code'] == 1451){
	        	$message = 'Cannot delete record. Record is currently in use';
	        }

	        $this->db->trans_complete();
	        if($this->db->trans_status() === false){
	          $this->db->trans_rollback();
	        } else {
	          $this->db->trans_commit();
	        }

	        $result['status'] = $this->db->trans_status();
	        $result['message'] = $message;

	        return $result;
		}

		public function get_users_types(){
			return $this->db->query(
				'select * from users_types order by id'
			);		
		}
	}

?>