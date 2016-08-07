<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Base_datos_model extends CI_Model {
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
    public function listado_base_datos($params=array()) {
    	$resultado = array();

        if(array_key_exists('conditions', $params)){
            $this->db->where($params['conditions']);
        }
        $this->db->order_by('bd_nombre', 'ASC');
    	/*
         * $busqueda = array('base_datos.bd_id', 'base_datos.bd_nombre','pub_bd.pub_id','pub_bd.pub_bd_url');
    	$this->db1->select($busqueda);
         * 
         */
        $query = $this->db->get('base_datos'); //Obtener conjunto de registros
        //pr($this->db->last_query());
	    //$resultado['total']=$this->db->count_all_results('idioma'); //Obtener el total de registros
	    $resultado['data']=$query->result_array();

	    $query->free_result(); //Libera la memoria

        return $resultado;
    }
    
    public function listado_link_base_datos($params=array()) {
    	$resultado = array();
        
        if(array_key_exists('conditions', $params)){
            $this->db->join('pub_bd', 'pub_bd.bd_id=base_datos.bd_id AND '.$params['conditions']);
            //$this->db->where($params['conditions']);
        }
                
        $this->db->order_by('base_datos.bd_nombre', 'ASC');
        $busqueda = array('base_datos.bd_id','base_datos.bd_activo', 'base_datos.bd_nombre','pub_bd.pub_id','pub_bd.pub_bd_url');
    	$this->db->select($busqueda);
        $query = $this->db->get('base_datos'); //Obtener conjunto de registros
        //pr($this->db->last_query());
	    //$resultado['total']=$this->db->count_all_results('idioma'); //Obtener el total de registros
	    $resultado['data']=$query->result_array();

	    $query->free_result(); //Libera la memoria

        return $resultado;
    }

    public function get_pub_bd($params=array()){
        $resultado = array();

        if(array_key_exists('conditions', $params)){
            $this->db->where($params['conditions']);
        }

        $this->db->join('base_datos', 'pub_bd.bd_id=base_datos.bd_id AND base_datos.bd_activo=1', 'left');
        $query = $this->db->get('pub_bd');
        //pr($this->db->last_query());
        $resultado=$query->result_array();

        $query->free_result(); //Libera la memoria

        return $resultado;
    }
}
