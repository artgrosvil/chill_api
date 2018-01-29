<?php
defined('BASEPATH') OR exit('No direct script access allowed');

require(APPPATH . '/libraries/REST_Controller.php');

class Contacts extends REST_Controller
{
	private $data_token;

	function __construct()
	{
		parent::__construct();

		$this->load->model('v3/contacts_model');
		$this->load->model('v3/messages_model');

		$user_token = $this->input->server("HTTP_X_API_TOKEN");
		$this->data_token = $this->auth_model->check_token($user_token)->row();
	}

	/**
	 * @api {get} /contacts/index/:id_user Get data contact
	 * @apiSampleRequest http://api.iamchill.co/v3/contacts/index/
	 * @apiVersion 0.3.0
	 * @apiName Get data contact
	 * @apiGroup Contacts
	 *
	 * @apiExample {curl} Example usage:
	 *    curl -i http://api.iamchill.co/v3/contacts/index/
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
	 * @apiSuccess {Number} response.id_contact Users contact unique ID.
	 * @apiSuccess {String} response.name User name.
	 * @apiSuccess {String} response.email Email user.
	 * @apiSuccess {String} response.login Login user.
	 * @apiSuccess {String} response.id_sender Unique ID sender user message.
	 * @apiSuccess {String} response.id_recipient Unique ID recipient user message.
	 * @apiSuccess {String} response.content Content message.
	 * @apiSuccess {String} response.type Type message.
	 * @apiSuccess {Number=0,1} response.read Reading status.
	 * @apiSuccess {String} response.size42 Path to icons 42px.
	 * @apiSuccess {String} response.size66 Path to icons 66px.
	 * @apiSuccessExample {json} response.Success-Response:
	 *    HTTP/1.1 200 OK
	 *    {
	 *        "status": "success",
	 *        "response":
	 *        {
	 *            "id_contact":
	 *            "name":
	 *            "email":
	 *            "login":
	 *            "id_sender":
	 *            "id_recipient":
	 *            "content":
	 *            "type":
	 *            "read":
	 *            "size42":
	 *            "size66":
	 *        }
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
				$data_contacts_user = $this->contacts_model->get_contacts_users($id_user)->result();
				$data_contacts_apps = $this->contacts_model->get_contacts_apps($id_user)->result();

				$data_contacts = array();

				foreach ($data_contacts_user as $item_data_contacts_user) {
					array_push($data_contacts, $item_data_contacts_user);
				}

				foreach ($data_contacts_apps as $item_data_contacts_apps) {
					array_push($data_contacts, $item_data_contacts_apps);
				}

				usort($data_contacts, function($a, $b){
					return -($a->id - $b->id);
				});

				$data_response = array(
					'status' => 'success',
					'response' => $data_contacts
				);
				$this->response($data_response);
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
	 * @apiSampleRequest http://api.iamchill.co/v3/contacts/index
	 * @apiVersion 0.3.0
	 * @apiName Add contact
	 * @apiGroup Contacts
	 *
	 * @apiExample {curl} Example usage:
	 *    curl -i http://api.iamchill.co/v3/contacts/index
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
	 * @apiParam {Number=0,1} type_contact Type contact, users = 0 or app = 1.
	 *
	 * @apiSuccess {String} status String status.
	 * @apiSuccess {Object} response Object data.
	 * @apiSuccess {Number} response.id_user Users unique ID.
	 * @apiSuccess {Number=0,1} type_contact Type contact, users = 0 or app = 1.
	 * @apiSuccessExample {json} Success-Response:
	 *    HTTP/1.1 200 OK
	 *    {
	 *        "status": "success",
	 *        "response":
	 *        {
	 *            "id_user":
	 *            "id_contact":
	 *            "type_contact":
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
	 * @apiError UserAppExist User or app exist.
	 * @apiErrorExample Error-Response:
	 *    HTTP/1.1
	 *    {
	 *        "status": "failed",
	 *        "response": "UserAppExist"
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
		$type_contact = $this->post("type_contact");

		if (!empty($id_user) && !empty($id_contact)) {
			if ($id_user == $this->data_token->id_user) {
				if ($this->contacts_model->check_contact($id_user, $id_contact, $type_contact)) {
					$data_contacts = array(
						'id_user' => $id_user,
						'id_contact' => $id_contact,
						'type_contact' => $type_contact
					);
					$data_contacts_re = array(
						'id_user' => $id_contact,
						'id_contact' => $id_user,
						'type_contact' => $type_contact
					);

					$data_message = array(
						'id_sender' => $id_user,
						'id_recipient' => $id_contact,
						'content' => 'logo',
						'type' => 'icon',
						'text' => "Hi!",
						'type_message' => 0
					);

					$data_message_re = array(
						'id_sender' => $id_contact,
						'id_recipient' => $id_user,
						'content' => 'logo',
						'type' => 'icon',
						'text' => "Hi!",
						'type_message' => 0
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
						'response' => 'User or app exist.'
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
	 * @apiSampleRequest http://api.iamchill.co/v3/contacts/index
	 * @apiVersion 0.3.0
	 * @apiName Delete contact
	 * @apiGroup Contacts
	 *
	 * @apiExample {curl} Example usage:
	 *    curl -i http://api.iamchill.co/v3/contacts/index
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
	 * @apiParam {Number=0,1} type_contact Type contact, users = 0 or app = 1.
	 *
	 * @apiSuccess {String} status String status.
	 * @apiSuccess {Object} response Object data.
	 * @apiSuccess {Number} response.id_user Users unique ID.
	 * @apiSuccess {Number} response.id_contact Users contact unique ID.
	 * @apiSuccess {Number=0,1} response.type_contact Type contact, users = 0 or app = 1.
	 * @apiSuccessExample {json} Success-Response:
	 *    HTTP/1.1 200 OK
	 *    {
	 *        "status": "success",
	 *        "response":
	 *        {
	 *            "id_user":
	 *            "id_contact":
	 *            "type_contact":
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
		$type_contact = $this->post("type_contact");

		if (!empty($id_user) && !empty($id_contact)) {
			if ($id_user == $this->data_token->id_user) {
				$data_contacts = array(
					'id_user' => $id_user,
					'id_contact' => $id_contact
				);
				if ($this->contacts_model->delete_contact($id_user, $id_contact, $type_contact) && $this->contacts_model->delete_contact($id_contact, $id_user, $type_contact)) {
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
/* Location: ./application/controllers/v3/Contacts.php */