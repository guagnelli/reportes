<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Idioma_model extends CI_Model {
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
    public function listado_idiomas($params=array()) {
    	$resultado = array();

    	///////////////////// Iniciar almacenado de parámetros en cache /////////////////////////
    	//$this->db->start_cache();
        if(array_key_exists('conditions', $params)){
            $this->db->where($params['conditions']);
        }
        $this->db->order_by('lang_idioma', 'ASC');
    	//$this->db->stop_cache();
    	/////////////////////// Fin almacenado de parámetros en cache ///////////////////////////
    	
        $query = $this->db->get('idioma'); //Obtener conjunto de registros

	    //$resultado['total']=$this->db->count_all_results('idioma'); //Obtener el total de registros
	    $resultado['data']=$query->result_array();

	    $query->free_result(); //Libera la memoria

        return $resultado;
    }
}
