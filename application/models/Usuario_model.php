<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Usuario_model extends CI_Model {
	public function __construct() {
        // Call the CI_Model constructor
        parent::__construct();
        $this->load->database();
    }


    /**
    * @access 	public
    * @param 	array[optional] (params=>)
    * @return 	array (total=>total de registros, data=>registros)
    *
    */
    public function listado_usuarios($params=array()) {
    	$resultado = array();
        if(array_key_exists('fields', $params)){
            $this->db->select($params['fields']);
        }
        if(array_key_exists('conditions', $params)){
            $this->db->where($params['conditions']);
        }
        if(array_key_exists('order', $params)){
            $this->db->order_by($params['order']['field'], $params['order']['type']);
        }
    	
        $query = $this->db->get('usuario'); //Obtener conjunto de registros

	    //$resultado['total']=$this->db->count_all_results('idioma'); //Obtener el total de registros
	    $resultado['data']=$query->result_array();
        
	    $query->free_result(); //Libera la memoria

        return $resultado;
    }
    
    public function usuario_existente($usuario) {
        $this->db->where('usr_matricula', $usuario);
        $query = $this->db->get('usuario');        
        if($query->num_rows() > 0){
            return true;
        }else{            
            return false;
        }
    }
    
}
