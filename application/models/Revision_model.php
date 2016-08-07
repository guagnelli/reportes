<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Revision_model extends CI_Model {
	public function __construct() {
        // Call the CI_Model constructor
        parent::__construct();
        $this->load->database();
    }

    /**
    * @access   public
    * @param    array[optional] (params=>)
    * @return   array (total=>total de registros, data=>registros)
    *
    */
    public function listado_revision($params=array()) {
        $resultado = array();

        if(array_key_exists('conditions', $params)){
            $this->db->where($params['conditions']);
        }
        
        $query = $this->db->get('revision'); //Obtener conjunto de registros
        //pr($this->db->last_query());
        $resultado=$query->result_array();

        $query->free_result(); //Libera la memoria

        return $resultado;
    }

    public function listado_validacion($params=array()){
        $resultado = array();

        if(array_key_exists('fields', $params)){
            $this->db->select($params['fields']);
        }
        if(array_key_exists('conditions', $params)){
            $this->db->where($params['conditions']);
        }

        if(array_key_exists('order', $params)){
            $this->db->order_by($params['order']);
        }

        $this->db->join('estado_val', 'validacion.ev_id=estado_val.ev_id', 'left');
        $this->db->join('criterio', 'validacion.cri_id=criterio.cri_id AND validacion.bsq_id=criterio.bsq_id', 'left');
        $this->db->join('busqueda', 'validacion.bsq_id=busqueda.bsq_id', 'left');
        
        $query = $this->db->get('validacion'); //Obtener conjunto de registros
        //pr($this->db->last_query());
        $resultado=$query->result_array();

        $query->free_result(); //Libera la memoria

        return $resultado;
    }

    public function listado_validacion_validada($params=array()){
        $this->db->start_cache();
        if(array_key_exists('conditions', $params)){
            $this->db->where($params['conditions']);
        }
        $this->db->stop_cache();

        $this->db->select("MAX(pd_validacion.val_id)"); //////Validación
        $this->db->group_by(array("validacion.cri_id","validacion.bsq_id"));
        $subquery = $this->db->get_compiled_select('validacion');

        $resultado = array();

        if(array_key_exists('fields', $params)){
            $this->db->select($params['fields']);
        }
        if(array_key_exists('order', $params)){
            $this->db->order_by($params['order']);
        }

        $this->db->where("pd_validacion.val_id IN (".$subquery.")");

        $this->db->join('estado_val', 'validacion.ev_id=estado_val.ev_id', 'left');
        $this->db->join('criterio', 'validacion.cri_id=criterio.cri_id AND validacion.bsq_id=criterio.bsq_id', 'left');
        $this->db->join('busqueda', 'validacion.bsq_id=busqueda.bsq_id', 'left');
        
        $query = $this->db->get('validacion'); //Obtener conjunto de registros
        //pr($this->db->last_query());
        $resultado=$query->result_array();

        $this->db->flush_cache();
        $query->free_result(); //Libera la memoria

        return $resultado;
    }

    public function update_revision_estado($rev_id, $revision){
        $resultado = array('result'=>TRUE, 'msg'=>'', 'data'=>null);
        $this->db->trans_begin(); //Definir inicio de transacción

        $this->db->where('rev_id', $rev_id);
        $this->db->update('revision', $revision);
        
        if($this->db->trans_status() === FALSE){
            $this->db->trans_rollback();
            $resultado['result'] = FALSE;
            $resultado['msg'] = "Ocurrió un error durante el guardado, por favor inténtelo de nuevo más tarde.";
        } else {
            $this->db->trans_commit();
            $resultado['msg'] = "Actualización completada";
            $resultado['result'] = TRUE;
        }

        return $resultado;
    }
    /*
    public function validacion_pendiente($params=array()){
        $resultado = array();
        if(array_key_exists('conditions', $params)){
            $this->db->where($params['conditions']);
        }
        
        $query = $this->db->get('validacion');
        //pr($this->db->last_query());
        $resultado=$query->result_array();
        $query->free_result();
        return $resultado;
    }*/

    public function get_cobertura($params=array()){
        $resultado = array();
        if(array_key_exists('conditions', $params)){
            $this->db->where($params['conditions']);
        }
        $this->db->order_by('cv_tipo', 'ASC');

        $query = $this->db->get('cobertura_val');
        //pr($this->db->last_query());
        $resultado=$query->result_array();
        $query->free_result();
        return $resultado;
    }

    public function get_busqueda($params=array()){
        $resultado = array();
        if(array_key_exists('conditions', $params)){
            $this->db->where($params['conditions']);
        }
        $this->db->order_by('bsq_nombre', 'ASC');

        $query = $this->db->get('busqueda');
        //pr($this->db->last_query());
        $resultado=$query->result_array();
        $query->free_result();
        return $resultado;
    }

    public function get_criterio($params=array()){
        $resultado = array();
        if(array_key_exists('conditions', $params)){
            $this->db->where($params['conditions']);
        }
        $this->db->order_by('cri_nombre', 'ASC');

        $query = $this->db->get('criterio');
        //pr($this->db->last_query());
        $resultado=$query->result_array();
        $query->free_result();
        return $resultado;
    }

    public function get_estado_validacion($params=array()){
        $resultado = array();
        if(array_key_exists('conditions', $params)){
            $this->db->where($params['conditions']);
        }
        $this->db->order_by('est_nombre', 'ASC');

        $query = $this->db->get('estado_val');
        //pr($this->db->last_query());
        $resultado=$query->result_array();
        $query->free_result();
        return $resultado;
    }

    public function get_criterio_busqueda($params=array()){
        $resultado = array();
        if(array_key_exists('fields', $params)){
            $this->db->select($params['fields']);
        }
        if(array_key_exists('conditions', $params)){
            $this->db->where($params['conditions']);
        }
        $this->db->order_by('bsq_orden', 'ASC');
        $this->db->order_by('cri_nombre', 'ASC');

        $this->db->join('criterio', 'criterio.bsq_id=busqueda.bsq_id', 'left');

        $query = $this->db->get('busqueda');
        //pr($this->db->last_query());
        $resultado=$query->result_array();
        $query->free_result();
        return $resultado;
    }

    /*public function get_validacion($params=array()){
        $resultado = array();
        if(array_key_exists('conditions', $params)){
            $this->db->where($params['conditions']);
        }

        $this->db->join('estado_val', 'validacion.ev_id=estado_val.ev_id', 'left');
        $this->db->join('criterio', 'validacion.cri_id=criterio.cri_id AND validacion.bsq_id=criterio.bsq_id', 'left');
        $this->db->join('busqueda', 'validacion.bsq_id=busqueda.bsq_id', 'left');

        $query = $this->db->get('validacion');
        //pr($this->db->last_query());
        $resultado=$query->result_array();
        $query->free_result();
        return $resultado;
    }*/

}
