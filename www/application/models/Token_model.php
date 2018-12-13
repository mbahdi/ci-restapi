<?php
if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Token_model extends MY_Model {

    var $table = 'appIdentifier';

    public function __construct()
    {
        parent::__construct();
    }

    public function auth($appid, $appsec)
    {
        $this->db->where('appid', $appid);
        $this->db->where('appsecret', $appsec);
        $this->db->where('trash', '0');
        $query = $this->db->get($this->table);

        if ($query->num_rows() > 0) {
            return $query->result();
        }
        return false;            
    }

    function get($where=null, $like=null, $order_by= null, $limit=0, $offset=0)
    {
        if (!is_null($where))
        {
            $this->db->where($where);
        }
        if (!is_null($like))
        {
            $this->db->like($like);
        }
        if (!is_null($order_by))
        {
            $this->db->order_by($order_by);
        }
        if ($limit)
        {
            $this->db->limit($limit, $offset);
        }

        $this->db->from($this->table);
        $query = $this->db->get();
        if ($query->num_rows() > 0) {
            return $query->result();
        }
        return false;
    }

    // save
    function save($data)
    {
        if (isset($data->id)) //update
        {
            $this->db->where("id", $data->id);
            if (!$this->db->update($this->table, $data)) {
                $cIns = & get_instance();

                $resp = array(
                    'code'=>$this->config->item('savefailed_code'),
                    'message'=>$this->db->error()['message']
                );

                $cIns->response($resp, 200);
            } else {
                return $this->get(array("id"=>$data->id));
            }
        } else //insert
        {
            if (!$this->db->insert($this->table, $data)) {
                // print_r($this->db->error());
                $cIns = & get_instance();

                $resp = array(
                    'code'=>$this->config->item('savefailed_code'),
                    'message'=>$this->db->error()['message']
                );

                $cIns->response($resp, 200);
            } else {
                $last_id = $this->db->insert_id();
                return $this->get(array("id"=>$last_id));
                // return $last_id;
            }
        }
    }

    // delete
    function delete($id) 
    {
        $this->db->delete($this->table, array('id'=>$id));
        if ($this->db->affected_rows()>0)
        {
            return $id;
            // return true;
        } else
        {
            return false;
        }
    }

    // trash
    function trash($id) 
    {
        $this->db->where("id", $id);
        if ($this->db->update($this->table, array('trash'=>1)))
        {
            return $id;
            // return true;
        } else
        {
            return false;
        }
    }
}

?>