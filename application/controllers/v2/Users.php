<?php
defined('BASEPATH') OR exit('No direct script access allowed');

require(APPPATH . '/libraries/REST_Controller.php');

class Users extends REST_Controller
{
	public $data_token;

	function __construct()
	{
		parent::__construct();

		$this->load->model('v2/users_model');
		$this->load->model('v2/contacts_model');

		$user_token = $this->input->server("HTTP_X_API_TOKEN");
		$this->data_token = $this->auth_model->check_token($user_token)->row();
	}

	/**
	 * @api {get} /users/index/:id_user Get data user
	 * @apiSampleRequest http://api.iamchill.co/v2/users/index/
	 * @apiVersion 0.2.0
	 * @apiName Get data user
	 * @apiGroup Users
	 *
	 * @apiExample {curl} Example usage:
	 *    curl -i http://api.iamchill.co/v2/users/index/
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
	 *
	 * @apiSuccess {String} status String status.
	 * @apiSuccess {Object} response Object data.
	 * @apiSuccess {Number} response.id User ID.
	 * @apiSuccess {Number} response.id_twitter Twitter ID.
	 * @apiSuccess {Number} response.id_facebook Facebook ID.
	 * @apiSuccess {String} response.name User name.
	 * @apiSuccess {String} response.twitter_name Twitter name.
	 * @apiSuccess {String} response.email User email.
	 * @apiSuccess {String} response.login User login.
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
	 *        }
	 *    }
	 *
	 * @apiError UserNotExist The user does not exist.
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

		if (!empty($id_user)) {
			if ($id_user == $this->data_token->id_user) {
				$data_user = $this->users_model->get_data_user($id_user);

				if ($data_user->num_rows() == 1) {
					foreach ($data_user->result() as $item_data_user) {
						unset($item_data_user->hash);
						unset($item_data_user->key);
					}

					$data_response = array(
						'status' => 'success',
						'response' => $data_user->result()
					);
					$this->response($data_response);
				} else {
					$data_response = array(
						'status' => 'failed',
						'response' => 'The user does not exist.'
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
	 * @api {post} /users/index Reg and auth user
	 * @apiSampleRequest http://api.iamchill.co/v2/users/index
	 * @apiVersion 0.2.0
	 * @apiName Reg and auth user
	 * @apiGroup Users
	 *
	 * @apiExample {curl} Example usage:
	 *    curl -i http://api.iamchill.co/v2/users/index
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
	 * @apiParam {Number} login User login.
	 * @apiParam {Number} password User password.
	 *
	 * @apiSuccess {String} status String status.
	 * @apiSuccess {Object} response Object data.
	 * @apiSuccess {Number} response.id_user User ID.
	 * @apiSuccess {String} response.login User login.
	 * @apiSuccess {String} response.token User token.
	 * @apiSuccessExample {json} Success-Response:
	 *    HTTP/1.1 200 OK
	 *    {
	 *        "status": "success",
	 *        "response":
	 *        {
	 *            "id_user":
	 *            "login":
	 *            "token":
	 *        }
	 *    }
	 *
	 * @apiError UserNotExist The user does not exist.
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
	function index_post()
	{
		$login = $this->post("login");
		$password = $this->post("password");

		if (!empty($login) && !empty($password)) {
			if ($this->users_model->check_reg($login)) {
				$salt = '$2y$11$' . substr(md5(uniqid(rand(), true)), 0, 22);
				$key = crypt($password, $salt);

				$salt = '$2y$11$' . substr(md5(uniqid(rand(), true)), 0, 22);
				$hash = crypt($password, $salt);

				$data_user = array(
					'name' => $login,
					'email' => $login,
					'login' => $login,
					'hash' => $hash,
					'key' => $key
				);

				if ($this->users_model->add_user($data_user)) {

					$data_user_tmp = $this->auth_model->get_data_user($login)->row();

					$token = substr(md5(uniqid(rand(), true)), 0, 22);

					$data_token = array(
						'id_user' => $data_user_tmp->id,
						'token' => $token
					);

					$this->auth_model->add_token($data_token);

					$data_user = array(
						'id_user' => $data_user_tmp->id,
						'login' => $data_user_tmp->login,
						'token' => $token,
						'auth' => '0'
					);

					$data_contacts_tmp = array(
						'id_user' => $data_user_tmp->id,
						'id_contact' => '1'
					);
					$this->contacts_model->add_contact($data_contacts_tmp);

					$data_response = array(
						'status' => 'success',
						'response' => $data_user
					);
					$this->response($data_response);
				} else {
					$data_response = array(
						'status' => 'failed',
						'response' => 'This user has not added.'
					);
					$this->response($data_response);
				}
			} else {
				$data_user = $this->auth_model->get_data_user($login);
				if ($data_user->num_rows() > 0) {
					$data_user = $data_user->row();

					$hash_tmp = crypt($password, $data_user->hash);

					if ($data_user->hash == $hash_tmp) {

						$data_token = $this->auth_model->get_token($data_user->id)->row();

						$data_auth = array(
							'id_user' => $data_user->id,
							'token' => $data_token->token,
							'auth' => '1'
						);

						$data_response = array(
							'status' => 'success',
							'response' => $data_auth
						);
						$this->response($data_response);
					} else {
						$data_response = array(
							'status' => 'failed',
							'response' => 'Password is not valid.'
						);
						$this->response($data_response);
					}
				} else {
					$data_response = array(
						'status' => 'failed',
						'response' => 'The user does not exist.'
					);
					$this->response($data_response);
				}
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
	 * @api {post} /users/update Update data user
	 * @apiSampleRequest http://api.iamchill.co/v2/users/update
	 * @apiVersion 0.2.0
	 * @apiName Update data user
	 * @apiGroup Users
	 *
	 * @apiExample {curl} Example usage:
	 *    curl -i http://api.iamchill.co/v2/users/update
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
	 * @apiParam {Number} id_user User ID.
	 * @apiParam {Number} id_twitter Twitter ID.
	 * @apiParam {Number} id_facebook Facebook ID.
	 * @apiParam {Number} login user login.
	 * @apiParam {Number} name User name.
	 * @apiParam {Number} email user email.
	 * @apiParam {Number} twitter_name Twitter name.
	 * @apiParam {Number} password User password.
	 *
	 * @apiSuccess {String} status String status.
	 * @apiSuccess {Object} response Object data.
	 * @apiSuccess {Number} response.id User ID.
	 * @apiSuccess {Number} response.id_twitter Twitter ID.
	 * @apiSuccess {Number} response.id_facebook Facebook ID.
	 * @apiSuccess {String} response.name Name user.
	 * @apiSuccess {String} response.twitter_name Twitter name.
	 * @apiSuccess {String} response.email User email.
	 * @apiSuccessExample {json} Success-Response:
	 *    HTTP/1.1 200 OK
	 *    {
	 *        "status": "success",
	 *        "response":
	 *        {
	 *            "id_user":
	 *            "id_twitter":
	 *            "id_facebook":
	 *            "login":
	 *            "name":
	 *            "email":
	 *            "twitter_name":
	 *        }
	 *    }
	 *
	 * @apiError UserNotUpdated This user has not updated.
	 * @apiErrorExample Error-Response:
	 *    HTTP/1.1
	 *    {
	 *        "status": "failed",
	 *        "response": "UserNotUpdated"
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
	function update_post()
	{
		$id_user = $this->post("id_user");
		$id_twitter = $this->post("id_twitter");
		$id_facebook = $this->post("id_facebook");
		$name = $this->post("name");
		$email = $this->post("email");
		$twitter_name = $this->post("twitter_name");
		$password = $this->post("password");

		if (!empty($id_user)) {
			$data_user = array();
			if (!empty($id_twitter)) {
				$data_user['id_twitter'] = $id_twitter;
			}
			if (!empty($id_facebook)) {
				$data_user['id_facebook'] = $id_facebook;
			}
			if (!empty($name)) {
				$data_user['name'] = $name;
			}
			if (!empty($email)) {
				$data_user['email'] = $email;
			}
			if (!empty($twitter_name)) {
				$data_user['twitter_name'] = $twitter_name;
			}
			if (!empty($password)) {
				$salt = '$2y$11$' . substr(md5(uniqid(rand(), true)), 0, 22);
				$key = crypt($password, $salt);

				$salt = '$2y$11$' . substr(md5(uniqid(rand(), true)), 0, 22);
				$hash = crypt($password, $salt);

				$data_user['hash'] = $hash;
				$data_user['key'] = $key;
			}

			if ($this->users_model->update_user($data_user, $id_user)) {
				$data_user['id_user'] = $id_user;

				unset($data_user['hash']);
				unset($data_user['key']);

				$data_response = array(
					'status' => 'success',
					'response' => $data_user
				);
				$this->response($data_response);
			} else {
				$data_response = array(
					'status' => 'failed',
					'response' => 'This user has not updated.'
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

/* End of file Users.php */
/* Location: ./application/controllers/v2/Users.php */