<?php
defined('BASEPATH') OR exit('No direct script access allowed');

require(APPPATH . '/libraries/REST_Controller.php');

class Facebook extends REST_Controller
{
	private $data_token;

	function __construct()
	{
		parent::__construct();

		$this->load->model('v3/facebook_model');

		$user_token = $this->input->server("HTTP_X_API_TOKEN");
		$this->data_token = $this->auth_model->check_token($user_token)->row();
	}

	/**
	 * @api {get} /facebook/index/:id_user/:id_contacts_facebook/ Get data contacts facebook
	 * @apiSampleRequest http://api.iamchill.co/v3/facebook/index/
	 * @apiVersion 0.3.0
	 * @apiName Get data contacts facebook
	 * @apiGroup Facebook
	 *
	 * @apiExample {curl} Example usage:
	 *    curl -i http://api.iamchill.co/v3/facebook/index/
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
	 * @apiSuccess {Object} facebook Object data.
	 * @apiSuccess {String} facebook.id List id twitter users.
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
	 *            "facebook":
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
		$id_contacts_facebook = $this->get('id_contacts_facebook');

		if (!empty($id_user) && !empty($id_contacts_facebook)) {
			if ($id_user == $this->data_token->id_user) {
				$data_id_contact = explode("-", $id_contacts_facebook);
				$data_contact = $this->facebook_model->get_contacts($id_user, $data_id_contact);

				$data_response = array(
					'status' => 'success',
					'response' => $data_contact
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

/* End of file Facebook.php */
/* Location: ./application/controllers/v3/Facebook.php */