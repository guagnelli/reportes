<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Tematica_model extends CI_Model {
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
    public function get_pub_tematica($params=array()){
        $resultado = array();

        if(array_key_exists('conditions', $params)){
            $this->db->where($params['conditions']);
        }
        $this->db->order_by('tema.t_nombre', 'ASC');

        $this->db->join('tema', 'tema.t_id=pub_tematica.t_id AND tema.t_activo=1', 'left');
        $query = $this->db->get('pub_tematica');
        //pr($this->db->last_query());
        $resultado=$query->result_array();

        $query->free_result(); //Libera la memoria

        return $resultado;
    }

    /**
    * @access   public
    * @param    array[optional] (params=>)
    * @return   array (total=>total de registros, data=>registros)
    *
    */
    public function listado_tema($params=array()) {
        $resultado = array();

        if(array_key_exists('conditions', $params)){
            $this->db->where($params['conditions']);
        }
        $this->db->order_by('t_nombre', 'ASC');
        
        $query = $this->db->get('tema'); //Obtener conjunto de registros
        //pr($this->db->last_query());
        //$resultado['total']=$this->db->count_all_results('idioma'); //Obtener el total de registros
        $resultado['data']=$query->result_array();

        $query->free_result(); //Libera la memoria

        return $resultado;
    }
    
    public function tematica_existente($tema) {
        $this->db->where('t_id', $tema);
        $query = $this->db->get('tema');        
        if($query->num_rows() > 0){
            return true;
        }else{            
            return false;
        }
    }
}
