<?php
defined('BASEPATH') OR exit('No direct script access allowed');

require(APPPATH . '/libraries/REST_Controller.php');

class Apps extends REST_Controller
{
	private $data_token;

	function __construct()
	{
		parent::__construct();

		$this->load->model('v3/apps_model');

		$user_token = $this->input->server("HTTP_X_API_TOKEN");
		$this->data_token = $this->auth_model->check_token($user_token)->row();
	}

	/**
	 * @api {get} /apps/index/:id_user/:id_app Get data app
	 * @apiSampleRequest http://api.iamchill.co/v3/apps/index/
	 * @apiVersion 0.3.0
	 * @apiName Get data app
	 * @apiGroup Apps
	 *
	 * @apiExample {curl} Example usage:
	 *    curl -i http://api.iamchill.co/v3/users/index/
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
	 * @apiParam {Number} id_user User ID
	 * @apiParam {Number} id_app App ID
	 *
	 * @apiSuccess {String} status String status.
	 * @apiSuccess {Object} response Object data.
	 * @apiSuccess {Number} response.id App ID.
	 * @apiSuccess {Number} response.name Name.
	 * @apiSuccess {Number} response.type Type.
	 * @apiSuccess {String} response.id_category Category ID.
	 * @apiSuccess {String} response.description Description.
	 * @apiSuccess {String} response.status Status published.
	 * @apiSuccess {String} response.language Language.
	 * @apiSuccess {String} response.loop Loop.
	 * @apiSuccessExample {json} Success-Response:
	 *    HTTP/1.1 200 OK
	 *    {
	 *        "status": "success",
	 *        "response":
	 *        {
	 *            "id":
	 *            "name":
	 *            "type":
	 *            "id_category":
	 *            "description":
	 *            "status":
	 *            "language":
	 *            "loop":
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
		$id_app = $this->get("id_app");

		if (!empty($id_user) && !empty($id_app)) {
			if ($id_user == $this->data_token->id_user) {
				$data_app = $this->apps_model->get_data_app($id_app);

				$data_response = array(
					'status' => 'success',
					'response' => $data_app->result()
				);
				$this->response($data_response);
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

/* End of file Apps.php */
/* Location: ./application/controllers/v3/Apps.php */