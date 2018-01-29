<?php
defined('BASEPATH') OR exit('No direct script access allowed');

require(APPPATH . '/libraries/REST_Controller.php');

class Search extends REST_Controller
{
	public $data_token;

	function __construct()
	{
		parent::__construct();

		$this->load->model('v2/users_model');
		$this->load->model('v2/search_model');

		$user_token = $this->input->server("HTTP_X_API_TOKEN");
		$this->data_token = $this->auth_model->check_token($user_token)->row();
	}

	/**
	 * @api {get} /messages/index/:id_user/:login Search
	 * @apiSampleRequest http://api.iamchill.co/v2/messages/index/
	 * @apiVersion 0.2.0
	 * @apiName Search
	 * @apiGroup Search
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
	 * @apiParam {Number} login Users contact unique ID.
	 *
	 * @apiSuccess {String} status String status.
	 * @apiSuccess {Object} response Object data.
	 * @apiSuccess {Number} response.id Unique ID.
	 * @apiSuccess {String} response.name User name.
	 * @apiSuccess {String} response.twitter_name Twitter name.
	 * @apiSuccess {String} response.email User email.
	 * @apiSuccess {String} response.login User login.
	 * @apiSuccess {String} response.date_reg Date registration user.
	 * @apiSuccessExample {json} Success-Response:
	 *    HTTP/1.1 200 OK
	 *    {
	 *        "status": "success",
	 *        "response":
	 *        {
	 *            "id":
	 *            "id_twitter":
	 *            "id_facebook":
	 *            "name":
	 *            "twitter_name":
	 *            "email":
	 *            "login":
	 *            "hash":
	 *            "key":
	 *            "date_reg":
	 *        }
	 *    }
	 *
	 * @apiError UserNotExist Users do not exist.
	 * @apiErrorExample Error-Response:
	 *    HTTP/1.1
	 *    {
	 *        "status": "failed",
	 *        "response": "UserNotExist"
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
		$login = $this->get("login");

		if (!empty($id_user) && !empty($login)) {
			if ($id_user == $this->data_token->id_user) {
				$data_user = $this->users_model->get_data_user($id_user)->row();
				$data_search = $this->search_model->search_user($login, $data_user->login);

				if ($data_search->num_rows() > 0) {
					$data_response = array(
						'status' => 'success',
						'response' => $data_search->result()
					);
					$this->response($data_response);
				} else {
					$data_response = array(
						'status' => 'failed',
						'response' => 'Users do not exist.'
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

/* End of file Search.php */
/* Location: ./application/controllers/v2/Search.php */