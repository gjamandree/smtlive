<?php

	class Users_model extends CI_Model {
		
		public function create($data){
			$this->db->trans_start();
	          $this->db->insert("users", $data);

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
	          $this->db->update("users", $data, $condition);

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
	          $this->db->delete("users", $condition);

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

		public function get_users(){
			return $this->db->query(
				'select '.

				'* '.

				'from users '.

				'order by id'
			);
		}

		public function get_user_by_id($user_id){
			return $this->db->query('select * from users where id = ' . $user_id);
		}

		public function get_user_by_credentials($username, $password){
			return $this->db->query('select * from users where username = "'. $username .'" and password = "'. $password .'"');	
		}

		public function get_user_by_fieldname($fieldname, $value){
			return $this->db->query('select * from users where '. $fieldname . ' = "' . $value . '" limit 1');	
		}

		public function get_user_by_credentials_and_permission($user_id, $access_name){
			$sql = 'select '.

			       'a.*, '.
			       'b.* '.

			       'from users a '.

			       'left join users_permissions b on b.user_type_id = a.user_type_id '.

			       'where a.id = ' . $user_id . ' ' .
			         'and b.access_name = "'. $access_name .'" '.
			         'and b.access_value = 1';


			return $this->db->query($sql);
		}

		public function email_exists($email){
			$data = $this->db->query(
				'select '.

				'count(*) as `email_exists` '.

				'from users '.

				'where email = "'. $email .'"')->row();

			return ($data->email_exists == 1);
		}

		public function username_exists($username){
			$data = $this->db->query(
				'select '.

				'count(*) as `username_exists` '.

				'from users '.

				'where username = "'. $username .'"')->row();

			return ($data->username_exists >= 1);
		}
	}

?>