<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Editor_comercial_model extends CI_Model {
	public function __construct() {
        parent::__construct();
        $this->load->database();
    }


    /**
    * @access 	public
    * @param 	array[optional] (params=>)
    * @return 	array (total=>total de registros, data=>registros)
    *
    */
    public function listado_editor_comercial($params=array()) {
    	$resultado = array();

        if(array_key_exists('conditions', $params)){
            $this->db->where($params['conditions']);
        }
        $this->db->order_by('ec_nombre', 'ASC');
    	
        $query = $this->db->get('editor_comercial'); //Obtener conjunto de registros

	    //$resultado['total']=$this->db->count_all_results('idioma'); //Obtener el total de registros
	    $resultado['data']=$query->result_array();

	    $query->free_result(); //Libera la memoria

        return $resultado;
    }
}
