<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Users_model extends CI_Model
{
	/**
	 * @param $id_user
	 * @return mixed
	 */
	function get_data_user($id_user)
	{
		$this->db->where('id', $id_user);
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
	function add_user($data)
	{
		if ($this->db->insert('users_app', $data)) {
			return TRUE;
		} else {
			return FALSE;
		}
	}

	/**
	 * @param $data
	 * @param $id
	 * @return bool
	 */
	function update_user($data, $id)
	{
		$this->db->where('id', $id);
		if ($this->db->update('users_app', $data)) {
			return TRUE;
		} else {
			return FALSE;
		}
	}

	/**
	 * @param $id
	 * @return bool
	 */
	function delete_user($id)
	{
		$this->db->where('id', $id);
		if ($this->db->delete('users_app')) {
			return TRUE;
		} else {
			return FALSE;
		}
	}

	/**
	 * @param $login
	 * @return bool
	 */
	function check_reg($login)
	{
		$this->db->or_where('login', $login);
		$data = $this->db->get('users_app');
		if ($data->num_rows() == 0) {
			return TRUE;
		} else {
			return FALSE;
		}
	}
}

/* End of file Users_model.php */
/* Location: ./application/models/v2/Users_model.php */