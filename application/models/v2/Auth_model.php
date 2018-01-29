<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Auth_model extends CI_Model
{
	/**
	 * @param $login
	 * @return bool
	 */
	function get_data_user($login)
	{
		$this->db->where('login', $login);
		$data = $this->db->get('users_app');
		if ($data) {
			return $data;
		} else {
			return FALSE;
		}
	}

	/**
	 * @param $data
	 * @return bool
	 */
	function add_token($data)
	{
		if ($this->db->insert('tokens', $data)) {
			return TRUE;
		} else {
			return FALSE;
		}
	}

	/**
	 * @param $user_token
	 * @return bool
	 */
	function check_token($user_token)
	{
		$this->db->where('token', $user_token);
		$data = $this->db->get('tokens');
		if ($data) {
			return $data;
		} else {
			return FALSE;
		}
	}

	/**
	 * @param $id_user
	 * @return bool
	 */
	function get_token($id_user)
	{
		$this->db->where('id_user', $id_user);
		$data = $this->db->get('tokens');
		if ($data) {
			return $data;
		} else {
			return FALSE;
		}
	}
}

/* End of file Auth_model.php */
/* Location: ./application/models/v2/Auth_model.php */