<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Buscador_model extends CI_Model {    
        //Aquí definimos los nombres que harán referencia a las bases de datos 
        var $db1 = null;
        var $DB2 = null;

        public function __construct() {
                // Call the CI_Model constructor
                parent::__construct();

                //$this->load->database();        
                $this->db1 = $this->load->database('buscador', true);        
                $this->DB2 = $this->load->database('bitacora', true);
        }

        /**
        * @access 	public
        * @param 	array[optional] (params=>)
        * @return 	array (total=>total de registros, columns=>nombres de los campos, data=>campos)
        *
        */
        public function listado($params=null) {
                $resultado = array();
                
                $guarda_busqueda = false;
                
                $this->db1->select("GROUP_CONCAT(pd_base_datos.bd_id)"); //////Base de datos
                $this->db1->join('pub_bd', 'pub_bd.bd_id = base_datos.bd_id');
                $this->db1->where('pub_bd.pub_id=publicacion.pub_id');
                $base_datos = $this->db1->get_compiled_select('base_datos');

                $this->db1->select("GROUP_CONCAT(pd_tema.t_id)"); //////Temáticas
                $this->db1->join('pub_tematica', 'pub_tematica.t_id = tema.t_id');
                $this->db1->where('pub_tematica.pub_id=publicacion.pub_id');
                $tematica = $this->db1->get_compiled_select('tema');

                $this->db1->select("GROUP_CONCAT(t_titulo SEPARATOR ', ')"); ///////Título(s)
                $this->db1->where('titulo.pub_id=publicacion.pub_id');
                $titulo = $this->db1->get_compiled_select('titulo');

                //pr($params);
                ///////////////////// Iniciar almacenado de parámetros en cache /////////////////////////
                $this->db1->start_cache();
                $this->db1->select('publicacion.pub_id');
                
                if(exist_and_not_null($params['titulo'])){ //////Títulos
                        $this->db1->like('t_titulo', $params['titulo']);
                        $guarda_busqueda = true;
                }
                if(exist_and_not_null($params['base_datos'])){
                    $guarda_busqueda = true; 
                    $this->db1->where('pub_bd.bd_id',$params['base_datos']);                                                                        
                }
                if(exist_and_not_null($params['tematica'])){
                        $guarda_busqueda = true; 
                        $this->db1->where('pub_tematica.t_id',$params['tematica']);                                                
                }
                
                if(exist_and_not_null($params['issn']))
                    {
                        $guarda_busqueda = true; 
                        $this->db1->group_start();
                        $this->db1->or_like('publicacion.pub_issn',$params['issn']);
                        $this->db1->or_like('publicacion.pub_issnl',$params['issn']);
                        $this->db1->or_like('publicacion.pub_issne',$params['issn']);
                        $this->db1->or_like('publicacion.pub_issnp',$params['issn']);
                        $this->db1->group_end();
                                                
                }

                if(exist_and_not_null($params['idioma']))
                    { 
                        $guarda_busqueda = true;                        
                        $this->db1->where('publicacion.lang_id',$params['idioma']);                                                
                }
                
                //pr($params);
                $this->db1->join('idioma', 'publicacion.lang_id=idioma.lang_id AND idioma.lang_activo=1', 'left');
                $this->db1->join('pub_bd', 'publicacion.pub_id=pub_bd.pub_id', 'left');
                $this->db1->join('pub_tematica', 'publicacion.pub_id=pub_tematica.pub_id', 'left');
                $this->db1->join('titulo', 'publicacion.pub_id=titulo.pub_id', 'left');
                $this->db1->group_by("publicacion.pub_id");

                $this->db1->stop_cache();
                /////////////////////// Fin almacenado de parámetros en cache ///////////////////////////                               
                    
                ///////////////////////////// Obtener número de registros ///////////////////////////////
                $nr = $this->db1->get_compiled_select('publicacion'); //Obtener el total de registros
                $num_rows = $this->db1->query("SELECT count(*) AS total FROM (".$nr.") AS temp")->result();
                //pr($this->db1->last_query());
                /////////////////////////////// FIN número de registros /////////////////////////////////
                $busqueda = array('publicacion.pub_issn AS issn', '('.$titulo.') AS titulo', '('.$base_datos.') AS base_datos', '('.$tematica.') AS tematica', 'idioma.lang_idioma AS idioma');

                $this->db1->select($busqueda);
                if(isset($params['order']) && !empty($params['order'])){
                        $tipo_orden = (isset($params['order_type']) && !empty($params['order_type'])) ? $params['order_type'] : "ASC";
                        $this->db1->order_by($params['order'], $tipo_orden);
                }

                if(isset($params['per_page']) && isset($params['current_row'])){ //Establecer límite definido para paginación
                        $this->db1->limit($params['per_page'], $params['current_row']);
                }

                $query = $this->db1->get('publicacion'); //Obtener conjunto de registros
                //pr($this->db1->last_query());                                  

                $resultado['total']=$num_rows[0]->total;
                $resultado['columns']=$query->list_fields();
                $resultado['data']=$query->result_array();
                //pr($resultado['data']);
                $this->db1->flush_cache();
                $query->free_result(); //Libera la memoria                                
                   /*   */         
                if(isset($guarda_busqueda) && $guarda_busqueda == true){                    
                    $this->guarda_busqueda($params);                                        
                }
                
                return $resultado;
        }
        
       

        public function get_idioma($idioma_id = null) {
            $query = $this->db1->select('lang_idioma')->where('lang_id', $idioma_id)->get('idioma');
            
            $row = $query->row_array();
            $resultado = null;
            if (isset($row))
            {
                    $resultado = $row['lang_idioma'];
            }
            
            return $resultado;
        }
/**/
        public function get_base_datos($base_datos = null) {            
            
            $query = $this->db1->select('bd_nombre')->where('bd_id', $base_datos )->get('base_datos');
            
            $row = $query->row_array();
            $resultado = null;
            if (isset($row))
            {
                    $resultado = $row['bd_nombre'];
            }
            
            return $resultado;
        }

        public function get_tematica($tematica_id = null) {
            $query = $this->db1->select('t_nombre')->where('t_id', $tematica_id)->get('tema');
            
            $row = $query->row_array();
            $resultado = null;
            if (isset($row))
            {
                    $resultado = $row['t_nombre'];
            }
            
            return $resultado;
        }
        
        public function guarda_busqueda($params = null){             
            $datos_bitacora = array('ip_usuario' => $params['ip_usuario']);
                if(exist_and_not_null($params['titulo'])){
                    $datos_bitacora['titulo'] =  $params['titulo'];                        
                }
                if(exist_and_not_null($params['base_datos'])){
                    $datos_bitacora['bd_id'] =  $params['base_datos'];
                    $datos_bitacora['bd_nombre'] = $params['name_db'];                                                                                                             
                }
                if(exist_and_not_null($params['tematica'])){
                    $datos_bitacora['tem_id'] =  $params['tematica'];
                    $datos_bitacora['tem_nombre']=  $this->get_tematica($params['tematica']); 
                }                
                if(exist_and_not_null($params['issn'])){ 
                    $datos_bitacora['issn'] =  $params['issn'];                                                                        
                }
                if(exist_and_not_null($params['idioma'])){                     
                    $datos_bitacora['lang_id'] =  $params['idioma'];
                    $datos_bitacora['lang_nombre']=  $this->get_idioma($params['idioma']);                                            
                }                                
                $this->DB2->set($datos_bitacora);
                $this->DB2->insert('bitacora_buscador');                                
        }
/*
        public function guardar_clic($data = null){
                $resultado = array('result'=>TRUE, 'msg'=>'', 'data'=>null);
                $this->db2->trans_begin(); //Definir inicio de transacción


                $array = array(
                        'name' => $name,
                        'title' => $title,
                        'status' => $status
                    );

                $this->db2->set($array);

                $this->db2->insert('bitacora_clic');
                $insert_id = $this->db2->insert_id(); //Inserción de validación

                if($this->db2->trans_status() === FALSE){
                        $this->db2->trans_rollback();
                        $resultado['result'] = FALSE;
                        $resultado['msg'] = "";
                } else {
                        $this->db2->trans_commit();
                        $resultado['msg'] = "";
                        $resultado['result'] = TRUE;
                }

                return $resultado;
        }
*/
        
        public function listado_base_datos($params=array()) {
    	$resultado = array();

        if(array_key_exists('conditions', $params)){
            $this->db1->where($params['conditions']);
        }
        $this->db1->order_by('bd_nombre', 'ASC');
    	/*
         * $busqueda = array('base_datos.bd_id', 'base_datos.bd_nombre','pub_bd.pub_id','pub_bd.pub_bd_url');
    	$this->db1->select($busqueda);
         * 
         */
        $query = $this->db1->get('base_datos'); //Obtener conjunto de registros
        //pr($this->db1->last_query());
	    //$resultado['total']=$this->db1->count_all_results('idioma'); //Obtener el total de registros
	    $resultado['data']=$query->result_array();

	    $query->free_result(); //Libera la memoria

        return $resultado;
    }
    
    public function listado_link_base_datos($params=array()) {
    	$resultado = array();
        
        if(array_key_exists('conditions', $params)){
            $this->db1->join('pub_bd', 'pub_bd.bd_id=base_datos.bd_id AND '.$params['conditions']);
            //$this->db1->where($params['conditions']);
        }
                
        $this->db1->order_by('base_datos.bd_nombre', 'ASC');
        $busqueda = array('base_datos.bd_id','base_datos.bd_activo', 'base_datos.bd_nombre','pub_bd.pub_id','pub_bd.pub_bd_url');
    	$this->db1->select($busqueda);
        $query = $this->db1->get('base_datos'); //Obtener conjunto de registros
        //pr($this->db1->last_query());
	    //$resultado['total']=$this->db1->count_all_results('idioma'); //Obtener el total de registros
	    $resultado['data']=$query->result_array();

	    $query->free_result(); //Libera la memoria

        return $resultado;
    }

    public function get_pub_bd($params=array()){
        $resultado = array();

        if(array_key_exists('conditions', $params)){
            $this->db1->where($params['conditions']);
        }

        $this->db1->join('base_datos', 'pub_bd.bd_id=base_datos.bd_id AND base_datos.bd_activo=1', 'left');
        $query = $this->db1->get('pub_bd');
        //pr($this->db1->last_query());
        $resultado=$query->result_array();

        $query->free_result(); //Libera la memoria

        return $resultado;
    }
    
    public function get_pub_tematica($params=array()){
        $resultado = array();

        if(array_key_exists('conditions', $params)){
            $this->db1->where($params['conditions']);
        }
        $this->db1->order_by('tema.t_nombre', 'ASC');

        $this->db1->join('tema', 'tema.t_id=pub_tematica.t_id AND tema.t_activo=1', 'left');
        $query = $this->db1->get('pub_tematica');
        //pr($this->db1->last_query());
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
            $this->db1->where($params['conditions']);
        }
        $this->db1->order_by('t_nombre', 'ASC');
        
        $query = $this->db1->get('tema'); //Obtener conjunto de registros
        //pr($this->db1->last_query());
        //$resultado['total']=$this->db1->count_all_results('idioma'); //Obtener el total de registros
        $resultado['data']=$query->result_array();

        $query->free_result(); //Libera la memoria

        return $resultado;
    }
    
    public function tematica_existente($tema) {
        $this->db1->where('t_id', $tema);
        $query = $this->db1->get('tema');        
        if($query->num_rows() > 0){
            return true;
        }else{            
            return false;
        }
    }
    
    public function listado_idiomas($params=array()) {
    	$resultado = array();

    	///////////////////// Iniciar almacenado de parámetros en cache /////////////////////////
    	//$this->db1->start_cache();
        if(array_key_exists('conditions', $params)){
            $this->db1->where($params['conditions']);
        }
        $this->db1->order_by('lang_idioma', 'ASC');
    	//$this->db1->stop_cache();
    	/////////////////////// Fin almacenado de parámetros en cache ///////////////////////////
    	
        $query = $this->db1->get('idioma'); //Obtener conjunto de registros

	    //$resultado['total']=$this->db1->count_all_results('idioma'); //Obtener el total de registros
	    $resultado['data']=$query->result_array();

	    $query->free_result(); //Libera la memoria

        return $resultado;
    }
}

//SELECT pbd.pub_id, pbd.pub_bd_url, bd.bd_nombre FROM pd_pub_bd pbd INNER JOIN pd_base_datos bd ON bd.bd_id=pbd.bd_id