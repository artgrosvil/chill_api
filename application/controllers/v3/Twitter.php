<?php
defined('BASEPATH') OR exit('No direct script access allowed');

require(APPPATH . '/libraries/REST_Controller.php');

class Twitter extends REST_Controller
{
	private $data_token;

	function __construct()
	{
		parent::__construct();

		$this->load->model('v3/twitter_model');

		$user_token = $this->input->server("HTTP_X_API_TOKEN");
		$this->data_token = $this->auth_model->check_token($user_token)->row();
	}

	/**
	 * @api {get} /twitter/index/:id_user/:id_contacts_twitter/ Get data contacts twitter
	 * @apiSampleRequest http://api.iamchill.co/v3/twitter/index
	 * @apiVersion 0.3.0
	 * @apiName Get data contacts twitter
	 * @apiGroup Twitter
	 *
	 * @apiExample {curl} Example usage:
	 *    curl -i http://api.iamchill.co/v3/twitter/index
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
	 * @apiSuccess {Object} twitter Object data.
	 * @apiSuccess {String} twitter.id List id twitter users.
	 * @apiSuccess {Object} chill Object data.
	 * @apiSuccess {Number} chill.id_user Users unique ID.
	 * @apiSuccess {Number} chill.id_contact Users contact unique ID.
	 * @apiSuccess {Number} chill.id_twitter Twitter users unique ID.
	 * @apiSuccess {String} chill.name User name.
	 * @apiSuccess {String} chill.email User email.
	 * @apiSuccessExample {json} response.Success-Response:
	 *    HTTP/1.1 200 OK
	 *    {
	 *        "status": "success",
	 *        "response":
	 *        {
	 *            "chill":
	 *            {
	 *                "id":
	 *                "id_twitter":
	 *                "id_facebook":
	 *                "name":
	 *                "email":
	 *                "login":
	 *            },
	 *            "twitter":
	 *            {
	 *                id
	 *            }
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
		$id_user = $this->get('id_user');
		$id_contacts_twitter = $this->get('id_contacts_twitter');

		if (!empty($id_user) && !empty($id_contacts_twitter)) {
			if ($id_user == $this->data_token->id_user) {
				$data_id_contact = explode("-", $id_contacts_twitter);
				$data_contacts = $this->twitter_model->get_contacts($id_user, $data_id_contact);

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
}

/* End of file Twitter.php */
/* Location: ./application/controllers/v3/Twitter.php */