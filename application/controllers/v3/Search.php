<?php
defined('BASEPATH') OR exit('No direct script access allowed');

require(APPPATH . '/libraries/REST_Controller.php');

class Search extends REST_Controller
{
	private $data_token;

	function __construct()
	{
		parent::__construct();

		$this->load->model('v2/users_model');
		$this->load->model('v3/search_model');

		$user_token = $this->input->server("HTTP_X_API_TOKEN");
		$this->data_token = $this->auth_model->check_token($user_token)->row();
	}

	/**
	 * @api {get} /search/index/:id_user/:login/:type_search Search
	 * @apiSampleRequest http://api.iamchill.co/v3/search/index/
	 * @apiVersion 0.3.0
	 * @apiName Search
	 * @apiGroup Search
	 *
	 * @apiExample {curl} Example usage:
	 *    curl -i http://api.iamchill.co/v3/search/index/
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
	 * @apiParam {Number=0,1} type_search Type search, user = 0, app = 1.
	 *
	 * @apiSuccess {String} status String status.
	 * @apiSuccess {Object} response Object data.
	 * @apiSuccess {Number} response.id Unique ID.
	 * @apiSuccess {String} response.id_twitter Unique ID facebook user.
	 * @apiSuccess {String} response.id_facebook Unique ID twitter user.
	 * @apiSuccess {String} response.name Uer name.
	 * @apiSuccess {String} response.email Email user.
	 * @apiSuccess {String} response.login Login user.
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
	 *            "email":
	 *            "login":
	 *        }
	 *    }
	 *
	 * @apiError UserAppsNotExist Users or apps do not exist.
	 * @apiErrorExample Error-Response:
	 *    HTTP/1.1
	 *    {
	 *        "status": "failed",
	 *        "response": "UserAppsNotExist"
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
		$name = $this->get("name");
		$type_search = $this->get("type_search");

		if (!empty($id_user) && !empty($name)) {
			if ($id_user == $this->data_token->id_user) {

				if ($type_search == 0) {
					$data_user = $this->users_model->get_data_user($id_user)->row();
					$data_search = $this->search_model->search_user($name, $data_user->login);
				} else if ($type_search == 1) {
					$data_search = $this->search_model->search_apps($name);
				}

				if ($data_search->num_rows() > 0) {
					$data_response = array(
						'status' => 'success',
						'response' => $data_search->result()
					);
					$this->response($data_response);
				} else {
					$data_response = array(
						'status' => 'failed',
						'response' => 'Users or apps do not exist.'
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
/* Location: ./application/controllers/v3/Search.php */