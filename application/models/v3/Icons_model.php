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

	function get_onboarding_icons($list_id_icons)
	{
		$this->db->or_where_in('id', $list_id_icons);
		$data = $this->db->get('icons');
		if ($data) {
			return $data;
		} else {
			return FALSE;
		}
	}

	function get_without_onboarding_icons($list_id_icons)
	{
		$this->db->where_not_in('id', $list_id_icons);
		$this->db->where('pack', 'main');
		$data = $this->db->get('icons');
		if ($data) {
			return $data;
		} else {
			return FALSE;
		}
	}

	function get_user_icons($id_user)
	{
		$this->db->select('*');
		$this->db->where('id_user', $id_user);
		$this->db->from('icons_users');
		$this->db->join('icons', 'icons.id = icons_users.id_icon');
		$data = $this->db->get();
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
		$this->db->or_where('name', 'clock');
		$this->db->or_where('name', 'beer');
		$this->db->or_where('name', 'coffee');
		$this->db->or_where('name', 'question');
		$this->db->or_where('name', 'logo');
		$this->db->or_where('name', 'rocket');
		$data = $this->db->get('icons');
		if ($data) {
			return $data;
		} else {
			return FALSE;
		}
	}

	/**
	 * @param $icons
	 * @return bool
	 */
	function add_icons($icons)
	{
		if ($this->db->insert('icons_users', $icons)) {
			return TRUE;
		} else {
			return FALSE;
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
/* Location: ./application/models/v3/Icons_model.php */