<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Search_model extends CI_Model
{
	/**
	 * @param $login
	 * @param $login_user
	 * @return bool
	 */
	function search_user($login, $login_user)
	{
		$this->db->like('login', $login);
		$this->db->not_like('login', $login_user);
		$this->db->not_like('login', 'howtochill');
		$this->db->select('id, id_twitter, id_facebook, name, email, login');
		$data = $this->db->get('users_app');
		if ($data) {
			return $data;
		} else {
			return FALSE;
		}
	}

	/**
	 * @param $name
	 * @return bool
	 */
	function search_apps($name)
	{
		$this->db->like('name', $name);
		$this->db->select('id, name');
		$data = $this->db->get('apps');
		if ($data) {
			return $data;
		} else {
			return FALSE;
		}
	}
}

/* End of file Search_model.php */
/* Location: ./application/models/v3/Search_model.php */