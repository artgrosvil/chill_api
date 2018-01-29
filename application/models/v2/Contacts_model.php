<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Contacts_model extends CI_Model
{
	/**
	 * @param $id_user
	 * @return bool
	 */
	function get_contacts($id_user)
	{
		$data = $this->db->query("SELECT
			a.id_contact,
			b.name, b.twitter_name, b.email, b.login,
			c.id_sender, c.id_recipient, c.content, c.type, c.read, c.text,
			d.size42, d.size66
			FROM contacts AS a

			LEFT JOIN users_app AS b ON b.id = a.id_contact

			LEFT JOIN messages AS c ON c.id = (SELECT MAX(d.id) FROM messages AS d WHERE (d.id_recipient = a.id_user AND d.id_sender = a.id_contact))

			LEFT JOIN icons AS d ON (d.name = c.content OR d.name = c.type)
			WHERE a.id_user = $id_user AND a.type_contact = 0 ORDER BY c.id DESC, a.id DESC");
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
	function add_contact($data)
	{
		if ($this->db->insert('contacts', $data)) {
			return TRUE;
		} else {
			return FALSE;
		}
	}

	/**
	 * @param $id_user
	 * @param $id_contact
	 * @return bool
	 */
	function delete_contact($id_user, $id_contact)
	{
		$this->db->where('id_user', $id_user);
		$this->db->where('id_contact', $id_contact);
		if ($this->db->delete('contacts')) {
			return TRUE;
		} else {
			return FALSE;
		}
	}

	/**
	 * @param $id_user
	 * @param $id_contact
	 * @return bool
	 */
	function check_contact($id_user, $id_contact)
	{
		$this->db->where('id_user', $id_user);
		$this->db->where('id_contact', $id_contact);
		$data = $this->db->get('contacts');
		if ($data->num_rows() == 0) {
			return TRUE;
		} else {
			return FALSE;
		}
	}
}

/* End of file Contacts_model.php */
/* Location: ./application/models/v2/Contacts_model.php */