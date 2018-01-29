<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Messages_model extends CI_Model
{

	/**
	 * @param $id_user
	 * @param $id_contact
	 * @param $type_message
	 * @return bool
	 */
	function get_messages($id_user, $id_contact, $type_message)
	{
		$data = $this->db->query("SELECT
			a.*,
			b.name, b.pack, b.size42, b.size66
			FROM messages AS a

			LEFT JOIN icons AS b ON (b.name = a.content OR b.name = a.type)

			WHERE (a.id_sender = $id_contact AND a.id_recipient = $id_user AND a.type_message = $type_message) ORDER BY a.id DESC LIMIT 5");
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
	function add_message($data)
	{
		if ($this->db->insert('messages', $data)) {
			return TRUE;
		} else {
			return FALSE;
		}
	}


	/**
	 * @param $id_message
	 * @param $data
	 * @return bool
	 */
	function update_messages($id_message, $data)
	{
		$this->db->where('id', $id_message);
		if ($this->db->update('messages', $data)) {
			return TRUE;
		} else {
			return FALSE;
		}
	}
}

/* End of file Messages_model.php */
/* Location: ./application/model/v3/Messages_model.php */