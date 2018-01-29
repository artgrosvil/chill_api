<?php
defined('BASEPATH') OR exit('No direct script access allowed');

require(APPPATH . '/libraries/REST_Controller.php');

class Promocodes extends REST_Controller
{
    public $data_token;

    function __construct()
    {
        parent::__construct();

        $this->load->model('v2/promocodes_model');
        $this->load->model('v2/users_model');
        $this->load->model('v2/contacts_model');

        $user_token = $this->input->server("HTTP_X_API_TOKEN");
        $this->data_token = $this->auth_model->check_token($user_token)->row();
    }

    /**
     * @api {get} /promocodes/index/:id_user Check promocode
     * @apiSampleRequest http://api.iamchill.co/v2/promocodes/index/
     * @apiVersion 0.2.0
     * @apiName Check promocode
     * @apiGroup Promocodes
     *
     * @apiExample {curl} Example usage:
     *    curl -i http://api.iamchill.co/v2/promocodes/index/
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
     * @apiParam {Number} promocode Promocode.
     *
     * @apiSuccess {String} status String status.
     * @apiSuccess {Object} response Object data.
     * @apiSuccess {Number} response.id_user User ID.
     * @apiSuccess {Number} response.code Promocode.
     * @apiSuccessExample {json} response.Success-Response:
     *    HTTP/1.1 200 OK
     *    {
     *        "status": "success",
     *        "response":
     *        {
     *            "code":
     *        }
     *    }
     *
     * @apiError NoUpdatePromoCode No update promo code.
     * @apiErrorExample Error-Response:
     *    HTTP/1.1
     *    {
     *        "status": "failed",
     *        "response": "NoUpdatePromoCode"
     *    }
     *
     * @apiError NoPromoCode No promo code.
     * @apiErrorExample Error-Response:
     *    HTTP/1.1
     *    {
     *        "status": "failed",
     *        "response": "NoPromoCode"
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
        $promocode = $this->get('promocode');
        $id_user = $this->get('id_user');

        if (!empty($id_user) && !empty($promocode)) {
            if ($id_user == $this->data_token->id_user) {
                $data_promocode = $this->promocodes_model->get_promocode_data($promocode);

                if ($data_promocode->num_rows() == 1) {
                    $data_promocode = $data_promocode->row();

                    $data_promocode_update = array(
                        'id_user' => $id_user,
                        'count' => $data_promocode->count + 1
                    );

                    if ($this->promocodes_model->update_code($promocode, $data_promocode_update)) {

                        $data_contacts = array(
                            'id_user' => $id_user,
                            'id_contact' => $data_promocode->id_user_invited,
                            'type_contact' => $data_promocode->type_contact
                        );
                        $data_contacts_re = array(
                            'id_user' => $data_promocode->id_user_invited,
                            'id_contact' => $id_user,
                            'type_contact' => $data_promocode->type_contact
                        );

                        if ($this->contacts_model->add_contact($data_contacts) && $this->contacts_model->add_contact($data_contacts_re)) {
                            $data_response = array(
                                'status' => 'success',
                                'response' => $data_contacts
                            );
                            $this->response($data_response);
                        } else {
                            $data_response = array(
                                'status' => 'failed',
                                'response' => 'Error adding contact.'
                            );
                            $this->response($data_response);
                        }
                    } else {
                        $data_response = array(
                            'status' => 'failed',
                            'response' => 'No update promo code.'
                        );
                        $this->response($data_response);
                    }
                } else {
                    $data_response = array(
                        'status' => 'failed',
                        'response' => 'No promo code.'
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
     * @api {post} /promocodes/index Create promocode
     * @apiSampleRequest http://api.iamchill.co/v2/promocodes/index
     * @apiVersion 0.2.0
     * @apiName Create promocode
     * @apiGroup Promocodes
     *
     * @apiExample {curl} Example usage:
     *    curl -i http://api.iamchill.co/v2/promocodes/index
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
     *
     * @apiSuccess {String} status String status.
     * @apiSuccess {Object} response Object data.
     * @apiSuccess {Number} response.id_user_invited User ID invited.
     * @apiSuccess {Number} response.code Promocode.
     * @apiSuccess {Number} response.link Link to website.
     * @apiSuccessExample {json} Success-Response:
     *    HTTP/1.1 200 OK
     *    {
     *        "status": "success",
     *        "response":
     *        {
     *            "id_user_invited":
     *            "code":
     *            "link":
     *        }
     *    }
     *
     * @apiError NoPromoCode No promo code.
     * @apiErrorExample Error-Response:
     *    HTTP/1.1
     *    {
     *        "status": "failed",
     *        "response": "NoPromoCode"
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

        if (!empty($id_user)) {
            if ($id_user == $this->data_token->id_user) {

                $promo_code = substr(md5(uniqid(rand(), true)), 0, 10);
                $data_promo_codes = array(
                    'id_user_invited' => $id_user,
                    'code' => $promo_code,
                    'count' => 0,
                    'type_contact' => 0
                );
                if ($this->promocodes_model->add_code($data_promo_codes)) {
                    $data_user = $this->users_model->get_data_user($id_user)->row();

                    $data_promo_codes = array(
                        'id_user_invited' => $id_user,
                        'code' => $promo_code,
                        'link' => 'http://iamchill.co/user/' . $data_user->login . '/promocode/' . $promo_code
                    );
                    $data_response = array(
                        'status' => 'success',
                        'response' => $data_promo_codes
                    );
                    $this->response($data_response);
                } else {
                    $data_response = array(
                        'status' => 'failed',
                        'response' => 'No promo code.'
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

/* End of file Promocodes.php */
/* Location: ./application/controllers/v2/Promocodes.php */