<?php
defined('BASEPATH') OR exit('No direct script access allowed');

require(APPPATH . '/libraries/REST_Controller.php');

class Icons extends REST_Controller
{
	public $data_token;
	
	function __construct()
	{
		parent::__construct();

		$this->load->model('v2/icons_model');

		$http_head_user_token = $this->input->server("HTTP_X_API_TOKEN");
		$this->data_token = $this->auth_model->check_token($http_head_user_token)->row();
	}

	/**
	 * @api {get} /icons/index/:id_user Get all icons
	 * @apiSampleRequest http://api.iamchill.co/v2/icons/index/
	 * @apiVersion 0.2.0
	 * @apiName Get all icons
	 * @apiGroup Icons
	 *
	 * @apiExample {curl} Example usage:
	 *    curl -i http://api.iamchill.co/v2/icons/index/
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
	 * @apiParam {Number} id_user Users ID.
	 *
	 * @apiSuccess {String} status String status.
	 * @apiSuccess {Object} response Object data.
	 * @apiSuccess {Number} response.id Icon ID.
	 * @apiSuccess {Number} response.name Name icon.
	 * @apiSuccess {Number} response.size42 Size icon 42*42.
	 * @apiSuccess {Number} response.size66 Size icon 66*66.
	 * @apiSuccess {Number} response.size214 Size icon 214*214.
	 * @apiSuccess {Number} response.size80 Size icon 80*80.
	 * @apiSuccess {Number} response.size272 Size icon 272*272.
	 * @apiSuccessExample {json} response.Success-Response:
	 *    HTTP/1.1 200 OK
	 *    {
	 *        "status": "success",
	 *        "response":
	 *        {
	 *          "id":
	 *          "name":
	 *          "pack":
	 *          "size42":
	 *          "size66":
	 *          "size214":
	 *          "size80":
	 *          "size272":
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
		$name_pack = $this->get('name_pack');

		if (!empty($id_user)) {
			if ($id_user == $this->data_token->id_user) {

				if ($name_pack == 'all') {
					$all_icons = $this->icons_model->get_all_icons();
				} else {
					$all_icons = $this->icons_model->get_pack_icons($name_pack);
				}

				$data_response = array(
					'status' => 'success',
					'response' => $all_icons->result()
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

	/**
	 * @api {get} /icons/user/:id_user Get users icons
	 * @apiSampleRequest http://api.iamchill.co/v2/icons/user/
	 * @apiVersion 0.2.0
	 * @apiName Get users icons
	 * @apiGroup Icons
	 *
	 * @apiExample {curl} Example usage:
	 *    curl -i http://api.iamchill.co/v2/icons/user/
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
	 * @apiParam {Number} id_user Users ID.
	 *
	 * @apiSuccess {String} status String status.
	 * @apiSuccess {Object} response Object data.
	 * @apiSuccess {Number} response.id Icon ID.
	 * @apiSuccess {Number} response.name Name icon.
	 * @apiSuccess {Number} response.size42 Size icon 42*42.
	 * @apiSuccess {Number} response.size66 Size icon 66*66.
	 * @apiSuccess {Number} response.size214 Size icon 214*214.
	 * @apiSuccess {Number} response.size80 Size icon 80*80.
	 * @apiSuccess {Number} response.size272 Size icon 272*272.
	 * @apiSuccessExample {json} response.Success-Response:
	 *    HTTP/1.1 200 OK
	 *    {
	 *        "status": "success",
	 *        "response":
	 *        {
	 *            "id":
	 *            "name":
	 *            "pack":
	 *            "size42":
	 *            "size66":
	 *            "size214":
	 *            "size80":
	 *            "size272":
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

	function user_get()
	{
		$id_user = $this->get("id_user");

		if (!empty($id_user)) {
			if ($id_user == $this->data_token->id_user) {
				$all_icons = $this->icons_model->get_all_icons();
				$user_icons = $this->icons_model->get_user_icons($id_user);
				$icons = array();

				foreach ($all_icons->result_array() as $item_all_icon) {
					foreach ($user_icons->result_array() as $item_user_icon) {
						if ($item_all_icon['id'] == $item_user_icon['id_icon']) {
							$item_all_icon['id'] = $item_user_icon['id_icon'];
							array_push($icons, $item_all_icon);
						}
					}
				}

				if (count($icons) == 0) {
					$icons = $this->icons_model->get_def_icons();
					$icons = $icons->result();
				}

				$data_response = array(
					'status' => 'success',
					'response' => $icons
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

	/**
	 * @api {post} /icons/index Add favorite icons user
	 * @apiSampleRequest http://api.iamchill.co/v2/icons/index
	 * @apiVersion 0.2.0
	 * @apiName Add favorite icons user
	 * @apiGroup Icons
	 *
	 * @apiExample {curl} Example usage:
	 *    curl -i http://api.iamchill.co/v2/icons/index
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
	 * @apiParam {Number} id_user Users ID.
	 * @apiParam {Number} id_icons_user List icons ID.
	 *
	 * @apiSuccess {String} status String status.
	 * @apiSuccess {Object} response Object data.
	 * @apiSuccess {Number} response.id Icon ID.
	 * @apiSuccess {Number} response.name Name icon.
	 * @apiSuccess {Number} response.size42 Size icon 42*42.
	 * @apiSuccess {Number} response.size66 Size icon 66*66.
	 * @apiSuccess {Number} response.size214 Size icon 214*214.
	 * @apiSuccess {Number} response.size80 Size icon 80*80.
	 * @apiSuccess {Number} response.size272 Size icon 272*272.
	 * @apiSuccessExample {json} response.Success-Response:
	 *    HTTP/1.1 200 OK
	 *    {
	 *        "status": "success",
	 *        "response":
	 *        {
	 *            "id_user":
	 *            "id":
	 *            "name":
	 *            "pack":
	 *            "size42":
	 *            "size66":
	 *            "size214":
	 *            "size80":
	 *            "size272":
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
	function index_post()
	{
		$id_user = $this->post("id_user");
		$id_icons_user = $this->post("id_icons_user");

		if (!empty($id_user) & !empty($id_icons_user)) {
			if ($id_user == $this->data_token->id_user) {
				$id_icons_user = explode("-", $id_icons_user);

				$this->icons_model->remove_icons($id_user);
				$icons = array();

				foreach ($id_icons_user as $id_icon_item_user) {
					$data_icons['id_user'] = $id_user;
					$data_icons['id_icon'] = $id_icon_item_user;
					array_push($icons, $data_icons);
				}

				$this->icons_model->add_icons($icons);

				$data_response = array(
					'status' => 'success',
					'response' => $icons
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

/* End of file Icons.php */
/* Location: ./application/controllers/v2/Icons.php */