<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Publicacion_model extends CI_Model {
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
    	
        $this->db->select("GROUP_CONCAT(pd_base_datos.bd_id)"); //////Base de datos
        $this->db->join('pub_bd', 'pub_bd.bd_id = base_datos.bd_id');
        $this->db->where('pub_bd.pub_id=publicacion.pub_id');
        $base_datos = $this->db->get_compiled_select('base_datos');

        $this->db->select("GROUP_CONCAT(pd_tema.t_id)"); //////Temáticas
        $this->db->join('pub_tematica', 'pub_tematica.t_id = tema.t_id');
        $this->db->where('pub_tematica.pub_id=publicacion.pub_id');
        $tematica = $this->db->get_compiled_select('tema');
        
        $this->db->select("GROUP_CONCAT(t_titulo SEPARATOR ', ')"); ///////Título(s)
        $this->db->where('titulo.pub_id=publicacion.pub_id');
        $titulo = $this->db->get_compiled_select('titulo');

        $this->db->select(" CONCAT(usr_nombre, ' ', usr_paterno, ' ', usr_materno, '') AS nombre "); ///////Responsable
        $this->db->where('usuario.usr_matricula=publicacion.responsable_id');
        $responsable = $this->db->get_compiled_select('usuario');

        ///////////////////// Iniciar almacenado de parámetros en cache /////////////////////////
    	$this->db->start_cache();
        $this->db->select('publicacion.pub_id');

        if(exist_and_not_null($params['titulo'])){ //////Títulos
            $this->db->like('t_titulo', $params['titulo']);
        }
        if(exist_and_not_null($params['base_datos'])){ ///Bases de datos
            $this->db->where('pub_bd.bd_id',$params['base_datos']);
        }
        if(exist_and_not_null($params['tematica'])){ ///Temática
            $this->db->where('pub_tematica.t_id',$params['tematica']);
        }
        if(exist_and_not_null($params['responsable'])){ ///Responsable
            $this->db->where('publicacion.responsable_id',$params['responsable']);
        }
        if(exist_and_not_null($params['issn'])){ ///ISSN(s)
            $this->db->group_start();
            $this->db->or_like('publicacion.pub_issn',$params['issn']);
            $this->db->or_like('publicacion.pub_issnl',$params['issn']);
            $this->db->or_like('publicacion.pub_issne',$params['issn']);
            $this->db->or_like('publicacion.pub_issnp',$params['issn']);
            $this->db->group_end();
        }
        if(exist_and_not_null($params['estado'])){ ///Estado de la solicitud
            $this->db->where('publicacion.est_pub_id',$params['estado']);
        }
        if(exist_and_not_null($params['idioma'])){ ///Idioma
            $this->db->where('publicacion.lang_id',$params['idioma']);
        }

        $this->db->join('idioma', 'publicacion.lang_id=idioma.lang_id AND idioma.lang_activo=1', 'left');
        $this->db->join('pub_bd', 'publicacion.pub_id=pub_bd.pub_id', 'left');
        $this->db->join('pub_tematica', 'publicacion.pub_id=pub_tematica.pub_id', 'left');
        $this->db->join('titulo', 'publicacion.pub_id=titulo.pub_id', 'left');
        $this->db->join('estado_publicacion', 'publicacion.est_pub_id=estado_publicacion.est_pub_id', 'left');
        $this->db->group_by("publicacion.pub_id");

    	$this->db->stop_cache();
    	/////////////////////// Fin almacenado de parámetros en cache ///////////////////////////
    	
        ///////////////////////////// Obtener número de registros ///////////////////////////////
    	$nr = $this->db->get_compiled_select('publicacion'); //Obtener el total de registros
        $num_rows = $this->db->query("SELECT count(*) AS total FROM (".$nr.") AS temp")->result();
        //pr($this->db->last_query());
        /////////////////////////////// FIN número de registros /////////////////////////////////

        $this->db->select(array('publicacion.pub_issn AS issn', '('.$titulo.') AS titulo', '('.$base_datos.') AS base_datos', '('.$tematica.') AS tematica', '('.$responsable.') AS responsable', 'idioma.lang_idioma AS idioma', 'estado_publicacion.est_pub_nombre AS estado_publicacion'));

        if(isset($params['order']) && !empty($params['order'])){
            $tipo_orden = (isset($params['order_type']) && !empty($params['order_type'])) ? $params['order_type'] : "ASC";
            $this->db->order_by($params['order'], $tipo_orden);
        }
    	
    	if(isset($params['per_page']) && isset($params['current_row'])){ //Establecer límite definido para paginación
    		$this->db->limit($params['per_page'], $params['current_row']);
    	}
    	
        $query = $this->db->get('publicacion'); //Obtener conjunto de registros
        //pr($this->db->last_query());

	    $resultado['total']=$num_rows[0]->total;
	    $resultado['columns']=$query->list_fields();
	    $resultado['data']=$query->result_array();
        //pr($resultado['data']);
        $this->db->flush_cache();
	    $query->free_result(); //Libera la memoria

        return $resultado;
    }
    
    public function listar_tematicas($params=null){
        $resultado = array();
    
        ///////////////////// Iniciar almacenado de parámetros en cache /////////////////////////
        if(exist_and_not_null($params['titulo'])){ //////Títulos
            $this->db->like('t_titulo', $params['titulo']);
        }
        if(exist_and_not_null($params['base_datos'])){ ///Bases de datos
            $this->db->where('pub_bd.bd_id',$params['base_datos']);
        }
        if(exist_and_not_null($params['tematica'])){ ///Temática
            $this->db->where('pub_tematica.t_id',$params['tematica']);
        }
        if(exist_and_not_null($params['responsable'])){ ///Responsable
            $this->db->where('publicacion.responsable_id',$params['responsable']);
        }
        if(exist_and_not_null($params['issn'])){ ///ISSN(s)
            $this->db->group_start();
            $this->db->or_like('publicacion.pub_issn',$params['issn']);
            $this->db->or_like('publicacion.pub_issnl',$params['issn']);
            $this->db->or_like('publicacion.pub_issne',$params['issn']);
            $this->db->or_like('publicacion.pub_issnp',$params['issn']);
            $this->db->group_end();
        }
        if(exist_and_not_null($params['estado'])){ ///Estado de la solicitud
            $this->db->where('publicacion.est_pub_id',$params['estado']);
        }
        if(exist_and_not_null($params['idioma'])){ ///Idioma
            $this->db->where('publicacion.lang_id',$params['idioma']);
        }
        
        $this->db->group_by("publicacion.pub_id");
        $this->db->select('publicacion.pub_id');        
        $this->db->join('idioma', 'publicacion.lang_id=idioma.lang_id AND idioma.lang_activo=1', 'left');
        $this->db->join('pub_bd', 'publicacion.pub_id=pub_bd.pub_id', 'left');
        $this->db->join('pub_tematica', 'publicacion.pub_id=pub_tematica.pub_id', 'left');
        $this->db->join('titulo', 'publicacion.pub_id=titulo.pub_id', 'left');
        $this->db->join('estado_publicacion', 'publicacion.est_pub_id=estado_publicacion.est_pub_id', 'left');
    	
    	/////////////////////// Fin almacenado de parámetros en cache ///////////////////////////
    	
        ///////////////////////////// Obtener número de registros ///////////////////////////////
    	  $nr = $this->db->get_compiled_select('publicacion'); //Obtener el total de registros
        $query = $this->db->query("SELECT `pd_tema`.*, COUNT(*) AS `total` FROM `pd_tema`
                LEFT JOIN `pd_pub_tematica` ON `pd_pub_tematica`.`t_id`=`pd_tema`.`t_id`
                WHERE `pub_id` IN(".$nr.")
                GROUP BY `pd_pub_tematica`.`t_id`");
        /////////////////////////////// FIN número de registros /////////////////////////////////
        //$this->db->select(array('publicacion.pub_issn AS issn','publicacion.responsable_id AS responsable', '('.$titulo.') AS titulo', '('.$base_datos.') AS base_datos', '('.$tematica.') AS tematica', 'idioma.lang_idioma AS idioma', 'estado_publicacion.est_pub_nombre AS estado_publicacion'));
	   $resultado['columns']=$query->list_fields();
	    $resultado['data']=$query->result_array();
		$resultado['total']=$query->num_rows();
        $this->db->flush_cache();
	    $query->free_result(); //Libera la memoria

        return $resultado;
    }

    public function guardar_publicacion($pub_id, $publicacion){
        $msg = $base_datos_error = "";
        $resultado = array('result'=>TRUE, 'msg'=>'', 'data'=>null);
        $this->db->trans_begin(); //Definir inicio de transacción
        
        //validar que existan los ID's
        $this->db->delete('titulo', array('pub_id'=>$pub_id)); //Actualización de títulos
        $this->db->insert_batch('titulo', $publicacion['titulo']);
        
        $this->db->delete('pub_tematica', array('pub_id'=>$pub_id)); //Actualización de temáticas
        $this->db->insert_batch('pub_tematica', $publicacion['tematica']);

        $this->db->delete('cobertura', array('pub_id'=>$pub_id)); //Actualización de coberturas
        $this->db->insert_batch('cobertura', $publicacion['cobertura']);

        ///Verificar que no exista validación para la BD de lo contrario no se elimina información
        foreach ($publicacion['base_datos'] as $key_bd => $base_datos) {
            $key = array_search($base_datos->bd_id, $publicacion['pub_bd']);
            if($key !== FALSE ){
                $this->db->where('pub_id', $base_datos->pub_id);
                $this->db->where('bd_id', $base_datos->bd_id);
                $this->db->update('pub_bd', $base_datos); ///Actualización de URLs
                unset($publicacion['pub_bd'][$key]);
            } else {
                $this->db->insert('pub_bd', $base_datos); //Actualización de bases de datos
            }
        }
        foreach ($publicacion['pub_bd'] as $key_bd_rev => $pub_bd_rev) {
            $this->db->where(array('revision.pub_id'=>$pub_id, 'revision.bd_id'=>$pub_bd_rev));
            $total_revision = $this->db->count_all_results('revision');
            if($total_revision>0){
                $base_datos_error = "<br>No fue posible eliminar todas las bases de datos elegidas para borrado, debido a tienen revisiones relacionadas. Verifique su estado.";
            } else {
                $this->db->delete('pub_bd', array('pub_id'=>$pub_id, 'bd_id'=>$pub_bd_rev)); //Actualización de bases de datos
            }
            //$this->get_revision(array('conditions'=>array('pub_id'=>$pub_id, 'bd_id'=>$pub_bd_rev)));
        }

        $this->db->where('pub_id', $pub_id);
        $this->db->update('publicacion', $publicacion['publicacion']);
        
        if ($this->db->trans_status() === FALSE){
            $this->db->trans_rollback();
            $resultado['result'] = FALSE;
            $resultado['msg'] = "Ocurrió un error durante el guardado, por favor intentelo de nuevo más tarde.".$base_datos_error;
        } else {
            $this->db->trans_commit();
            $resultado['msg'] = "Actualización completada".$base_datos_error;
            $resultado['result'] = TRUE;
        }

        return $resultado;
    }

    public function insertar_publicacion($pub_id, $publicacion){
        //pr($publicacion);
        $msg = $base_datos_error = "";
        $resultado = array('result'=>TRUE, 'msg'=>'', 'data'=>null);
        $this->db->trans_begin(); //Definir inicio de transacción

        $this->db->insert('publicacion', $publicacion['publicacion']); //Inserción de publicación
        $pub_id = $this->db->insert_id(); //Obtener identificador insertado

        foreach ($publicacion['titulo'] as $key_tit => $titulo) { //Insertar pub_id en títulos
            $titulo->pub_id = $pub_id;
        }
        foreach ($publicacion['tematica'] as $key_tem => $tematica) { //Insertar pub_id en temáticas
            $tematica->pub_id = $pub_id;
        }
        foreach ($publicacion['cobertura'] as $key_cob => $cobertura) { //Insertar pub_id en coberturas
            $cobertura->pub_id = $pub_id;
        }
        foreach ($publicacion['base_datos'] as $key_bd => $base_datos) { //Insertar pub_id en base de datos
            $base_datos->pub_id = $pub_id;
        }

        $this->db->insert_batch('titulo', $publicacion['titulo']); //Inserción de títulos
        
        $this->db->insert_batch('pub_tematica', $publicacion['tematica']); //Inserción de temáticas

        $this->db->insert_batch('cobertura', $publicacion['cobertura']); //Inserción de coberturas

        $this->db->insert_batch('pub_bd', $publicacion['base_datos']); //Inserción de bases de datos
        
        if ($this->db->trans_status() === FALSE){
            $this->db->trans_rollback();
            $resultado['result'] = FALSE;
            $resultado['msg'] = "Ocurrió un error durante el guardado, por favor intentelo de nuevo más tarde.";
        } else {
            $this->db->trans_commit();
            $resultado['msg'] = "Inserción completada";
            $resultado['data'] = $pub_id;
            $resultado['result'] = TRUE;
        }

        return $resultado;
    }

    public function guardar_validacion($val_id, $validacion){
        $resultado = array('result'=>TRUE, 'msg'=>'', 'data'=>null);
        $this->db->trans_begin(); //Definir inicio de transacción

        $this->db->delete('cobertura_val', array('val_id'=>$val_id)); //Actualización de coberturas
        $this->db->insert_batch('cobertura_val', $validacion['coberturas']);

        $this->db->where('val_id', $val_id);
        $this->db->update('validacion', $validacion['validacion']);
        
        if($this->db->trans_status() === FALSE){
            $this->db->trans_rollback();
            $resultado['result'] = FALSE;
            $resultado['msg'] = "Ocurrió un error durante el guardado, por favor inténtelo de nuevo más tarde.".$base_datos_error;
        } else {
            $this->db->trans_commit();
            $resultado['msg'] = "Actualización completada";
            $resultado['result'] = TRUE;
        }

        return $resultado;
    }

    public function insertar_validacion($validacion){
        $resultado = array('result'=>TRUE, 'msg'=>'', 'data'=>null);
        
        $this->db->trans_begin(); //Definir inicio de transacción
        //pr($validacion);
        $this->db->select('COUNT(val_id) AS total'); //Contar número de validaciones hechas por revisión
        $this->db->where('rev_id', $validacion['validacion']->rev_id);
        $revision = $this->db->get('validacion');
        if($revision->num_rows() > 0){
            $folio = $revision->row()->total+1;
        } else {
            $folio = 1;
        }
        $validacion['validacion']->val_folio .= $folio; ///Agregar a folio consecutivo
        
        $this->db->insert('validacion', $validacion['validacion']);
        $insert_id = $this->db->insert_id(); //Inserción de validación

        $coberturas_config = $this->config->item('publicacion_cobertura'); //Obtener tipos de coberturas
        $validacion['coberturas'][$coberturas_config['reciente']['id']]->val_id = $insert_id;
        $validacion['coberturas'][$coberturas_config['antiguo']['id']]->val_id = $insert_id;
        $this->db->insert_batch('cobertura_val', $validacion['coberturas']); //Inserción de coberturas
        
        if($this->db->trans_status() === FALSE){
            $this->db->trans_rollback();
            $resultado['result'] = FALSE;
            $resultado['msg'] = "Ocurrió un error durante el guardado, por favor inténtelo de nuevo más tarde.".$base_datos_error;
        } else {
            $this->db->trans_commit();
            $resultado['msg'] = "Actualización completada";
            $resultado['data'] = $insert_id;
            $resultado['result'] = TRUE;
        }

        return $resultado;
    }

    public function insertar_revision($session){
        $resultado = array('result'=>TRUE, 'msg'=>'', 'data'=>null);
        
        $this->db->trans_begin(); //Definir inicio de transacción

        $this->db->insert('revision', $session);
        $insert_id = $this->db->insert_id(); //Inserción de validación

        if($this->db->trans_status() === FALSE){
            $this->db->trans_rollback();
            $resultado['result'] = FALSE;
            $resultado['msg'] = "Ocurrió un error durante el guardado, por favor inténtelo de nuevo más tarde.".$base_datos_error;
        } else {
            $this->db->trans_commit();
            $resultado['msg'] = "Actualización completada";
            $resultado['rev_id'] = $insert_id;
            $resultado['result'] = TRUE;
        }

        return $resultado;
    }

    /*public function get_revision($params=array()){
        $resultado = array();
        if(array_key_exists('conditions', $params)){
            $this->db->where($params['conditions']);
        }

        $query = $this->db->get('revision');
        //pr($this->db->last_query());
        $resultado=$query->result_array();
        $query->free_result();
        return $resultado;
    }*/

    public function get($params){
        $resultado = array();

        $this->db->where('publicacion.pub_id',$params['pub_id']);
        $this->db->join('idioma', 'publicacion.lang_id=idioma.lang_id AND idioma.lang_activo=1', 'left');
        $this->db->join('estado_publicacion', 'publicacion.est_pub_id=estado_publicacion.est_pub_id', 'left');
        $this->db->join('editor_comercial', 'publicacion.ec_id=editor_comercial.ec_id AND editor_comercial.ec_activo=1', 'left');
        $this->db->join('pais', 'publicacion.pais_codigo=pais.pais_codigo AND pais.pais_activo=1', 'left');
        $this->db->join('usuario', 'publicacion.responsable_id=usuario.usr_matricula AND usuario.usr_activo=1', 'left');

        $query = $this->db->get('publicacion');
        //pr($this->db->last_query());
        $resultado=$query->result_array();
        $query->free_result();
        return $resultado;
    }

    public function get_titulo($params=array()){
        $resultado = array();
        if(array_key_exists('conditions', $params)){
            $this->db->where($params['conditions']);
        }

        $query = $this->db->get('titulo');
        //pr($this->db->last_query());
        $resultado=$query->result_array();
        $query->free_result();
        return $resultado;
    }

    public function get_cobertura($params=array()){
        $resultado = array();
        if(array_key_exists('conditions', $params)){
            $this->db->where($params['conditions']);
        }
        $this->db->order_by('cp_tipo', 'ASC');

        $query = $this->db->get('cobertura');
        //pr($this->db->last_query());
        $resultado=$query->result_array();
        $query->free_result();
        return $resultado;
    }
    
    public function update_order_pub_us($publicacion,$matricula){
        $data = array('responsable_id' => $matricula);
        $this->db->where('pub_id', $publicacion);
        $this->db->update('publicacion', $data);
    }


    public function matriculas_usuarios(){
        $sql="SELECT usr_matricula FROM pd_usuario";
        $query = $this->db->query($sql);
        if ($query->num_rows() > 0){
            return $query->result();
        }else{
            return false;
        }
        
    }
    
    public function abcd_publicaciones(){
       $sql="SELECT 
            GROUP_CONCAT(DISTINCT(`ti`.`pub_id`) SEPARATOR '_') as `publicaciones`, 
            SUBSTR(`ti`.`t_titulo`,1,1) as `letra`,  
            COUNT(DISTINCT(`ti`.`pub_id`)) as `total_por_letra` 
            FROM 
            `pd_titulo` as `ti`, 
            `pd_publicacion` as `pu`
            WHERE
            SUBSTR(`ti`.`t_titulo`,1,1)=SUBSTR(`ti`.`t_titulo`,1,1) AND
            `ti`.`pub_id` = `pu`.`pub_id`
            GROUP BY `letra` 
            ORDER BY `letra` ASC
            ";
        $query = $this->db->query($sql);

        if ($query->num_rows() > 0){
            /*$datos = array();
            foreach ($query->result() as $row){                
                $datos[] = array(
                    'publicaciones'=>$row->publicaciones,
                    'letras'=>$row->letra,
                    'total_por_letra'=>$row->total_por_letra
                );

            }*/
            return $query->result();
        }else{
            return false;
        } 
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
