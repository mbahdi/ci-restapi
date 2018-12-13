<?php
defined('BASEPATH') OR exit('No direct script access allowed');

require APPPATH . '/libraries/REST_Controller.php';

class Token extends REST_Controller {

    function __construct($config = 'rest') {
        parent::__construct($config);

        $this->load->model('Token_model', 'mymodel');
    }

    // GET TOKEN
    function index_get() {
        $appid      = $this->head('Appid');
        $appsec     = $this->head('Appsec');

        if (!$appid || !$appsec) {
            $resp = array(
                'code'=>$this->config->item('unauthorized_code'),
                'message'=>$this->config->item('unauthorized_msg')
            );
        } else {
            $auth = $this->mymodel->auth($appid,$appsec);
            if ($auth) {
                $data = array(
                    'app'=>$auth[0],
                    'agent'=>$this->head('User-Agent'),
                    // 'tokentime' => strtotime('-1 day'),
                    'tokentime' => time(),
                    'exp' => time() + $this->config->item('exp_token')
                );
                $token = JWT::encode($data, $this->config->item('jwt_key'));

                $resp = array(
                    'code'=>$this->config->item('success_code'),
                    'message'=>$this->config->item('success_msg'),
                    'token'=>$token
                );
            } else {
                $resp = array(
                    'code'=>$this->config->item('invalidapp_code'),
                    'message'=>$this->config->item('invalidapp_msg')
                );
            }
        }

        $this->response($resp, 200);
    }
    
    // decode for us only
    function decode_get() {
        $token      = $this->head('Token');
        $auth       = $this->head('Auth');
        $resp       = array();

        if ($token) {
            $tokendt        = JWT::decode($token, $this->config->item('jwt_key'));
            $resp['token']  = $tokendt;
        }

        if ($auth) {
            $authdt         = JWT::decode($auth, $this->config->item('jwt_key'));
            $resp['auth']   = $authdt;
        }

        $this->response($resp, 200);        
    }
}