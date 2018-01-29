<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Apps_model extends CI_Model
{

	/**
	 * @param $id_app
	 * @return bool
	 */
	function get_data_app($id_app)
	{
		$this->db->where('id', $id_app);
		$this->db->select('id, name, type, id_category, description, status, language, loop');
		$data = $this->db->get('apps');
		if ($data) {
			return $data;
		} else {
			return FALSE;
		}
	}
}

/* End of file Users_model.php */
/* Location: ./application/models/v3/Apps_model.php */