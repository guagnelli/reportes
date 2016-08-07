<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Welcome_model extends CI_Model {
	public function __construct() {
        // Call the CI_Model constructor
        parent::__construct();
        $this->load->database();
    }


    /**
    * @access 	public
    * @param 	array[optional] (params=>)
    * @return 	array (total=>total de registros, columns=>nombres de los campos, data=>campos)
    *
    */
    public function listado($params=null) {
    	$resultado = array();

    	///////////////////// Iniciar almacenado de parámetros en cache /////////////////////////
    	$this->db->start_cache();
    	if(isset($params['search']) && !empty($params['search'])){
    		$this->db->like('title', $params['search']);
    	}
    	if(isset($params['order']) && !empty($params['order'])){
    		$tipo_orden = (isset($params['order_type']) && !empty($params['order_type'])) ? $params['order_type'] : "ASC";
    		$this->db->order_by($params['order'], $tipo_orden);
    	}
    	$this->db->stop_cache();
    	/////////////////////// Fin almacenado de parámetros en cache ///////////////////////////
    	
    	$num_rows = $this->db->count_all_results('usuario'); //Obtener el total de registros
    	
    	if(isset($params['per_page']) && isset($params['current_row'])){ //Establecer límite definido para paginación
    		$this->db->limit($params['per_page'], $params['current_row']);
    	}
    	
        $query = $this->db->get('usuario'); //Obtener conjunto de registros

	    $resultado['total']=$num_rows;
	    $resultado['columns']=$query->list_fields();
	    $resultado['data']=$query->result_array();

	    $query->free_result(); //Libera la memoria

        return $resultado;
    }

    /*public function listado($params=null) {
    	$resultado = array();

    	$this->db->start_cache();
    	if(isset($params['search']) && !empty($params['search'])){
    		$this->db->like('title', $params['search']);
    	}
    	if(isset($params['order']) && !empty($params['order'])){
    		$tipo_orden = (isset($params['order_type']) && !empty($params['order_type'])) ? $params['order_type'] : "ASC";
    		$this->db->order_by($params['order'], $tipo_orden);
    	}
    	$this->db->stop_cache();

    	$num_rows = $this->db->count_all_results('node'); //
    	//echo $this->db->last_query()."<br>";
    	
    	$this->db->limit($params['per_page'], $params['current_row']);
        $query = $this->db->get('node');
        //echo $this->db->last_query();
        
        //if($params['current_row']>1){
	    //    $query->data_seek($params['current_row']);
	    //    $row = $query->unbuffered_row();
	    //}

	    $resultado['total']=$num_rows;
	    $resultado['columns']=$query->list_fields();
	    $resultado['data']=$query->result_array();

	    $query->free_result();

        return $resultado;
    }*/
}
