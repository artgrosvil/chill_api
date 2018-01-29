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
		$data = $this->db->get('users_app');
		if ($data) {
			return $data;
		} else {
			return FALSE;
		}
	}
}

/* End of file Search_model.php */
/* Location: ./application/models/v2/Search_model.php */