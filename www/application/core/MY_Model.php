<?php
class MY_Model extends CI_Model
{
    function __construct()
    {
        parent::__construct();    
    }
    /**
     * shortcut function to fetch data from table on one call
     */
    function selRow($table, $where = '', $orderby = '', $is_echo=false, $Master_connection=false) {
        
        if ($where)
            $this->where($where);
        if ($orderby)
            $this->orderby($orderby);
        $sql = $this->table($table)->create_query();
        if($is_echo) echo $sql;
        return $this->query($sql)->fetchrow();
        
    }
    
    /**
	 * Get array set for further use in creating combo option list
	 * @access	public
	 * @return	array
     * @return array
     * ex : array(
     *      1 => 'nama1',
     *      2 => 'nama 2'
     * )
	 */
    function get_comboRow($table,  $primary_id, $name_id, $selItem='', $where='', $orderby='', $Master_connection=false, $isEcho=false)
    {
    	$dt = array();
    	$orderby = ($orderby) ? $orderby : $name_id;
        $row = $this->selRow($table, $where, $orderby, $isEcho, $Master_connection);
        foreach($row as $r){
            if(is_array($selItem)){
                $dt[$r[$primary_id]] = in_array($r[$primary_id], ($selItem)) ? "#".$r[$name_id] : $r[$name_id];
            }else{
                $dt[$r[$primary_id]] = ($selItem==$r[$primary_id]) ? "#".$r[$name_id] : $r[$name_id];
            }
        }
        return $dt;
    }
    
    function get_rowset_idsorted($table, $primary, $where='', $order=array(), $Master_connection=false)
    {
        $row = $this->selRow($table, $where, $order, false, $Master_connection);
        $data = array();
        foreach($row as $r){
            $data[$r[$primary]] = $r;
        }
        
        return $data;
    }
    
    function delRow($table, $where,$Master_connection=false) {
    	if($Master_connection)
    	{
    		return $this->delete($table, $where);
    	}
    	else
    	{
    		return $this->delete($table, $where);	
    	}
        
    }

	function idAsIndex($data) {
		$ret = null;
		if (is_array($data) && count($data) > 0) 
		{			
			foreach ($data as $key=>$value) 
			{
				$ret[$value->id] = $value;
			}
		}
		return ($ret);
    }

    function idPropAsIndex($data) {
        $ret = null;
        if (is_array($data) && count($data) > 0) 
        {           
            foreach ($data as $key=>$value) 
            {
                $ret[$value->provinsi_id] = $value;
            }
        }
        return ($ret);
    }

    function idKotaAsIndex($data) {
        $ret = null;
        if (is_array($data) && count($data) > 0) 
        {           
            foreach ($data as $key=>$value) 
            {
                $ret[$value->kota_id] = $value;
            }
        }
        return ($ret);
    }

    function idPropKotaAsIndex($data) {
        $ret = null;
        if (is_array($data) && count($data) > 0) 
        {           
            foreach ($data as $key=>$value) 
            {
                $ret[$value->provinsi_id][] = $value;
            }
        }
        return ($ret);
    }

    function prodIdAsIndex($data) {
        $ret = null;
        if (is_array($data) && count($data) > 0) 
        {           
            foreach ($data as $key=>$value) 
            {
                $ret[$value->produk_id] = $value;
            }
        }
        return ($ret);
    }

    function tcodeAsIndex($data) {
        $ret = null;
        if (is_array($data) && count($data) > 0) 
        {           
            foreach ($data as $key=>$value) 
            {
                $ret[$value->tradercode] = $value;
            }
        }
        return ($ret);
    }

    function grouptree($data)
    {
        $ret = null;
        if (is_array($data) && count($data) > 0) 
        {
            $lvl1   = array();
            $lvl2   = array();
            $lvl3   = array();

            foreach ($data as $key=>$value) 
            {
                $value  = (object) $value;

                if ($value->parent_id) 
                {
                    $parent = (object) $data[$value->parent_id];
                    if ($parent->parent_id) //level3
                    {
                        $lvl3[$value->parent_id][$value->id] = $value;
                    } else { //level2
                        $lvl2[$value->parent_id][$value->id] = $value;
                    }                    
                } else 
                {
                    $lvl1[$value->id] = $value;
                }
            }

            $ret = array('all'=>$data, 'lvl1'=>$lvl1, 'lvl2'=>$lvl2, 'lvl3'=>$lvl3);
        }

        //echo('<pre>');print_r(json_encode($ret));echo('</pre>');

        return ($ret);
    }

    function buildQuery() {
        
    }
}

/* eof */