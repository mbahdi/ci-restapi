<?php
if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Users_model extends MY_Model {

    var $table 	= 'users';
    var $table2	= 'roles';

    public function __construct()
    {
        parent::__construct();
    }

    // get
    function get($where=null, $like=null, $order_by= null, $limit=0, $offset=0, $allowCred=0)
	{
		if (!is_null($where))
		{
			if (isset($where['trash'])) {
				$where['users.trash'] = $where['trash'];
				unset($where['trash']);
			}
			if (isset($where['id'])) {
				$where['users.id'] = $where['id'];
				unset($where['id']);
			}
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

		if ($allowCred) {
			$this->db->select('users.*, roles.code as roleCode, roles.name as roleName');
		} else {
			$this->db->select('users.id, users.username, users.pp, users.role, users.trash, users.createdAt, users.createdBy, users.updatedAt, users.updatedBy, users.lastlogin, users.lastip, roles.code as roleCode, roles.name as roleName');			
		}
				
		$this->db->from($this->table);
		$this->db->join($this->table2, $this->table.'.role='.$this->table2.'.id');

		$query = $this->db->get();
		if ($query->num_rows() > 0) {
            return $query->result();
        }
        return false;
	}

	// count all datas 
    function getCount($where=null, $like=null, $order_by= null)
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

        $this->db->from($this->table);
        return $this->db->count_all_results();
    }

	// save
	function save($data)
	{
		if (isset($data->id)) //update
		{
			$this->db->where("id", $data->id);
			// $count = $this->db->count_all_results();
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
			// $count = $this->db->count_all_results();
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

	// refresh token 
	function reToken($id)
	{	
		$sql = "select a.*, b.code as roleCode, b.name as roleName from ".$this->table." a, ".$this->table2." b where a.id='".$id."' and a.trash='0' and a.role=b.id LIMIT 1";
		$query = $this->db->query($sql);
		if($query->num_rows() > 0)
		{
			return ($query->row());
		}

		return (false);
	}

	// LOGIN
	function login($username, $password)
	{	
		$sql = "select a.*, b.code as roleCode, b.name as roleName from ".$this->table." a, ".$this->table2." b where a.username='".$username."' and a.trash='0' and a.role=b.id LIMIT 1";
		$query = $this->db->query($sql);
		if($query->num_rows() > 0)
		{
			foreach ($query->result() as $row)   
			{
				if (md5(''.md5(''.$password.'').$row->salt.'') == $row->password) 
				{
					$this->db->update($this->table, array('lastlogin' => date('Y-m-d H:i:s'), 'lastip'=>$this->input->ip_address()), array('id' => $row->id));
					if ($this->db->affected_rows()>0)
					{
						return ($query->row()); 
					} else
					{
						return false;
					}					
				} else 
				{
					return (false);
				}
			}
		}
		return (false);
	}
}

?>