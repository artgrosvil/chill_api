<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Facebook_model extends CI_Model
{
	/**
	 * @param $id_user
	 * @param $data_id_contacts_facebook
	 * @return array|bool
	 */
	function get_contacts($id_user, $data_id_contacts_facebook)
	{
		$data_chill = array();
		$data_facebook = array();
		for ($i = 0; $i < count($data_id_contacts_facebook); $i++) {
			$this->db->where('id_facebook', $data_id_contacts_facebook[$i]);
			$this->db->select('id, id_twitter, id_facebook, name, email, login');
			$data_tmp = $this->db->get('users_app');
			if ($data_tmp->num_rows() > 0) {
				$this->db->where('id_user', $id_user);
				$this->db->where('id_contact', $data_tmp->row()->id);
				$data_contact = $this->db->get('contacts');
				if ($data_contact->num_rows == 0) {
					$data_chill[] = $data_tmp->result();
				}
			} else {
				$data_facebook[] = $data_id_contacts_facebook[$i];
			}
		}

		$data = array(
			'chill' => $data_chill,
			'facebook' => $data_facebook
		);
		if ($data) {
			return $data;
		} else {
			return FALSE;
		}
	}
}

/* End of file Facebook_model.php */
/* Location: ./application/models/v3/Facebook_model.php */