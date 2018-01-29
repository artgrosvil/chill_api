<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Messages_model extends CI_Model
{
	/**
	 * @param $id_user
	 * @param $id_contact
	 * @return bool
	 */
	function get_messages($id_user, $id_contact)
	{
		$data = $this->db->query("SELECT
			a.*,
			b.name, b.description, b.pack, b.size42, b.size66
			FROM messages AS a

			LEFT JOIN icons AS b ON (b.name = a.content OR b.name = a.type)

			WHERE (a.id_sender = $id_contact AND a.id_recipient = $id_user) ORDER BY a.id DESC LIMIT 5");
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
	 * @return bool
	 */
	function update_messages($id_message)
	{
		$this->db->where('id', $id_message);
		if ($this->db->update('messages', array('read' => '1'))) {
			return TRUE;
		} else {
			return FALSE;
		}
	}
}

/* End of file Messages_model.php */
/* Location: ./application/model/v2/Messages_model.php */