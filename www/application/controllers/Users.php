<?php
defined('BASEPATH') OR exit('No direct script access allowed');

require APPPATH . '/libraries/REST_Controller.php';

class Users extends REST_Controller {

    function __construct($config = 'rest') {
        parent::__construct($config);

        $this->load->model('Users_model', 'mymodel');
    }

    // get data
    function index_get($id=null) {
        $qdata      = $this->_build_query_get($id);        

        $auth       = $this->head('Auth');
        $authdt     = JWT::decode($auth, $this->config->item('jwt_key'));
        // print_r($authdt);
        if ($authdt->user->role > 1) {
            $qdata->where["role !="] = 1;
        }
        // print_r($qdata);
        // print_r($this->get());

        $dt     = $this->mymodel->get($qdata->where, $qdata->like, $qdata->orderby, $qdata->limit, $qdata->offset);
        $dtTot  = $this->mymodel->getCount($qdata->where, $qdata->like, $qdata->orderby);
        $rdt    = $this->_build_return($dt);

        // echo("data => ".$dt."<br>");
        // echo($this->db->last_query());
        // print_r($dt);

        if ($dt) {
            $resp = array(
                'code'=>$this->config->item('success_code'),
                'message'=>$this->config->item('success_msg'),
                'total'=>$dtTot,
                'page'=>$rdt->page,
                'limit'=>$rdt->limit,
                'offset'=>$rdt->offset,
                'data'=>$rdt->data
            );
        } else {
            $resp = array(
                'code'=>$this->config->item('nodata_code'),
                'message'=>$this->config->item('nodata_msg'),
                'total'=>$dtTot,
                'page'=>$rdt->page,
                'limit'=>$rdt->limit,
                'offset'=>$rdt->offset,
                'data'=>$rdt->data
            );            
        }

        $this->response($resp, 200);
    }

    // insert data
    function index_post($id=null) {
        // print_r($this->post());

        $auth       = $this->head('Auth');
        $authdt     = JWT::decode($auth, $this->config->item('jwt_key'));

        $mysalt = genSalt();
        $data = array(
            'username' => $this->post('username'),
            'role' => $this->post('role'),
            'pp' => $this->post('pp'),
            'trash' => ($this->post('trash') ? $this->post('trash') : '0'),
            'createdBy' => $authdt->user->id,
            'updatedAt' => date('Y-m-d H:i:s'),
            'updatedBy' => $authdt->user->id,
            'lastip' => $this->input->ip_address()
        );
        if ($id) {
            $data['id']         = $id;
            
            if ($this->post('password')) {
                $data['salt']       = $mysalt;
                $data['password']   = md5(''.md5(''.$this->post('password').'').$mysalt.'');
            }
        } else {
            $data['salt']       = $mysalt;
            $data['password']   = md5(''.md5(''.$this->post('password').'').$mysalt.'');
            $data['createdAt']  = date('Y-m-d H:i:s');
        }
        $data = (object) $data;

        // $insert = $this->db->insert('users', $this->post());
        $save = $this->mymodel->save($data);
        if ($save) {
            $resp = array(
                'code'=>$this->config->item('success_code'),
                'message'=>$this->config->item('success_msg'),
                'total'=>1,
                'data'=>$save
            );
        } else {
            $resp = array(
                'code'=>$this->config->item('savefailed_code'),
                'message'=>$this->config->item('savefailed_msg')
            );
        }
        $this->response($resp, 200);
    }

    // update data
    function index_put($id=null) {
        // print_r($this->put());

        $auth       = $this->head('Auth');
        $authdt     = JWT::decode($auth, $this->config->item('jwt_key'));

        if ($id) {
            $mysalt = genSalt();

            $data = $this->put();
            $data['id']         = $id;
            $data['updatedAt']  = date('Y-m-d H:i:s');
            $data['updatedBy']  = $authdt->user->id;
            $data['lastip']     = $this->input->ip_address();

            if ($this->put('password')) {
                $data['salt']       = $mysalt;
                $data['password']   = md5(''.md5(''.$this->put('password').'').$mysalt.'');
            }

            $data = (object) $data;

            $save = $this->mymodel->save($data);
            if ($save) {
                $resp = array(
                    'code'=>$this->config->item('success_code'),
                    'message'=>$this->config->item('success_msg'),
                    'total'=>1,
                    'data'=>$save
                );
            } else {
                $resp = array(
                    'code'=>$this->config->item('savefailed_code'),
                    'message'=>$this->config->item('savefailed_msg')
                );
            }
        } else {
            $resp = array(
                'code'=>$this->config->item('invalidreq_code'),
                'message'=>$this->config->item('invalidreq_msg')
            );
        }
        $this->response($resp, 200);
    }

    // delete data
    function index_delete($id=null) {
        if ($id) {
            $save = $this->mymodel->trash($id);
            // $save = $this->mymodel->delete($id);
            if ($save) {
                $resp = array(
                    'code'=>$this->config->item('success_code'),
                    'message'=>$this->config->item('success_msg'),
                    'id'=>$save
                );
            } else {
                $resp = array(
                    'code'=>$this->config->item('savefailed_code'),
                    'message'=>$this->config->item('savefailed_msg')
                );
            }
        } else {
            $resp = array(
                'code'=>$this->config->item('invalidreq_code'),
                'message'=>$this->config->item('invalidreq_msg')
            );
        }
        $this->response($resp, 200);
    }

    // retoken 
    function retoken_get($id=null) {
        if ($id) {
            $dt     = $this->mymodel->reToken($id);
            if ($dt) {

                if(isset($dt->salt)){
                    unset($dt->salt);
                }
                if(isset($dt->password)){
                    unset($dt->password);
                }

                $data = array(
                    'user'=>$dt,
                    'usertime' => time(),
                    'agent'=>$this->head('User-Agent'),
                    'exp' => time() + $this->config->item('exp_login')
                );
                $auth = JWT::encode($data, $this->config->item('jwt_key'));

                $resp = array(
                    'code'=>$this->config->item('success_code'),
                    'message'=>$this->config->item('success_msg'),
                    'auth'=>$auth
                );
            } else {
                $resp = array(
                    'code'=>$this->config->item('invalidreq_code'),
                    'message'=>$this->config->item('invalidreq_msg')
                );
            }
        } else {
            $resp = array(
                'code'=>$this->config->item('invalidreq_code'),
                'message'=>$this->config->item('invalidreq_msg')
            );
        }
        $this->response($resp, 200);
    }

    // change password 
    function cpassword_post($id=null) {
        $oldPassword    = $this->post('oldPassword');
        $password       = $this->post('password');

        if ($id) {
            $mysalt = genSalt();
            $dt     = $this->mymodel->get(array("id"=>$id), null, null, 1, 0, 1);

            if ($dt) {
                // print_r($dt);
                $tpassword = md5(''.md5(''.$oldPassword.'').$dt[0]->salt.'');

                if ($tpassword === $dt[0]->password) {

                    // change pwd pcd 
                    $data['id']         = $id;
                    $data['updatedAt']  = date('Y-m-d H:i:s');
                    $data['updatedBy']  = $id;
                    $data['lastip']     = $this->input->ip_address();
                    $data['salt']       = $mysalt;
                    $data['password']   = md5(''.md5(''.$password.'').$mysalt.'');

                    $data = (object) $data;

                    $save = $this->mymodel->save($data);
                    if ($save) {
                        $resp = array(
                            'code'=>$this->config->item('success_code'),
                            'message'=>$this->config->item('success_msg'),
                            'total'=>1,
                            'data'=>$save
                        );
                    } else {
                        $resp = array(
                            'code'=>$this->config->item('savefailed_code'),
                            'message'=>$this->config->item('savefailed_msg')
                        );
                    }
                    // end change pwd
                } else {
                    $resp = array(
                        'code'=>$this->config->item('password_nomatch_code'),
                        'message'=>$this->config->item('password_nomatch_msg')
                    );                    
                }
            } else {
                $resp = array(
                    'code'=>$this->config->item('invalidreq_code'),
                    'message'=>$this->config->item('invalidreq_msg')
                );
            }
        } else {
            $resp = array(
                'code'=>$this->config->item('invalidreq_code'),
                'message'=>$this->config->item('invalidreq_msg')
            );
        }

        $this->response($resp, 200);
    }   

    // LOGIN
    function login_post() {
        $username   = $this->post('username');
        $password   = $this->post('password');

        $dt     = $this->mymodel->login($username, $password);
        // $auth   = JWT::decode($this->head('token'), $this->config->item('jwt_key'));
        // print_r($dt);

        if ($dt) {

            if(isset($dt->salt)){
                unset($dt->salt);
            }
            if(isset($dt->password)){
                unset($dt->password);
            }

            $data = array(
                'user'=>$dt,
                'usertime' => time(),
                'agent'=>$this->head('User-Agent'),
                'exp' => time() + $this->config->item('exp_login')
            );
            $auth = JWT::encode($data, $this->config->item('jwt_key'));

            $resp = array(
                'code'=>$this->config->item('success_code'),
                'message'=>$this->config->item('success_msg'),
                'auth'=>$auth
            );
        } else {
            $resp = array(
                'code'=>$this->config->item('failed_login_code'),
                'message'=>$this->config->item('failed_login_msg')
            );
        }

        $this->response($resp, 200);
    }   

}