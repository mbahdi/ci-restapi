<?php
defined('BASEPATH') OR exit('No direct script access allowed');

require APPPATH . '/libraries/REST_Controller.php';

class Products extends REST_Controller {

    function __construct($config = 'rest') {
        parent::__construct($config);

        $this->load->model('Products_model', 'mymodel');
    }

    // get data
    function index_get($id=null) {
        $qdata      = $this->_build_query_get($id);        

        $auth       = $this->head('Auth');
        $authdt     = JWT::decode($auth, $this->config->item('jwt_key'));

        // $dt     = $this->mymodel->get($qdata->where, $qdata->like, $qdata->orderby, $qdata->limit, $qdata->offset);
        $dt     = $this->mymodel->getCalc($qdata->where, $qdata->like, $qdata->orderby, $qdata->limit, $qdata->offset);
        $dtTot  = $this->mymodel->getCount($qdata->where, $qdata->like, $qdata->orderby);
        // $rdt    = $this->_build_return($dt);
        $rdt    = $this->_build_return_products($dt);

        if ($dt) {
            $resp = array(
                'code'=>$this->config->item('success_code'),
                'message'=>$this->config->item('success_msg'),
                'total'=>$dtTot,
                'page'=>$rdt->page,
                'limit'=>$rdt->limit,
                'offset'=>$rdt->offset,
                'data'=>$rdt->data,
                'subtotprice'=>number_format($rdt->subtotprice, 2, '.', ''),
                'subtottax'=>number_format($rdt->subtottax, 4, '.', ''),
                'grandtot'=>number_format($rdt->grandtot, 4, '.', '')
            );
        } else {
            $resp = array(
                'code'=>$this->config->item('nodata_code'),
                'message'=>$this->config->item('nodata_msg'),
                'total'=>$dtTot,
                'page'=>$rdt->page,
                'limit'=>$rdt->limit,
                'offset'=>$rdt->offset,
                'data'=>$rdt->data,
                'subtotprice'=>number_format($rdt->subtotprice, 2, '.', ''),
                'subtottax'=>number_format($rdt->subtottax, 4, '.', ''),
                'grandtot'=>number_format($rdt->grandtot, 4, '.', '')
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
            'taxcode' => $this->post('taxcode'),
            'price' => ($this->post('price') ? $this->post('price') : 1),
            'trash' => ($this->post('trash') ? $this->post('trash') : '0')
        );
        if ($id) {
            $data['id']         = $id;
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