<?php
defined('BASEPATH') OR exit('No direct script access allowed');

require(APPPATH . '/libraries/REST_Controller.php');

class Social extends REST_Controller
{
	public $data_token;

	function __construct()
	{
		parent::__construct();

		$this->load->model('v2/social_model');

		$user_token = $this->input->server("HTTP_X_API_TOKEN");
		$this->data_token = $this->auth_model->check_token($user_token)->row();
	}

	/**
	 * @api {get} /social/twitter/:id_user/:id_contacts_twitter/ Get data contacts twitter
	 * @apiSampleRequest http://api.iamchill.co/v2/social/twitter/
	 * @apiVersion 0.2.0
	 * @apiName Get data contacts twitter
	 * @apiGroup Social
	 *
	 * @apiExample {curl} Example usage:
	 *    curl -i http://api.iamchill.co/v2/social/twitter/
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
	 * @apiParam {Number} id_contacts_twitter Users twitter ID.
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
	 *
	 * @apiError TokenNotSent User token not sent.
	 * @apiErrorExample Error-Response:
	 *    HTTP/1.1
	 *    {
	 *        "status": "failed",
	 *        "response": "TokenNotSent"
	 *    }
	 */

	function twitter_get()
	{
		if (!empty($this->data_token)) {
			$id_user = $this->get('id_user');
			$id_contacts_twitter = $this->get('id_contacts_twitter');

			if (!empty($id_user) && !empty($id_contacts_twitter)) {
				if ($id_user == $this->data_token->id_user) {
					$data_id_contacts_twitter = explode("-", $id_contacts_twitter);
					$data_contacts_twitter = $this->social_model->twitter_contacts_get($id_user, $data_id_contacts_twitter);

					$data_response = array(
						'status' => 'success',
						'response' => $data_contacts_twitter
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
		} else {
			$data_response = array(
				'status' => 'failed',
				'response' => 'User token not sent.'
			);
			$this->response($data_response);
		}
	}

	/**
	 * @api {get} /social/facebook/:id_user/:id_contacts_facebook/ Get data contacts facebook
	 * @apiSampleRequest http://api.iamchill.co/v2/social/facebook/
	 * @apiVersion 0.2.0
	 * @apiName Get data contacts facebook
	 * @apiGroup Social
	 *
	 * @apiExample {curl} Example usage:
	 *    curl -i http://api.iamchill.co/v2/social/facebook/
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
	 * @apiParam {Number} id_contacts_facebook Users facebook ID.
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
	 *
	 * @apiError TokenNotSent User token not sent.
	 * @apiErrorExample Error-Response:
	 *    HTTP/1.1
	 *    {
	 *        "status": "failed",
	 *        "response": "TokenNotSent"
	 *    }
	 */

	function facebook_get()
	{
		if (!empty($this->data_token)) {
			$id_user = $this->get('id_user');
			$id_contacts_facebook = $this->get('id_contacts_facebook');

			if (!empty($id_user) && !empty($id_contacts_facebook)) {
				if ($id_user == $this->data_token->id_user) {
					$data_id_contacts_facebook = explode("-", $id_contacts_facebook);
					$data_contacts_facebook = $this->social_model->facebook_contacts_get($id_user, $data_id_contacts_facebook);

					$data_response = array(
						'status' => 'success',
						'response' => $data_contacts_facebook
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
		} else {
			$data_response = array(
				'status' => 'failed',
				'response' => 'User token not sent.'
			);
			$this->response($data_response);
		}
	}
}

/* End of file Social.php */
/* Location: ./application/controllers/v2/Social.php */