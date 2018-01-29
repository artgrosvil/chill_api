<?php
defined('BASEPATH') OR exit('No direct script access allowed');

require(APPPATH . '/libraries/REST_Controller.php');

class Notifications extends REST_Controller
{
	public $data_token;

	function __construct()
	{
		parent::__construct();

		$this->load->model('v2/auth_model');
		$this->load->model('v2/users_model');
		$this->load->model('v2/icons_model');

		$user_token = $this->input->server("HTTP_X_API_TOKEN");
		$this->data_token = $this->auth_model->check_token($user_token)->row();
	}

	private $headers = array(
		"Content-Type: application/json",
		"X-Parse-Application-Id: vlSSbINvhblgGlipWpUWR6iJum3Q2xd7GthrDVUI",
		"X-Parse-REST-API-Key: kIw91AWjXcGtqkBJ2tj5LjbwvhbZUgPahKTBUeho"
	);

	/**
	 * @api {post} /notifications/message Message is received
	 * @apiSampleRequest http://api.iamchill.co/v2/notifications/message
	 * @apiVersion 0.2.0
	 * @apiName Message is received
	 * @apiGroup Notifications
	 *
	 * @apiExample {curl} Example usage:
	 *    curl -i http://api.iamchill.co/v2/notifications/message
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
	 * @apiParam {String} text Text message.
	 * @apiParam {String} content Content message.
	 * @apiParam {String} type Type message.
	 *
	 * @apiSuccess {String} status String status.
	 * @apiSuccess {Object} response Object data.
	 * @apiSuccess {Object} data Object data.
	 * @apiSuccess {Number} response.data.alert Alert message.
	 * @apiSuccess {Number} response.data.badge Badge message.
	 * @apiSuccess {Number} response.data.fromUserId From user ID.
	 * @apiSuccess {Number} response.data.sound Sound message.
	 * @apiSuccessExample {json} Success-Response:
	 *    HTTP/1.1 200 OK
	 *    {
	 *        "status": "success",
	 *        "response":
	 *        {
	 *            "channels": [
	 *                "us255"
	 *            ],
	 *            "data": {
	 *                "alert":
	 *                "badge":
	 *                "fromUserId":
	 *                "sound":
	 *            }
	 *        }
	 *    }
	 *
	 * @apiError NotificationNotSent Notification is not sent.
	 * @apiErrorExample Error-Response:
	 *    HTTP/1.1
	 *    {
	 *        "status": "failed",
	 *        "response": "NotificationNotSent."
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
	function message_post()
	{
		$id_user = $this->post("id_user");
		$id_contact = $this->post("id_contact");
		$content = $this->post("content");
		$text = $this->post("text");
		$type = $this->post("type");

		if (!empty($id_user) && !empty($id_contact) && !empty($content) && !empty($type)) {
			if ($id_user == $this->data_token->id_user) {

				$data_user = $this->users_model->get_data_user($id_user)->row();
				if ($type == 'icon') {
					$data_icon = $this->icons_model->get_icon($content)->row();
					if (empty($text) or empty($text)) {
						$push_string = $data_user->login . ': ' . html_entity_decode(stripcslashes($data_icon->bytes));
					} else {
						$push_string = $data_user->login . ': ' . html_entity_decode(stripcslashes($data_icon->bytes)) . ' #' . $text;
					}
				} elseif ($type == 'location') {
					$push_string = html_entity_decode(stripcslashes('\xF0\x9F\x93\x8D')) . ' from ' . $data_user->login;
				} elseif ($type == 'parse') {
					$push_string = html_entity_decode(stripcslashes('\xF0\x9F\x93\xB7')) . ' from ' . $data_user->login;
				}

				$parse_par = array(
					'channels' => ['us' . $id_contact],
					'data' => array(
						'alert' => $push_string,
						'badge' => 1,
						'fromUserId' => $id_user,
						'sound' => 'default',
						'category' => 'actionable'
					)
				);

				$ch = curl_init();
				curl_setopt($ch, CURLOPT_URL, 'https://api.parse.com/1/push/');
				curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
				curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
				curl_setopt($ch, CURLOPT_POST, 1);
				curl_setopt($ch, CURLOPT_HTTPHEADER, $this->headers);
				curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($parse_par));
				curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, 30);
				$data_ch = json_decode(curl_exec($ch));
				curl_close($ch);

				if ($data_ch->result) {
					$data_response = array(
						'status' => 'success',
						'response' => $parse_par
					);
					$this->response($data_response);
				} else {
					$data_response = array(
						'status' => 'failed',
						'response' => 'Notification is not sent.'
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
	 * @api {post} /notifications/contact Contact is added
	 * @apiSampleRequest http://178.62.151.46/v2/notifications/contact
	 * @apiVersion 0.2.0
	 * @apiName Contact is added
	 * @apiGroup Notifications
	 *
	 * @apiExample {curl} Example usage:
	 *    curl -i http://178.62.151.46/v2/notifications/contact
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
	 * @apiParam {String} text Text message.
	 * @apiParam {String} content Content message.
	 *
	 * @apiSuccess {String} status String status.
	 * @apiSuccess {Object} response Object data.
	 * @apiSuccess {Object} data Object data.
	 * @apiSuccess {Number} response.data.alert Alert message.
	 * @apiSuccess {Number} response.data.badge Badge message.
	 * @apiSuccess {Number} response.data.fromUserId From user ID.
	 * @apiSuccess {Number} response.data.sound Sound message.
	 * @apiSuccessExample {json} Success-Response:
	 *    HTTP/1.1 200 OK
	 *    {
	 *        "status": "success",
	 *        "response":
	 *        {
	 *            "channels":
	 *            [
	 *                "us255"
	 *            ],
	 *            "data":
	 *            {
	 *                "alert":
	 *                "badge":
	 *                "fromUserId":
	 *                "sound":
	 *            }
	 *        }
	 *    }
	 *
	 * @apiError NotificationNotSent Notification is not sent.
	 * @apiErrorExample Error-Response:
	 *    HTTP/1.1
	 *    {
	 *        "status": "failed",
	 *        "response": "NotificationNotSent."
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
	function contact_post()
	{
		$id_user = $this->post("id_user");
		$id_contact = $this->post("id_contact");

		if (!empty($id_user) && !empty($id_contact)) {
			if ($id_user == $this->data_token->id_user) {

				$data_user = $this->users_model->get_data_user($id_user)->row();

				$parse_par = array(
					'channels' => ['us' . $id_contact],
					'data' => array(
						'alert' => $data_user->login . ' wants to Chill with You!',
						'badge' => 1,
						'fromUserId' => $id_user,
						'sound' => 'default',
						'category' => 'actionable'
					)
				);

				$ch = curl_init();
				curl_setopt($ch, CURLOPT_URL, 'https://api.parse.com/1/push/');
				curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
				curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
				curl_setopt($ch, CURLOPT_POST, 1);
				curl_setopt($ch, CURLOPT_HTTPHEADER, $this->headers);
				curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($parse_par));
				curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, 30);
				$data_ch = json_decode(curl_exec($ch));
				curl_close($ch);

				if ($data_ch->result) {
					$data_response = array(
						'status' => 'success',
						'response' => $parse_par
					);
					$this->response($data_response);
				} else {
					$data_response = array(
						'status' => 'failed',
						'response' => 'Notification is not sent.'
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

/* End of file Notifications.php */
/* Location: ./application/controllers/v2/Notifications.php */