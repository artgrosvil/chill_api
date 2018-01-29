<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Promocodes_model extends CI_Model
{
	/**
	 * @param $data
	 * @return bool
	 */
	function add_code($data)
	{
		if ($this->db->insert('promocodes', $data)) {
			return TRUE;
		} else {
			return FALSE;
		}
	}

	/**
	 * @param $promocode
	 * @return bool
	 */
	function check_promocode($promocode)
	{
		$this->db->where('code', $promocode);
		$this->db->where('status', 0);
		$data = $this->db->get('promocodes');
		if ($data->num_rows() > 0) {
			return TRUE;
		} else {
			return FALSE;
		}
	}

	function get_promocode_data($promocode)
	{
		$this->db->where('code', $promocode);
		$data = $this->db->get('promocodes');
		if ($data) {
			return $data;
		} else {
			return FALSE;
		}
	}
	function update_code($promocode, $data)
	{
		$this->db->where('code', $promocode);
		if ($this->db->update('promocodes', $data)) {
			return TRUE;
		} else {
			return FALSE;
		}
	}
}

/* End of file Users_model.php */
/* Location: ./application/models/v2/Promocodes_model.php */