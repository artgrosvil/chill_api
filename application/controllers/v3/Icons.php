<?php
defined('BASEPATH') OR exit('No direct script access allowed');

require(APPPATH . '/libraries/REST_Controller.php');

class Icons extends REST_Controller
{
	public $data_token;

	function __construct()
	{
		parent::__construct();

		$this->load->model('v3/icons_model');

		$http_head_user_token = $this->input->server("HTTP_X_API_TOKEN");
		$this->data_token = $this->auth_model->check_token($http_head_user_token)->row();
	}

	/**
	 * @api {get} /icons/index/:id_user/:name_pack Get icons
	 * @apiSampleRequest http://api.iamchill.co/v3/icons/index/
	 * @apiVersion 0.3.0
	 * @apiName Get icons
	 * @apiGroup Icons
	 *
	 * @apiExample {curl} Example usage:
	 *    curl -i http://api.iamchill.co/v3/icons/index/
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
	 * @apiParam {Number} name_pack Name pack.
	 *
	 * @apiSuccess {String} status String status.
	 * @apiSuccess {Object} response Object data.
	 * @apiSuccess {Number} response.id Icon ID.
	 * @apiSuccess {Number} response.name Name icon.
	 * @apiSuccess {Number} response.description Description icon.
	 * @apiSuccess {Number} response.pack Pack name icon.
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
	 *          "description":
	 *          "pack":
	 *          "size42":
	 *          "size66":
	 *          "size214":
	 *          "size80":
	 *          "size272":
	 *          "bytes":
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
		$name_pack = $this->get('name_pack');

		if (!empty($id_user) && !empty($name_pack)) {
			if ($id_user == $this->data_token->id_user) {

				if ($name_pack == 'all') {
					$all_icons = $this->icons_model->get_all_icons()->result();
				} elseif ($name_pack == 'fav') {
					$all_icons = $this->icons_model->get_user_icons($id_user)->result();
				} elseif ($name_pack == 'onboarding') {
					$list_id_icon = [3, 31, 1, 32, 33, 8];
					$all_icons = $this->icons_model->get_onboarding_icons($list_id_icon)->result();
				} elseif ($name_pack == 'onboarding2') {
					$list_id_icon = [3, 31, 1, 32, 33, 8];
					$act1_tmp = array();
					$act = $this->icons_model->get_onboarding_icons($list_id_icon)->result_array();


					foreach ($list_id_icon as $list_id_icon_item) {
						foreach ($act as $act_item) {
							if ($list_id_icon_item == $act_item['id']) {
								array_push($act1_tmp, $act_item);
							}
						}
					}

					$all_icons = [
						'act1' => $act1_tmp,
						'act2' => $this->icons_model->get_without_onboarding_icons($list_id_icon)->result(),
					];
				} else {
					$all_icons = $this->icons_model->get_pack_icons($name_pack)->result();
				}

				$data_response = array(
					'status' => 'success',
					'response' => $all_icons
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

				foreach ($data_icons as $item_icons) {
					$this->icons_model->add_icons($item_icons);
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
}

/* End of file Icons.php */
/* Location: ./application/controllers/v3/Icons.php */