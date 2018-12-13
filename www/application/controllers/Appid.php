<?php
defined('BASEPATH') OR exit('No direct script access allowed');

require APPPATH . '/libraries/REST_Controller.php';

class Appid extends REST_Controller {

    function __construct($config = 'rest') {
        parent::__construct($config);

        $this->load->model('Appid_model', 'mymodel');
    }

    // get data
    function index_get($id=null) {
        $qdata      = $this->_build_query_get($id);        

        $auth       = $this->head('Auth');
        $authdt     = JWT::decode($auth, $this->config->item('jwt_key'));
        
        $dt     = $this->mymodel->get($qdata->where, $qdata->like, $qdata->orderby, $qdata->limit, $qdata->offset);
        $dtTot  = $this->mymodel->getCount($qdata->where, $qdata->like, $qdata->orderby);
        $rdt    = $this->_build_return($dt);

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
            'name' => $this->post('name'),
            'description' => $this->post('description'),
            'appid' => ($this->post('appid') ? $this->post('appid') : genUids(10)),
            'appsecret' => ($this->post('appsecret') ? $this->post('appsecret') : genUids(20)),
            'type' => $this->post('type'),            
            'createdBy' => $authdt->user->id,
            'updatedAt' => date('Y-m-d H:i:s'),
            'updatedBy' => $authdt->user->id,
            'lastip' => $this->input->ip_address(),
            'trash' => ($this->post('trash') ? $this->post('trash') : '0')
        );
        if ($id) {
            $data['id']         = $id;
        } else {
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
}