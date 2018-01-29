<?php
defined('BASEPATH') OR exit('No direct script access allowed');

require(APPPATH . '/libraries/REST_Controller.php');

class Contacts extends REST_Controller
{
	public $data_token;

	function __construct()
	{
		parent::__construct();

		$this->load->model('v2/contacts_model');
		$this->load->model('v2/messages_model');

		$user_token = $this->input->server("HTTP_X_API_TOKEN");
		$this->data_token = $this->auth_model->check_token($user_token)->row();
	}

	/**
	 * @api {get} /contacts/index/:id_user Get data contact
	 * @apiSampleRequest http://api.iamchill.co/v2/contacts/index/
	 * @apiVersion 0.2.0
	 * @apiName Get data contact
	 * @apiGroup Contacts
	 *
	 * @apiExample {curl} Example usage:
	 *    curl -i http://api.iamchill.co/v2/contacts/index/
	 *
	 * @apiHeaderExample {json} User Token:
	 *    {
	 *        "X-API-TOKEN": "10c5998bf91a506f1bcddd"
	 *    }
	 *
	 * @apiHeaderExample {json} App Token:
	 *    {
	 *        "X-API-KEY": "76eb29d3ca26fe805545812850e6d75af933214a"
	 *    }
	 *
	 * @apiHeader {String} X-API-TOKEN User key
	 * @apiHeader {String} X-API-KEY API key
	 *
	 * @apiParam {Number} id_user Users unique ID.
	 *
	 * @apiSuccess {String} status String status.
	 * @apiSuccess {Object} response Object data.
	 * @apiSuccess {Number} response.id_user Users unique ID.
	 * @apiSuccess {Number} response.id_contact Users contact unique ID.
	 * @apiSuccess {Number} response.id_twitter Twitter users unique ID.
	 * @apiSuccess {String} response.name User name.
	 * @apiSuccess {String} response.twitter_name Twitter name.
	 * @apiSuccess {String} response.email User email.
	 * @apiSuccess {String} response.login User login.
	 * @apiSuccess {String} response.date_reg Date reg.
	 * @apiSuccess {String} response.content Content message.
	 * @apiSuccess {String} response.type Type message.
	 * @apiSuccess {Number=0,1} response.read Reading status.
	 * @apiSuccess {String} response.date_created Date created.
	 * @apiSuccessExample {json} response.Success-Response:
	 *    HTTP/1.1 200 OK
	 *    {
	 *        "status": "success",
	 *        "response":
	 *        {
	 *            "id_user":
	 *            "id_contact":
	 *            "id_twitter":
	 *            "name":
	 *            "twitter_name":
	 *            "email":
	 *            "login":
	 *            "date_reg":
	 *            "content":
	 *            "type":
	 *            "read":
	 *            "date_created":
	 *        }
	 *    }
	 *
	 * @apiError NoContacts No contacts.
	 * @apiErrorExample Error-Response:
	 *    HTTP/1.1
	 *    {
	 *        "status": "failed",
	 *        "response": "NoContacts"
	 *    }
	 *
	 * @apiError UserNotExist User with the token does not exist.
	 * @apiErrorExample Error-Response:
	 *    HTTP/1.1
	 *    {
	 *        "status": "failed",
	 *        "response": "UserNotExist"
	 *    }
	 *
	 * @apiError DataNotValid Data were not obtained or is not valid.
	 * @apiErrorExample Error-Response:
	 *    HTTP/1.1
	 *    {
	 *        "status": "failed",
	 *        "response": "DataNotValid"
	 *    }
	 */

	function index_get()
	{
		$id_user = $this->get("id_user");

		if (!empty($id_user)) {
			if ($id_user == $this->data_token->id_user) {
				$data_contacts = $this->contacts_model->get_contacts($id_user);

				if ($data_contacts->num_rows() > 0) {
					$data_response = array(
						'status' => 'success',
						'response' => $data_contacts->result()
					);
					$this->response($data_response);
				} else {
					$data_response = array(
						'status' => 'failed',
						'error' => 'No contacts.'
					);
					$this->response($data_response);
				}
			} else {
				$data_response = array(
					'status' => 'failed',
					'error' => 'User with the token does not exist.'
				);
				$this->response($data_response);
			}
		} else {
			$data_response = array(
				'status' => 'failed',
				'error' => 'Data were not obtained or is not valid.'
			);
			$this->response($data_response);
		}
	}

	/**
	 * @api {post} /contacts/index Add contact
	 * @apiSampleRequest http://api.iamchill.co/v2/contacts/index
	 * @apiVersion 0.2.0
	 * @apiName Add contact
	 * @apiGroup Contacts
	 *
	 * @apiExample {curl} Example usage:
	 *    curl -i http://api.iamchill.co/v2/contacts/index
	 *
	 * @apiHeaderExample {json} User Token:
	 *    {
	 *        "X-API-TOKEN": "10c5998bf91a506f1bcddd"
	 *    }
	 *
	 * @apiHeaderExample {json} App Token:
	 *    {
	 *        "X-API-KEY": "76eb29d3ca26fe805545812850e6d75af933214a"
	 *    }
	 *
	 * @apiHeader {String} X-API-TOKEN User key
	 * @apiHeader {String} X-API-KEY API key
	 *
	 * @apiParam {Number} id_user Users unique ID.
	 * @apiParam {Number} id_contact Users contact unique ID.
	 *
	 * @apiSuccess {String} status String status.
	 * @apiSuccess {Object} response Object data.
	 * @apiSuccess {Number} response.id_user Users unique ID.
	 * @apiSuccessExample {json} Success-Response:
	 *    HTTP/1.1 200 OK
	 *    {
	 *        "status": "success",
	 *        "response":
	 *        {
	 *            "id_user":
	 *            "id_contact":
	 *        }
	 *    }
	 *
	 * @apiError ErrorAddingContact Error adding contact.
	 * @apiErrorExample Error-Response:
	 *    HTTP/1.1
	 *    {
	 *        "status": "failed",
	 *        "response": "ErrorAddingContact"
	 *    }
	 *
	 * @apiError UserExist User exist.
	 * @apiErrorExample Error-Response:
	 *    HTTP/1.1
	 *    {
	 *        "status": "failed",
	 *        "response": "UserExist"
	 *    }
	 *
	 * @apiError UserNotExist User with the token does not exist.
	 * @apiErrorExample Error-Response:
	 *    HTTP/1.1
	 *    {
	 *        "status": "failed",
	 *        "response": "UserNotExist"
	 *    }
	 *
	 * @apiError DataNotValid Data were not obtained or is not valid.
	 * @apiErrorExample Error-Response:
	 *    HTTP/1.1
	 *    {
	 *        "status": "failed",
	 *        "response": "DataNotValid"
	 *    }
	 */
	function index_post()
	{
		$id_user = $this->post("id_user");
		$id_contact = $this->post("id_contact");

		if (!empty($id_user) && !empty($id_contact)) {
			if ($id_user == $this->data_token->id_user) {
				if ($this->contacts_model->check_contact($id_user, $id_contact)) {
					$data_contacts = array(
						'id_user' => $id_user,
						'id_contact' => $id_contact
					);
					$data_contacts_re = array(
						'id_user' => $id_contact,
						'id_contact' => $id_user
					);

					$data_message = array(
						'id_sender' => $id_user,
						'id_recipient' => $id_contact,
						'content' => 'logo',
						'type' => 'icon',
						'text' => "Hi!"
					);

					$data_message_re = array(
						'id_sender' => $id_contact,
						'id_recipient' => $id_user,
						'content' => 'logo',
						'type' => 'icon',
						'text' => "Hi!"
					);

					$this->messages_model->add_message($data_message);
					$this->messages_model->add_message($data_message_re);

					if ($this->contacts_model->add_contact($data_contacts) && $this->contacts_model->add_contact($data_contacts_re)) {
						$data_response = array(
							'status' => 'success',
							'response' => $data_contacts
						);
						$this->response($data_response);
					} else {
						$data_response = array(
							'status' => 'failed',
							'response' => 'Error adding contact.'
						);
						$this->response($data_response);
					}
				} else {
					$data_response = array(
						'status' => 'failed',
						'response' => 'User exist.'
					);
					$this->response($data_response);
				}
			} else {
				$data_response = array(
					'status' => 'failed',
					'response' => 'User with the token does not exist.'
				);
				$this->response($data_response);
			}
		} else {
			$data_response = array(
				'status' => 'failed',
				'response' => 'Data were not obtained or is not valid.'
			);
			$this->response($data_response);
		}
	}

	/**
	 * @api {delete} /contacts/index Delete contact
	 * @apiSampleRequest http://api.iamchill.co/v2/contacts/index
	 * @apiVersion 0.2.0
	 * @apiName Delete contact
	 * @apiGroup Contacts
	 *
	 * @apiExample {curl} Example usage:
	 *    curl -i http://api.iamchill.co/v2/contacts/index
	 *
	 * @apiHeaderExample {json} User Token:
	 *    {
	 *        "X-API-TOKEN": "10c5998bf91a506f1bcddd"
	 *    }
	 *
	 * @apiHeaderExample {json} App Token:
	 *    {
	 *        "X-API-KEY": "76eb29d3ca26fe805545812850e6d75af933214a"
	 *    }
	 *
	 * @apiHeader {String} X-API-TOKEN User key
	 * @apiHeader {String} X-API-KEY API key
	 *
	 *
	 * @apiParam {Number} id_user Users unique ID.
	 * @apiParam {Number} id_contact Users contact unique ID.
	 *
	 * @apiSuccess {String} status String status.
	 * @apiSuccess {Object} response Object data.
	 * @apiSuccess {Number} response.id_user Users unique ID.
	 * @apiSuccess {Number} response.id_contact Users contact unique ID.
	 * @apiSuccessExample {json} Success-Response:
	 *    HTTP/1.1 200 OK
	 *    {
	 *        "status": "success",
	 *        "response":
	 *        {
	 *            "id_user":
	 *            "id_contact":
	 *        }
	 *    }
	 *
	 * @apiError ErrorDeleteContact Error delete contact.
	 * @apiErrorExample Error-Response:
	 *    HTTP/1.1
	 *    {
	 *        "status": "failed",
	 *        "response": "ErrorDeleteContact"
	 *    }
	 *
	 * @apiError UserNotExist User with the token does not exist.
	 * @apiErrorExample Error-Response:
	 *    HTTP/1.1
	 *    {
	 *        "status": "failed",
	 *        "response": "UserNotExist"
	 *    }
	 *
	 * @apiError DataNotValid Data were not obtained or is not valid.
	 * @apiErrorExample Error-Response:
	 *    HTTP/1.1
	 *    {
	 *        "status": "failed",
	 *        "response": "DataNotValid"
	 *    }
	 */
	function delete_post()
	{
		$id_user = $this->post("id_user");
		$id_contact = $this->post("id_contact");

		if (!empty($id_user) && !empty($id_contact)) {
			if ($id_user == $this->data_token->id_user) {
				$data_contacts = array(
					'id_user' => $id_user,
					'id_contact' => $id_contact
				);
				if ($this->contacts_model->delete_contact($id_user, $id_contact) && $this->contacts_model->delete_contact($id_contact, $id_user)) {
					$data_response = array(
						'status' => 'success',
						'response' => $data_contacts
					);
					$this->response($data_response);
				} else {
					$data_response = array(
						'status' => 'failed',
						'response' => 'Error delete contact.'
					);
					$this->response($data_response);
				}
			} else {
				$data_response = array(
					'status' => 'failed',
					'response' => 'User with the token does not exist.'
				);
				$this->response($data_response);
			}
		} else {
			$data_response = array(
				'status' => 'failed',
				'response' => 'Data were not obtained or is not valid.'
			);
			$this->response($data_response);
		}
	}
}

/* End of file Contacts.php */
/* Location: ./application/controllers/v2/Contacts.php */