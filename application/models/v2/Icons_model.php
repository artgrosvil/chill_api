<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Icons_model extends CI_Model
{
	/**
	 * @return bool
	 */
	function get_all_icons()
	{
		$data = $this->db->get('icons');
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
	function get_icon($name)
	{
		$this->db->where('name', $name);
		$data = $this->db->get('icons');
		if ($data) {
			return $data;
		} else {
			return FALSE;
		}
	}

	/**
	 * @param $name_pack
	 * @return bool
	 */
	function get_pack_icons($name_pack)
	{
		$this->db->where('pack', $name_pack);
		$data = $this->db->get('icons');
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
	function get_user_icons($id_user)
	{
		$this->db->where('id_user', $id_user);
		$data = $this->db->get('icons_users');
		if ($data) {
			return $data;
		} else {
			return FALSE;
		}
	}

	/**
	 * @return bool
	 */
	function get_def_icons()
	{
		$this->db->or_where('name', 'Clock');
		$this->db->or_where('name', 'Beer');
		$this->db->or_where('name', 'Coffee');
		$this->db->or_where('name', 'Question');
		$this->db->or_where('name', 'Chill');
		$this->db->or_where('name', 'Rocket');
		$data = $this->db->get('icons');
		if ($data) {
			return $data;
		} else {
			return FALSE;
		}
	}

	/**
	 * @param $data_icons
	 */
	function add_icons($data_icons)
	{
		foreach ($data_icons as $item_icon) {
			$this->db->insert('icons_users', $item_icon);
		}
	}

	/**
	 * @param $id_user
	 * @return bool
	 */
	function remove_icons($id_user)
	{
		$this->db->where('id_user', $id_user);
		if ($this->db->delete('icons_users')) {
			return TRUE;
		} else {
			return FALSE;
		}
	}
}

/* End of file Icons_model.php */
/* Location: ./application/models/v2/Icons_model.php */