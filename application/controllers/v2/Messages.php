<?php
defined('BASEPATH') OR exit('No direct script access allowed');

require(APPPATH . '/libraries/REST_Controller.php');

class Messages extends REST_Controller
{
	public $data_token;

	function __construct()
	{
		parent::__construct();

		$this->load->model('v2/messages_model');

		$user_token = $this->input->server("HTTP_X_API_TOKEN");
		$this->data_token = $this->auth_model->check_token($user_token)->row();
	}

	/**
	 * @api {get} /messages/index/:id_user Get messages
	 * @apiSampleRequest http://api.iamchill.co/v2/messages/index/
	 * @apiVersion 0.2.0
	 * @apiName Get messages
	 * @apiGroup Messages
	 *
	 * @apiExample {curl} Example usage:
	 *    curl -i http://api.iamchill.co/v2/messages/index/
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
	 * @apiSuccess {Number} response.id Unique ID.
	 * @apiSuccess {Number} response.id_sender Users sender content unique ID.
	 * @apiSuccess {Number} response.id_recipient Users recipient content unique ID.
	 * @apiSuccess {String} response.content Content message.
	 * @apiSuccess {String} response.type Type message.
	 * @apiSuccess {Number=0,1} response.read Reading status.
	 * @apiSuccess {String} response.date_created Date created.
	 * @apiSuccessExample {json} Success-Response:
	 *    HTTP/1.1 200 OK
	 *    {
	 *        "status": "success",
	 *        "response":
	 *        {
	 *            "id":
	 *            "id_sender":
	 *            "id_recipient":
	 *            "content":
	 *            "type":
	 *            "read":
	 *            "date_created":
	 *        }
	 *    }
	 *
	 * @apiError NoMessages No messages.
	 * @apiErrorExample Error-Response:
	 *    HTTP/1.1
	 *    {
	 *        "status": "failed",
	 *        "response": "NoMessages"
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
		$id_contact = $this->get("id_contact");

		if (!empty($id_user) && !empty($id_contact)) {
			if ($id_user == $this->data_token->id_user) {
				$data_messages = $this->messages_model->get_messages($id_user, $id_contact);
				foreach ($data_messages->result() as $row) {
					if ($row->read == 0) {
						$this->messages_model->update_messages($row->id);
					}
				}
				if ($data_messages->num_rows() > 0) {
					$data_response = array(
						'status' => 'success',
						'response' => $data_messages->result()
					);
					$this->response($data_response);
				} else {
					$data_response = array(
						'status' => 'failed',
						'response' => 'No messages.'
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
	 * @api {post} /messages/index Add message
	 * @apiSampleRequest http://api.iamchill.co/v2/messages/index
	 * @apiVersion 0.2.0
	 * @apiName Add message
	 * @apiGroup Messages
	 *
	 * @apiExample {curl} Example usage:
	 *    curl -i http://api.iamchill.co/v2/messages/index
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
	 * @apiParam {Number} content Name content.
	 * @apiParam {Number} type Type content.
	 *
	 * @apiSuccess {String} status String status.
	 * @apiSuccess {Object} response Object data.
	 * @apiSuccess {Number} response.id_sender Users sender content unique ID.
	 * @apiSuccess {Number} response.id_recipient Users recipient content unique ID.
	 * @apiSuccess {String} response.content Content message.
	 * @apiSuccess {String} response.type Type message.
	 * @apiSuccess {Number=0,1} response.read Reading status.
	 * @apiSuccess {String} response.date_created Date created.
	 * @apiSuccessExample {json} Success-Response:
	 *    HTTP/1.1 200 OK
	 *    {
	 *        "status": "success",
	 *        "response":
	 *        {
	 *            "id_sender":
	 *            "id_recipient":
	 *            "content":
	 *            "type":
	 *            "date_created":
	 *        }
	 *    }
	 *
	 * @apiError MessageNotAdded The message is not added.
	 * @apiErrorExample Error-Response:
	 *    HTTP/1.1
	 *    {
	 *        "status": "failed",
	 *        "response": "MessageNotAdded"
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
		$content = $this->post("content");
		$type = $this->post("type");
		$text = $this->post("text");

		if (!empty($id_user) && !empty($id_contact) && !empty($content) && !empty($type)) {
			if ($id_user == $this->data_token->id_user) {
				if (empty($text)) {
					$text = "";
				}
				$data_message = array(
					'id_sender' => $id_user,
					'id_recipient' => $id_contact,
					'content' => $content,
					'type' => $type,
					'text' => $text
				);

				if ($this->messages_model->add_message($data_message)) {
					$data_response = array(
						'status' => 'success',
						'response' => $data_message
					);
					$this->response($data_response);
				} else {
					$data_response = array(
						'status' => 'failed',
						'response' => 'The message is not added.'
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

/* End of file Messages.php */
/* Location: ./application/controllers/v2/Messages.php */
