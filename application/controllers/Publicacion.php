<?php
defined('BASEPATH') OR exit('No direct script access allowed');

/**
 * Clase que gestiona las publicaciones
 * @version 	: 1.0.0
 * @autor 		: Jesús Díaz P.
 */
class Publicacion extends CI_Controller {
	/**
     * Carga de clases para el acceso a base de datos y para la creación de elementos del formulario
	 * @access 		: public
	 * @modified 	: 
     */
	var $sessionData;
	public function __construct() {
        parent::__construct(); 
        date_default_timezone_set('America/Mexico_City');
        $this->load->library('form_complete');
        //$this->load->library('session');        
        $this->load->library('excel');
        $this->load->model('Publicacion_model', 'publicacion');
        $this->load->model('Base_datos_model', 'base_datos');
        $this->config->load('general');
        $this->sessionData = $this->session->userdata();
    }

    /**
     * Método que carga el formulario de búsqueda y el listado de publicaciones.
     * @autor 		: Jesús Díaz P.
	 * @modified 	: 
	 * @access 		: public
     */
	public function index()	{
		$this->load->model('Idioma_model', 'idioma');
		$this->load->model('Estado_publicacion_model', 'estado_publicacion');
		$this->load->model('Tematica_model', 'tematica');
		$this->load->model('Usuario_model', 'usuario');
		
		if(array_key_exists('publicacion', $this->sessionData) && !empty($this->sessionData['publicacion'])){ //Si existe información relacionada con alguna revisión, se elimina de la sesión
			$this->session->unset_userdata('publicacion');
		}
		if(array_key_exists('revision', $this->sessionData) && !empty($this->sessionData['revision'])){ //Si existe información relacionada con alguna revisión, se elimina de la sesión
			$this->session->unset_userdata('revision');
		}//pr(encrypt_base64(0));

		$idiomas = $this->idioma->listado_idiomas(array('conditions'=>array('lang_activo'=>1)));
		$estados_publicacion = $this->estado_publicacion->listado_estados_publicacion();
		$bases_datos = $this->base_datos->listado_base_datos(array('conditions'=>array('bd_activo'=>1)));
		$tematicas = $this->tematica->get_pub_tematica(array('conditions'=>array('t_activo'=>1)));
		$responsables = $this->usuario->listado_usuarios(array('fields'=>"usr_matricula, CONCAT( usr_nombre, ' ', COALESCE(usr_paterno,''), ' ', COALESCE(usr_materno,''), '' ) AS nombre", 'conditions'=>array('usr_activo'=>1), 'order'=>array('field'=>'nombre','type'=>'ASC')));
		//$responsables = $this->usuario->listado_usuarios(array('fields'=>"usr_matricula, CONCAT( usr_nombre, ' ', usr_paterno, ' ', usr_materno, '' ) AS nombre", 'conditions'=>array('usr_activo'=>1)));
		//pr($responsables);
		$datos['idiomas'] = dropdown_options($idiomas['data'], 'lang_id', 'lang_idioma');
		$datos['estados_publicacion'] = dropdown_options($estados_publicacion['data'], 'est_pub_id', 'est_pub_nombre');
		$datos['bases_datos'] = dropdown_options($bases_datos['data'], 'bd_id', 'bd_nombre');
		$datos['tematicas'] = dropdown_options($tematicas, 't_id', 't_nombre');
		$datos['responsables'] = dropdown_options($responsables['data'], 'usr_matricula', 'nombre');
		$datos['order_columns'] = array('issn'=>'ISSN', 'idioma'=>'Idioma', 'titulo'=>'Título', 'responsable'=>'Responsable');

		$template['menu'] = $this->load->view('template/menu', null, TRUE);

		$template['main_content'] = $this->load->view('publicacion/formulario_listado', $datos, TRUE);
		$this->template->template_conricyt($template);
	}

	private function form_validation_arreglo($arreglo, $elemento, $etiqueta, $reglas){
		if(is_null($arreglo) || empty($arreglo)) {
			$this->form_validation->set_rules($elemento, $etiqueta, $reglas);
		} else {
			foreach ($arreglo as $key => $valor) {
				$this->form_validation->set_rules($elemento."[".$key."]", $etiqueta, $reglas);
			}
		}
	}
        
        

	/**
     * Método que permite editar las publicaciones.
     * @autor 		: Jesús Díaz P.
	 * @modified 	: 
	 * @access 		: public
     */
	public function edicion($pub_id=null){
		if(exist_and_not_null($pub_id)){
			$this->load->model('Tematica_model', 'tematica');
			$this->load->model('Editor_comercial_model', 'editor_comercial');
			$this->load->model('Idioma_model', 'idioma');
			$this->load->model('Pais_model', 'pais');
			$this->load->model('Usuario_model', 'usuario');
			$this->load->model('Estado_publicacion_model', 'estado_publicacion');
			
			$pub_id = (int)decrypt_base64($pub_id); ///Formato a identificador
			$usuario_admin = $this->config->item('usuario_admin');
			$formato_publicacion=array();

			if($this->input->post()){
                            $this->load->library('form_validation');
                            $this->config->load('form_validation'); //Cargar archivo con validaciones
                                
                            $validations = $this->config->item('publicacion_formulario'); //Obtener validaciones de archivo
                            $datos = $this->input->post();
                                //validar que exista el usuario
                              
                                if(array_key_exists('tematica', $datos)){ //Obtener nombres de temáticas, ya que el post no los retorna
					$tematicas = $this->tematica->listado_tema(array('conditions'=>'tema.t_id IN ('.implode(",", $datos['tematica']).")"));//Obtener temáticas
					$datos['tematica_nombre'] = dropdown_options($tematicas['data'], 't_id', 't_nombre');
				} else {
					$datos['tematica'] = array();
					$datos['tematica_nombre'] = array();
				}  
				if(array_key_exists('bd', $datos)){ //Obtener nombres de bases de datos, ya que el post no los retorna
					$bds = $this->base_datos->listado_base_datos(array('conditions'=>'base_datos.bd_id IN ('.implode(',', $datos['bd']).')'));//Obtener bases de datos
					$datos['bd_nombre'] = dropdown_options($bds['data'], 'bd_id', 'bd_nombre');
				} else {
					$datos['bd'] = array();
					$datos['bd_url'] = array();
				}

				$formato_publicacion = $this->formato_publicacion($pub_id, $datos); //Generar objeto 'publicación' con datos del formulario
				
				$this->form_validation_arreglo($formato_publicacion['titulo'], 'titulos', 'Título', 'required|alpha_numeric_accent_space|min_length[3]|max_length[100]'); //Generar reglas para los títulos
				$this->form_validation_arreglo($datos['tematica'], 'tematica', 'Temática', 'required|integer|max_length[2]'); //Generar reglas para la temática
				$this->form_validation_arreglo($datos['bd'], 'bd', 'Base de datos', 'required|integer'); //Generar reglas para las bases de datos
				$this->form_validation_arreglo($datos['bd_url'], 'bd_url', 'URL de la base de datos', 'validate_url|max_length[255]'); //Generar reglas para las URLs de bases de datos
				$this->form_validation->set_rules($validations);

				if($this->form_validation->run() == TRUE){ //Validar datos
					$this->load->library('Bitacora'); //Cargar clase para insertar en la bitácora
					$datos['tematica_nombre'] = array();
					$datos['bd_nombre'] = array();
					$fp = $this->formato_publicacion($pub_id, $datos); //Generar formato para actualización en BD
					$bds = $this->base_datos->get_pub_bd(array('conditions'=>array('pub_bd.pub_id'=>$pub_id)));//Obtener bases de datos
					$fp['pub_bd'] = array_column($bds, 'bd_id');
					
					if($pub_id!=$this->config->item('id_insercion')) { //Actualización
						$formato_publicacion['resultado'] = $this->publicacion->guardar_publicacion($pub_id, $fp); //Realizar actualización
						$data_bitacora = array('modulo'=>'Publicación', 'modulo_url'=>'publicacion/edicion/'.$pub_id, 'accion'=>'Edición');
						$this->bitacora->bitacora_insertar($data_bitacora);
					} else {
						$formato_publicacion['resultado'] = $this->publicacion->insertar_publicacion($pub_id, $fp); //Realizar inserción
						$mod_url = (exist_and_not_null_array($formato_publicacion['resultado'], 'data')) ? array('modulo_url'=>'publicacion/edicion/'.$formato_publicacion['resultado']['data']) : array();
						$data_bitacora = array('modulo'=>'Publicación', 'accion'=>'Alta')+$mod_url;
						$this->bitacora->bitacora_insertar($data_bitacora);
						redirect('publicacion/edicion/'.encrypt_base64($formato_publicacion['resultado']['data']).'?r=1');
					}
					if($formato_publicacion['resultado']['result']==true){ //En caso de que la actualización haya sido correcta, se toman las bases de datos almacenados, asegurando el mostrar datos correctos.
						//$this->bitacora->bitacora_insertar($data_bitacora);
						$bds = $this->base_datos->get_pub_bd(array('conditions'=>array('pub_bd.pub_id'=>$pub_id)));//Obtener bases de datos
						$bdtemp['bd'] = array_column($bds, 'bd_id');
						$bdtemp['bd_url'] = dropdown_options($bds, 'bd_id', 'pub_bd_url');
						$bdtemp['bd_nombre'] = dropdown_options($bds, 'bd_id', 'bd_nombre');

						$formato_publicacion['base_datos'] = $this->formato_bds($pub_id, array('bd'=>$bdtemp['bd'], 'url'=>$bdtemp['bd_url'], 'nombre'=>$bdtemp['bd_nombre']));
					}
				}
			}
			$publicacion_coberturas = array();
			if(empty($formato_publicacion)){ //Carga inicial de datos
				////////////////////Obtener datos de la base de datos
				$pub = $this->publicacion->get(array('pub_id'=>$pub_id)); //Obtener datos de la publicación
				$titulos = $this->publicacion->get_titulo(array('conditions'=>array('titulo.pub_id'=>$pub_id))); //Obtener títulos
				$tematicas = $this->tematica->get_pub_tematica(array('conditions'=>array('pub_tematica.pub_id'=>$pub_id)));//Obtener temáticas
				$bds = $this->base_datos->get_pub_bd(array('conditions'=>array('pub_bd.pub_id'=>$pub_id)));//Obtener bases de datos
				
				$pc = (!empty($pub)) ? $pub[0] : array();
				$pc['titulos'] = array_column($titulos, 't_titulo');
				$pc['tematica'] = array_column($tematicas, 't_id');
				$pc['tematica_nombre'] = dropdown_options($tematicas, 't_id', 't_nombre');
				$pc['bd'] = array_column($bds, 'bd_id');
				$pc['bd_url'] = dropdown_options($bds, 'bd_id', 'pub_bd_url');
				$pc['bd_nombre'] = dropdown_options($bds, 'bd_id', 'bd_nombre');
				
				$coberturas =  $this->publicacion->get_cobertura(array('conditions'=>array('cobertura.pub_id'=>$pub_id)));//Obtener coberturas
				foreach ($coberturas as $key_cobertura => $cobertura) {
					$publicacion_coberturas[$cobertura['cp_tipo']] = (object) $cobertura; //Arreglo enviado a la vista
					$pc[$cobertura['cp_tipo'].'_anio'] = $cobertura['cp_anio'];
					$pc[$cobertura['cp_tipo'].'_volumen'] = $cobertura['cp_vol'];
					$pc[$cobertura['cp_tipo'].'_numero'] = $cobertura['cp_num'];
				}
				$publicacion = $this->formato_publicacion($pub_id, $pc);
			} else { //Si el formulario fue enviado, se toman esos datos
				$publicacion = $formato_publicacion;
				foreach ($formato_publicacion['cobertura'] as $key_cobertura => $cobertura) {
					$publicacion_coberturas[$cobertura->cp_tipo] = $cobertura; //Arreglo enviado a la vista
				}
			}
			
			$publicacion['pub_id'] = $pub_id;			
			$publicacion['publicacion_coberturas'] = $publicacion_coberturas;
			$publicacion['coberturas_config'] = $this->config->item('publicacion_cobertura');
			//pr($publicacion);
			////////////////////Obtener datos para listados desplegables
			$editor_comercial = $this->editor_comercial->listado_editor_comercial(array('conditions'=>array('ec_activo'=>1)));
			$idiomas = $this->idioma->listado_idiomas(array('conditions'=>array('lang_activo'=>1)));
			$paises = $this->pais->listado_paises(array('conditions'=>array('pais_activo'=>1)));
			$tematicas = $this->tematica->listado_tema(array('conditions'=>array('tema.t_activo'=>1)));
			$base_datos = $this->base_datos->listado_base_datos(array('conditions'=>array('base_datos.bd_activo'=>1)));
			$publicacion_estado = $this->config->item('publicacion_estado');
			$responsables = $this->usuario->listado_usuarios(array('conditions'=>array('usuario.usr_activo'=>1, 'usuario.usr_matricula <>'=>$usuario_admin['matricula']), 
				'order'=>array('field'=>'nombre','type'=>'ASC'), 'fields'=>array("usr_matricula, CONCAT(COALESCE(usr_paterno,''),' ',COALESCE(usr_materno,''),' ',usr_nombre) AS nombre"))
			);
			////Definir estados disponibles para la creación de una publicación
			$condicion_estado_publicacion = ($pub_id==$this->config->item('id_insercion')) ? array('conditions'=>array('est_pub_id'=>$publicacion_estado['pendiente'])) : array();
			$estado_publicacion = $this->estado_publicacion->listado_estados_publicacion($condicion_estado_publicacion);
			
			////////////////////Generar arreglos para listados desplegables
			$publicacion['editores_comerciales'] = dropdown_options($editor_comercial['data'], 'ec_id', 'ec_nombre');
			$publicacion['idiomas'] = dropdown_options($idiomas['data'], 'lang_id', 'lang_idioma');
			$publicacion['paises'] = dropdown_options($paises['data'], 'pais_codigo', 'pais_nombre');
			$publicacion['tematicas'] = dropdown_options($tematicas['data'], 't_id', 't_nombre');
			$publicacion['bases_datos'] = dropdown_options($base_datos['data'], 'bd_id', 'bd_nombre');
			$publicacion['responsables'] = dropdown_options($responsables['data'], 'usr_matricula', 'nombre');
			$publicacion['estados_publicacion'] = dropdown_options($estado_publicacion['data'], 'est_pub_id', 'est_pub_nombre');
			$publicacion['formatos'] = $this->config->item('publicacion_formato');
			
			$template['main_content'] = $this->load->view('publicacion/formulario_edicion', $publicacion, TRUE);
			$template['js_files'] = js("publicacion/publicacion.js"); //Cargar js
		} else {
			$template['main_content'] = "Por favor seleccione una publicación válida.";
		}
		$this->template->template_conricyt($template);
	}
        
        public function check_user($matricula){
            $responsable = $this->usuario->usuario_existente($matricula);
            
            
                if(isset($responsable) && !empty($responsable) && $responsable == true){
                        return true;
                    
                } else {
                        $this->form_validation->set_message('check_user','El usuario no es correcto');
                        return false;
                }
                
                       
            
        }

	/**
     * Método que permite editar las publicaciones.
     * @autor 		: Jesús Díaz P.
	 * @modified 	: 
	 * @access 		: public
     */
	public function detalle_publicacion(){
		//pr($this->sessionData);
		if($this->input->is_ajax_request()){ //Solo se accede al método a través de una petición ajax
			$pub_id = $this->sessionData['publicacion']['pub_id'];
			$resultado = array('resultado'=>FALSE, 'error'=>'', 'data'=>'');
			if(exist_and_not_null($pub_id)){
				$this->load->model('Tematica_model', 'tematica');
				$this->load->model('Editor_comercial_model', 'editor_comercial');
				$this->load->model('Idioma_model', 'idioma');
				$this->load->model('Pais_model', 'pais');
				$this->load->model('Usuario_model', 'usuario');
				$this->load->model('Estado_publicacion_model', 'estado_publicacion');
				
				$pub_id = (int)decrypt_base64($pub_id); ///Formato a identificador
				$usuario_admin = $this->config->item('usuario_admin');
				$formato_publicacion=array();
				$publicacion_coberturas = array();

				////////////////////Obtener datos de la base de datos
				$pub = $this->publicacion->get(array('pub_id'=>$pub_id)); //Obtener datos de la publicación
				$pc = array();

				if(!empty($pub)){
					$titulos = $this->publicacion->get_titulo(array('conditions'=>array('titulo.pub_id'=>$pub_id))); //Obtener títulos
					$tematicas = $this->tematica->get_pub_tematica(array('conditions'=>array('pub_tematica.pub_id'=>$pub_id)));//Obtener temáticas
					$bds = $this->base_datos->get_pub_bd(array('conditions'=>array('pub_bd.pub_id'=>$pub_id)));//Obtener bases de datos

					$pc = $pub[0];
					$pc['titulos'] = array_column($titulos, 't_titulo');
					$pc['tematica'] = array_column($tematicas, 't_id');
					$pc['tematica_nombre'] = dropdown_options($tematicas, 't_id', 't_nombre');
					$pc['bd'] = array_column($bds, 'bd_id');
					$pc['bd_url'] = dropdown_options($bds, 'bd_id', 'pub_bd_url');
					$pc['bd_nombre'] = dropdown_options($bds, 'bd_id', 'bd_nombre');

					$publicacion['titulos'] = (object)array_column($titulos, 't_titulo');
					$pc['tematica'] = array_column($tematicas, 't_id');
					$pc['tematica_nombre'] = dropdown_options($tematicas, 't_id', 't_nombre');
					$pc['bd'] = array_column($bds, 'bd_id');
					$pc['bd_url'] = dropdown_options($bds, 'bd_id', 'pub_bd_url');
					$pc['bd_nombre'] = dropdown_options($bds, 'bd_id', 'bd_nombre');
					
					$coberturas =  $this->publicacion->get_cobertura(array('conditions'=>array('cobertura.pub_id'=>$pub_id)));//Obtener coberturas
					foreach ($coberturas as $key_cobertura => $cobertura) {
						$publicacion_coberturas[$cobertura['cp_tipo']] = (object) $cobertura; //Arreglo enviado a la vista
						$pc[$cobertura['cp_tipo'].'_anio'] = $cobertura['cp_anio'];
						$pc[$cobertura['cp_tipo'].'_volumen'] = $cobertura['cp_vol'];
						$pc[$cobertura['cp_tipo'].'_numero'] = $cobertura['cp_num'];
					}
				}
				$publicacion['publicacion'] = (object)$pc;
				$publicacion['pub_id'] = $pub_id;
				$publicacion['publicacion_coberturas'] = $publicacion_coberturas;
				$publicacion['coberturas_config'] = $this->config->item('publicacion_cobertura');
				$publicacion['formatos'] = $this->config->item('publicacion_formato');
				
				$resultado['resultado'] = true;
				$resultado['data'] = $this->load->view('publicacion/formulario_detalle', $publicacion, TRUE);
			} else {
				$resultado['error'] = "Por favor seleccione una publicación válida.";
			}
			echo json_encode($resultado);
            exit();
		} else {
			redirect(site_url()); //Redirigir al inicio del sistema si se desea acceder al método mediante una petición normal, no ajax
		}
	}

	public function revision_interna($pub_id=null, $bd_id=null){
		$tipo_revision = $this->config->item('publicacion_tipo_revision');
		$this->revision(array('pub_id'=>$pub_id, 'bd_id'=>$bd_id, 'tipo'=>$tipo_revision['interna']['id']));
	}

	public function revision_remota($pub_id=null, $bd_id=null){
		$tipo_revision = $this->config->item('publicacion_tipo_revision');
		$this->revision(array('pub_id'=>$pub_id, 'bd_id'=>$bd_id, 'tipo'=>$tipo_revision['remota']['id']));
	}

	private function revision($ids){
		if(exist_and_not_null($ids['pub_id']) && exist_and_not_null($ids['bd_id'])){
			$this->load->model('Revision_model', 'revision');
			$this->load->helper('fecha');

			$session = array();
			$revision=array();
			$revision['revisado'] = $revision['validado'] = array();

			$pub_id = (int)decrypt_base64($ids['pub_id']); ///Se quita formato a identificadores y se convierte a entero
			$bd_id = (int)decrypt_base64($ids['bd_id']);
			
			if($pub_id>0 && $bd_id>0){
				//Verificar si existen datos de publicación en session
				if(!array_key_exists('publicacion', $this->sessionData)){
					$session = array('publicacion'=>array('pub_id'=>$ids['pub_id'], 'bd_id'=>$ids['bd_id'], 'tipo'=>$ids['tipo']));
				}

				/////Obtener datos (revisión, publicación, $títulos)
				$revision['revision'] = $this->revision->listado_revision(array('conditions'=>array('revision.pub_id'=>$pub_id, 'revision.bd_id'=>$bd_id, 'revision.rev_tipo'=>$ids['tipo']))); //Obtener datos de la revisión
				$pub = $this->publicacion->get(array('pub_id'=>$pub_id)); //Obtener datos de la publicación
				$titulos = $this->publicacion->get_titulo(array('conditions'=>array('titulo.pub_id'=>$pub_id))); //Obtener títulos
				$base_datos = $this->base_datos->listado_base_datos(array('conditions'=>array('base_datos.bd_activo'=>1, 'base_datos.bd_id'=>$bd_id)));
				//pr($revision);
				if(!empty($revision['revision'])){ //Si existe información de revisiones, se obtienen sus correspondientes validaciones
					if(!array_key_exists('publicacion', $this->sessionData)){
						$session['publicacion']['rev_id'] = $revision['revision'][0]['rev_id'];
					}
					$revision['revisado'] = $this->revision->listado_validacion(array('conditions'=>array('validacion.rev_id'=>$revision['revision'][0]['rev_id']), 'order'=>'criterio.cri_id ASC, validacion.val_fecha DESC'));
					$revision['validado'] = $this->revision->listado_validacion_validada(array('conditions'=>array('validacion.rev_id'=>$revision['revision'][0]['rev_id']), 'order'=>'criterio.cri_id ASC', 'fields'=>array('validacion.*')));
				} else {
					$this->load->library('Bitacora'); //Cargar clase para insertar en la bitácora
					//Insertar revisión
					$formato_revision = $this->formato_revision(array('pub_id'=>$pub_id, 'bd_id'=>$bd_id, 'tipo'=>$ids['tipo']));
					$revision = $this->publicacion->insertar_revision($formato_revision);
					if(exist_and_not_null_array($revision, 'rev_id')){ //Obtener id de revisión
						$this->bitacora->bitacora_insertar(array('modulo'=>'Revisión', 'modulo_url'=>'publicacion/revision/'.$revision['rev_id'], 'accion'=>'Alta'));
						$session['publicacion']['rev_id'] = $revision['rev_id'];
					}
					$revision['revisado'] = $revision['validado'] = null;
				}
				$revision['busqueda_criterio'] = $this->get_busqueda_criterio($revision['revisado'], $revision['validado']);
				
				if(!empty($session)){ //Agregar datos a sesión
					$this->session->set_userdata($session);
				}

				$pc = $pub[0];
				$pc['titulos'] = array_column($titulos, 't_titulo');
				$revision['base_datos'] = $base_datos['data'][0];
				$revision['publicacion'] = $this->formato_publicacion($pub_id, $pc); ///Formato de información a objeto
				$template['js_files'] = js("publicacion/revision.js"); //Cargar js
				$template['main_content'] = $this->load->view('publicacion/formulario_revision', $revision, TRUE);
			} else {
				$template['main_content'] = "Por favor seleccione una publicación y revisión válida.";
			}
		} else {
			$template['main_content'] = "Por favor seleccione una publicación válida.";
		}
		$this->template->template_conricyt($template);
	}

	/**
     * Método que carga el formulario de creación y edición de la validación, solo se permiten peticiones ajax
     * @autor 		: Jesús Díaz P.
	 * @modified 	: 
	 * @access 		: public
	 * @param 		: POST
	 * @result 		: HTML con formulario
     */
	public function cargar_formulario_revision(){
		if($this->input->is_ajax_request()){ //Solo se accede al método a través de una petición ajax
			if(!is_null($this->input->post())){ //Se verifica que se haya recibido información por método post
				$datos = $this->input->post();
				if(exist_and_not_null($datos['validacion'])){
					$this->load->model('Revision_model', 'revision');
					$this->load->library('form_validation');
					$this->config->load('form_validation'); //Cargar archivo con validaciones
					$val_id = decrypt_base64($datos['validacion']);
					$validacion=array();
					$formato_validacion=array();
					if(exist_and_not_null_array($datos, 'guardar') && $datos['guardar']==1){ //Validamos que se haya recibido información para la inserción o actualización de datos
						$coberturas_config = $this->config->item('publicacion_cobertura'); //Obtener tipos de coberturas
						$datos['criterio_busqueda'] = (exist_and_not_null_array($datos, 'criterio_busqueda')) ? $datos['criterio_busqueda'] : '';
						$datos['rev_id'] = $this->sessionData['publicacion']['rev_id'];
						$datos['val_folio'] = $this->generar_folio($datos['criterio_busqueda']);//Generar folio
						$validacion['validacion'] = $this->formato_validacion($val_id, $datos);
						$validacion['coberturas'][$coberturas_config['reciente']['id']] = $this->formato_cobertura_val($val_id, $datos+array('tipo'=>$coberturas_config['reciente']['id']));
						$validacion['coberturas'][$coberturas_config['antiguo']['id']] = $this->formato_cobertura_val($val_id, $datos+array('tipo'=>$coberturas_config['antiguo']['id']));

						$validations = $this->config->item('validacion_formulario'); //Obtener validaciones de archivo
						$this->form_validation->set_rules($validations);

						if($this->form_validation->run() == TRUE){ //Validar datos
							$this->load->library('Bitacora'); //Cargar clase para insertar en la bitácora
							if($val_id!=$this->config->item('id_insercion')) { //Actualización
								$validacion['resultado'] = $this->publicacion->guardar_validacion($val_id, $validacion); //Guardar datos
								$data_bitacora = array('modulo'=>'Validación', 'modulo_url'=>'publicacion/cargar_formulario_revision/'.$val_id, 'accion'=>'Edición');
							} else { //Insert
								$validacion['resultado'] = $this->publicacion->insertar_validacion($validacion); //Insertar datos
								$mod_url = (exist_and_not_null_array($validacion['resultado'], 'data')) ? array('modulo_url'=>'publicacion/cargar_formulario_revision/'.$validacion['resultado']['data']) : array();
								$data_bitacora = array('modulo'=>'Validación', 'accion'=>'Alta')+$mod_url;
							}
							$this->bitacora->bitacora_insertar($data_bitacora);
						}
					}
					$conditions = array();
					if(empty($validacion)){
						if($val_id!="0"){ //Actualización (Si es 0 será tomado como insert, de lo contrario una actualización)
							////Obtener datos para llenar formulario
							$data_validacion = $this->revision->listado_validacion(array('conditions'=>array('validacion.val_id'=>$val_id)));
							$coberturas =  $this->revision->get_cobertura(array('conditions'=>array('cobertura_val.val_id'=>$val_id)));//Obtener coberturas

							$validacion['validacion'] = $this->formato_validacion($val_id, $data_validacion[0]);
							foreach ($coberturas as $key_cobertura => $cobertura) {
								$validacion['coberturas'][$cobertura['cv_tipo']] = (object)$cobertura; //Arreglo enviado a la vista
							}
							$conditions['conditions']['bsq_id'] = $data_validacion[0]['bsq_id'];
						} else { //Inserción
							$validacion['validacion'] = $this->formato_validacion($val_id, array('rev_id'=>null,'val_folio'=>null));
						}
					}
					////Obtener datos para llenar las listas desplegables
					$conditions['conditions']['bsql_activo'] = 1;
					$data_busqueda = $this->revision->get_busqueda($conditions);
					$data_estado_validacion = $this->revision->get_estado_validacion();
					
					////Generar formato adecuado para el llenado de las listas desplegables
					$validacion['tipo_busqueda'] = dropdown_options($data_busqueda, 'bsq_id', 'bsq_nombre');
					$validacion['criterio_busqueda'] = array();
					$validacion['estado_revision'] = dropdown_options($data_estado_validacion, 'ev_id', 'est_nombre');
					$validacion['coberturas_config'] = $this->config->item('publicacion_cobertura');
					
					$template['main_content'] = $this->load->view('publicacion/formulario_revision_edicion', $validacion);
				} else {
					echo "Por favor seleccione una revisión válida.";
				}
			}
		} else {
			redirect(site_url()); //Redirigir al inicio del sistema si se desea acceder al método mediante una petición normal, no ajax
		}
	}

	public function detalle_revision(){
		if($this->input->is_ajax_request()){ //Solo se accede al método a través de una petición ajax
			if(!is_null($this->input->post())){ //Se verifica que se haya recibido información por método post
				$datos = $this->input->post();
				if(exist_and_not_null($datos['validacion'])){
					$this->load->model('Revision_model', 'revision');
					$this->load->library('form_validation');
					$this->config->load('form_validation'); //Cargar archivo con validaciones
					$val_id = decrypt_base64($datos['validacion']);
					$validacion=array();
					$formato_validacion=array();
					$conditions = array();

					////Obtener datos para llenar formulario
					$data_validacion = $this->revision->listado_validacion(array('conditions'=>array('validacion.val_id'=>$val_id)));
					$coberturas =  $this->revision->get_cobertura(array('conditions'=>array('cobertura_val.val_id'=>$val_id)));//Obtener coberturas
					
					$validacion['validacion'] = $this->formato_validacion($val_id, $data_validacion[0]);
					foreach ($coberturas as $key_cobertura => $cobertura) {
						$validacion['coberturas'][$cobertura['cv_tipo']] = (object)$cobertura; //Arreglo enviado a la vista
					}

					////Obtener datos para llenar las listas desplegables
					$validacion['data_busqueda'] = $this->revision->get_busqueda(array('conditions'=>array('bsql_activo'=>1, 'bsq_id'=>$data_validacion[0]['bsq_id'])));
					$validacion['data_criterio'] = $this->revision->get_criterio(array('conditions'=>array('cri_activo'=>1, 'bsq_id'=>$data_validacion[0]['bsq_id'], 'cri_id'=>$data_validacion[0]['cri_id'])));
					$validacion['data_estado_validacion'] = $this->revision->get_estado_validacion(array('conditions'=>array('ev_id'=>$data_validacion[0]['ev_id'])));
					$validacion['coberturas_config'] = $this->config->item('publicacion_cobertura');
					$validacion['evidencias_ruta'] = asset_url().'files/revision/'.$this->sessionData['publicacion']['rev_id']."/".$val_id."/";
					$validacion['evidencias'] = get_files($this->config->item('ruta_revision').$this->sessionData['publicacion']['rev_id']."/".$val_id."/");
					
					$template['main_content'] = $this->load->view('publicacion/formulario_revision_detalle', $validacion);
				} else {
					echo "Por favor seleccione una revisión válida.";
				}
			}
		} else {
			redirect(site_url()); //Redirigir al inicio del sistema si se desea acceder al método mediante una petición normal, no ajax
		}
	}

	/**
     * Método que crea el folio para las validaciones, utiliza el [ISSN, ISSNL, ISSNE ó ISSNP], tipo de revisión (externo o remoto) y un consecutivo
     * @autor 		: Jesús Díaz P.
	 * @modified 	: 
	 * @access 		: public
	 * @param 		: $criterio Identificador del criterio
	 * @result 		: folio
     */
	private function generar_folio($criterio){
		$separador = "_";
		$rev_id = $this->sessionData['publicacion']['rev_id'];
		$tipo = $this->sessionData['publicacion']['tipo'];
		$pub_id = decrypt_base64($this->sessionData['publicacion']['pub_id']);
		$pub = $this->publicacion->get(array('pub_id'=>$pub_id));
		$issn = (exist_and_not_null_array($pub[0], 'pub_issn')) ? $pub[0]['pub_issn'] : ((exist_and_not_null_array($pub[0], 'pub_issnl')) ? $pub[0]['pub_issnl'] :  ((exist_and_not_null_array($pub[0], 'pub_issne')) ? $pub[0]['pub_issne'] : $pub[0]['pub_issnp'] ));
		
		//return $issn.$separador.$rev_id.$separador.$tipo.$separador.$criterio;
		return $rev_id.$separador.$tipo.$separador.$criterio.$separador;
	}
	
	public function cargar_archivo_listado(){
		if($this->input->is_ajax_request()){ //Solo se accede al método a través de una petición ajax
			if(!is_null($this->input->post())){ //Se verifica que se haya recibido información por método post
				$datos = $this->input->post();
				$val_id = decrypt_base64($datos['validacion']);
				if(exist_and_not_null($val_id)){
					$evidencias['evidencias_ruta'] = asset_url().'files/revision/'.$this->sessionData['publicacion']['rev_id']."/".$val_id."/";
					$evidencias['evidencias'] = get_files($this->config->item('ruta_revision').$this->sessionData['publicacion']['rev_id']."/".$val_id."/");
					$this->load->view('publicacion/listado_evidencias', $evidencias);
				}
			}
		}
	}

	public function cargar_archivo(){
		if($this->input->is_ajax_request()){ //Solo se accede al método a través de una petición ajax
			//pr($this->input->post());
			$val_id = decrypt_base64($this->input->post('val_id'));
			$resultado = array('resultado'=>FALSE, 'error'=>'');
			$ruta_archivos_revision = $this->config->item('ruta_revision').$this->sessionData['publicacion']['rev_id']."/";
			$ruta_archivos = $ruta_archivos_revision.$val_id."/";
			if(!file_exists($ruta_archivos_revision) && !is_dir($ruta_archivos_revision)){ //Si no existe la carpeta se crea
				mkdir($ruta_archivos_revision);
			}
			if(!file_exists($ruta_archivos) && !is_dir($ruta_archivos)){ //Si no existe la carpeta se crea
				mkdir($ruta_archivos);
			}

			$config['upload_path']          = $ruta_archivos;
            $config['allowed_types']        = $this->config->item('extension_revision');
            $config['max_size']             = $this->config->item('max_size_revision'); // Definir tamaño máximo de archivo
            $config['detect_mime']			= TRUE; // Validar mime type
            
            $this->load->library('upload', $config); ///Cargar librería que carga y valida los archivos
            $elemento_upload = array_keys($_FILES); ///Obtener nombre de elemento que se esta cargando

            if(!$this->upload->do_upload($elemento_upload[0])) {
                $resultado['error'] = $this->upload->display_errors();
            } else {
            	$this->load->library('Bitacora'); //Cargar clase para insertar en la bitácora
                $resultado['data'] = array('upload_data' => $this->upload->data());
                $resultado['resultado'] = TRUE;
                $this->bitacora->bitacora_insertar(array('modulo'=>'Evidencia', 'modulo_url'=>'publicacion/cargar_archivo/'.$this->sessionData['publicacion']['rev_id'].'/'.$val_id.'/'.$resultado['data']['upload_data']['file_name'], 'accion'=>'Alta'));
            }
            echo json_encode($resultado);
            exit();
		} else {
			redirect(site_url()); //Redirigir al inicio del sistema si se desea acceder al método mediante una petición normal, no ajax
		}
	}

	public function eliminar_archivo(){
		if($this->input->is_ajax_request()){ //Solo se accede al método a través de una petición ajax
			$resultado = array('resultado'=>FALSE, 'error'=>'');
			if(!is_null($this->input->post())){ //Se verifica que se haya recibido información por método post
				$datos = $this->input->post();
				$val_id = decrypt_base64($datos['val_id']);
				if(exist_and_not_null($val_id) && exist_and_not_null($datos['archivo'])){
					$upload_path = $this->config->item('ruta_revision').$this->sessionData['publicacion']['rev_id']."/".$val_id."/".$datos['archivo'];
					if (file_exists($upload_path)) {
						unlink($upload_path);
						$resultado['resultado'] = TRUE;

						$this->load->library('Bitacora'); //Cargar clase para insertar en la bitácora
						$this->bitacora->bitacora_insertar(array('modulo'=>'Evidencia', 'modulo_url'=>'publicacion/eliminar_archivo/'.$this->sessionData['publicacion']['rev_id'].'/'.$val_id."/".$datos['archivo'], 'accion'=>'Eliminar'));
					} else {
						$resultado['error'] = "No se localizo el archivo que intenta borrar.";
					}
				} else {
					$resultado['error'] = "Faltan datos para poder eliminar el archivo.";
				}
			} else {
				$resultado['error'] = "Faltan datos para poder eliminar el archivo.";
			}
			echo json_encode($resultado);
            exit();
		} else {
			redirect(site_url()); //Redirigir al inicio del sistema si se desea acceder al método mediante una petición normal, no ajax
		}
	}

	public function cargar_criterio(){
		if($this->input->is_ajax_request()){ //Solo se accede al método a través de una petición ajax
			$resultado = array('resultado'=>FALSE, 'error'=>'', 'data'=>'');
			if(!is_null($this->input->post())){ //Se verifica que se haya recibido información por método post
				$datos = $this->input->post();
				$seleccionado = "";
				if(exist_and_not_null($datos['buscador'])){
					$this->load->model('Revision_model', 'revision');
					$data_criterio = $this->revision->get_criterio(array('conditions'=>array('cri_activo'=>1, 'bsq_id'=>$datos['buscador'])));
					$criterio_busqueda = dropdown_options($data_criterio, 'cri_id', 'cri_nombre');
					$seleccionado = (exist_and_not_null($datos['seleccionado'])) ? $datos['seleccionado'] : '';
				} else {
					$criterio_busqueda = null;
				}
				$resultado['resultado'] = TRUE;
				$resultado['data'] = $this->form_complete->create_element(array('id'=>'criterio_busqueda', 'type'=>'dropdown', 'options'=>$criterio_busqueda, 'value'=>$seleccionado, 'first'=>array(''=>'Seleccione...'), 'attributes'=>array('name'=>'criterio_busqueda[]', 'class'=>'form-control', 'placeholder'=>'Criterio de búsqueda', 'data-toggle'=>'tooltip', 'data-placement'=>'top', 'title'=>'Criterio de búsqueda', 'onchange'=>'checar_validacion();')));
			} else {
				$resultado['error'] = "Ocurrió un error en el servidor, inténtelo más tarde.";
			}
			echo json_encode($resultado);
            exit();
		} else {
			redirect(site_url()); //Redirigir al inicio del sistema si se desea acceder al método mediante una petición normal, no ajax
		}
	}

	public function checar_validacion(){
		if($this->input->is_ajax_request()){ //Solo se accede al método a través de una petición ajax
			if(!is_null($this->input->post())){ //Se verifica que se haya recibido información por método post
				$this->load->model('Revision_model', 'revision');
				$datos = $this->input->post();
				$resultado = array('resultado'=>FALSE, 'error'=>'');
				if(exist_and_not_null($datos['busqueda']) && exist_and_not_null($datos['criterio'])){
					$rev_id = $this->sessionData['publicacion']['rev_id'];
					$rev = $this->revision->listado_validacion(array('conditions'=>array('validacion.cri_id'=>$datos['criterio'], 'validacion.bsq_id'=>$datos['busqueda'], 'rev_id'=>$rev_id)));
					if(empty($rev)){
						$resultado['resultado'] = TRUE;
					} else {
						$resultado['error'] = 'Criterio de búsqueda validado.';
					}
				} else {
					$resultado['error'] = 'Debe elegir el tipo y el criterio de búsqueda.';
				}
				//$data_criterio = $this->revision->get_criterio(array('conditions'=>array('cri_activo'=>1, 'bsq_id'=>$datos['buscador'])));
				
				echo json_encode($resultado);
			} else {
				echo "Ocurrió un error en el servidor, inténtelo más tarde.";
			}
			exit();
		} else {
			redirect(site_url()); //Redirigir al inicio del sistema si se desea acceder al método mediante una petición normal, no ajax
		}
	}

	public function cerrar_revision(){
		if($this->input->is_ajax_request()){ //Solo se accede al método a través de una petición ajax
			if(!is_null($this->input->post())){ //Se verifica que se haya recibido información por método post
				$this->load->model('Revision_model', 'revision');
				$revision_estado = $this->config->item('revision_estado');
				$resultado = array('resultado'=>FALSE, 'error'=>'');
				//pr($this->sessionData);
				////Obtener estado actual de la revisión
				$data_revision = $this->revision->listado_revision(array('conditions'=>array('rev_id'=>$this->sessionData['publicacion']['rev_id'])));
				$rev_estado_actual = ($data_revision[0]['rev_estado']==$revision_estado['completa']) ? $revision_estado['incompleta'] : $revision_estado['completa'];
				
				//Obtenemos validaciones de la revisión
				//$validaciones = $this->revision->listado_validacion(array('conditions'=>array('validacion.rev_id'=>$this->sessionData['publicacion']['rev_id']), 'fields'=>array('validacion.cri_id','validacion.bsq_id')));
				$validacion_estado = $this->config->item('validacion_estado'); //Obtener estados de la revisión
				$validaciones = $this->revision->listado_validacion_validada(array('conditions'=>array('validacion.rev_id'=>$this->sessionData['publicacion']['rev_id']), 'fields'=>array('validacion.cri_id','validacion.bsq_id','validacion.ev_id')));
				
				////Obtener número de criterios a validar
				$busqueda_criterio = $this->revision->get_criterio_busqueda(array('conditions'=>array('bsql_activo'=>1, 'cri_activo'=>1), 'fields'=>array('criterio.cri_id','criterio.bsq_id')));
				
				foreach ($busqueda_criterio as $key => $bc) {
					if(exist_and_not_null($validaciones)){
						foreach ($validaciones as $key_v => $val) {
							/*if($val['bsq_id']==$bc['bsq_id'] && $val['cri_id']==$bc['cri_id']){ //Comparar validaciones existentes con criterios a validar
								unset($busqueda_criterio[$key]);
								break;
							}*/
							if($val['bsq_id']==$bc['bsq_id'] && $val['cri_id']==$bc['cri_id'] && in_array($val['ev_id'], $validacion_estado['correcta'])){ //Comparar validaciones existentes con criterios a validar
								unset($busqueda_criterio[$key]);
								break;
							}
						}
					}
				}
				////En caso de que la revisión no tenga validaciones pendientes o se desee cambiar el estado de completo a incompleto
				if(empty($busqueda_criterio) || ($data_revision[0]['rev_estado']==$revision_estado['completa'])){
					$this->load->library('Bitacora'); //Cargar clase para insertar en la bitácora
					$this->revision->update_revision_estado($this->sessionData['publicacion']['rev_id'], array('rev_estado'=>$rev_estado_actual)); //Actualizar estado
					$resultado['resultado'] = TRUE;
					$this->bitacora->bitacora_insertar(array('modulo'=>'Revisión', 'modulo_url'=>'publicacion/cerrar_revision/'.$this->sessionData['publicacion']['rev_id'].'/'.$rev_estado_actual, 'accion'=>'Completar revisión'));
				} else {
					$resultado['error'] = "No se puede marcar como completada esta revisión, actualmente falta agregar validaciones al recurso, por favor revise el listado de pendientes en la columna 'Validado'.";
				}				
				echo json_encode($resultado);
	            exit();
			} else {
				echo "Ocurrió un error en el servidor, inténtelo más tarde.";
			}
		} else {
			redirect(site_url()); //Redirigir al inicio del sistema si se desea acceder al método mediante una petición normal, no ajax
		}
	}

	private function get_busqueda_criterio($revisado, $validado){
		$busqueda_criterio = $this->revision->get_criterio_busqueda(array('conditions'=>array('bsql_activo'=>1, 'cri_activo'=>1)));
		$resultado = array();
		$validacion_estado = $this->config->item('validacion_estado'); //Obtener estados de la revisión
		
		foreach ($busqueda_criterio as $key => $bc) {
			$resultado[$key]['busqueda'] = $bc['bsq_nombre'];
			$resultado[$key]['criterio'] = $bc['cri_nombre'];
			if(exist_and_not_null($revisado)){
				foreach ($revisado as $key_v => $val) {
					if($val['bsq_id']==$bc['bsq_id'] && $val['cri_id']==$bc['cri_id']){
						$resultado[$key]['registrado'] = TRUE;
					}
				}
				foreach ($validado as $key_val => $valid) {
					if($valid['bsq_id']==$bc['bsq_id'] && $valid['cri_id']==$bc['cri_id'] && in_array($valid['ev_id'], $validacion_estado['correcta'])){
						$resultado[$key]['validado'] = TRUE;
					}
				}
			}
		}
		return $resultado;
	}

	private function formato_revision($arreglo_revision){
		$revision_estado = $this->config->item('revision_estado'); //Obtener estados de la revisión

		$revision = new Revision_dao;
		$revision->pub_id = $arreglo_revision['pub_id'];
		$revision->bd_id = $arreglo_revision['bd_id'];
		$revision->rev_tipo = $arreglo_revision['tipo'];
		$revision->rev_estado = $revision_estado['incompleta'];

		return $revision;
	}

	private function formato_validacion(&$val_id, $arreglo_validacion){
		$validacion = new Validacion_dao;
		$validacion->val_id = $val_id;
		$validacion->val_fecha = date('Y-m-d H:i:s');
		$validacion->rev_id = $arreglo_validacion['rev_id'];
		$validacion->ev_id = (isset($arreglo_validacion['estado_revision']) && !empty($arreglo_validacion['estado_revision'])) ? $arreglo_validacion['estado_revision'] : ((isset($arreglo_validacion['ev_id']) && !empty($arreglo_validacion['ev_id'])) ? $arreglo_validacion['ev_id'] : NULL);
		$validacion->cri_id = (isset($arreglo_validacion['criterio_busqueda']) && !empty($arreglo_validacion['criterio_busqueda'])) ? $arreglo_validacion['criterio_busqueda'] : ((isset($arreglo_validacion['cri_id']) && !empty($arreglo_validacion['cri_id'])) ? $arreglo_validacion['cri_id'] : NULL);
		$validacion->bsq_id = (isset($arreglo_validacion['tipo_busqueda']) && !empty($arreglo_validacion['tipo_busqueda'])) ? $arreglo_validacion['tipo_busqueda'] : ((isset($arreglo_validacion['bsq_id']) && !empty($arreglo_validacion['bsq_id'])) ? $arreglo_validacion['bsq_id'] : NULL);
		$validacion->val_folio = $arreglo_validacion['val_folio'];
		$validacion->val_disponibilidad = (isset($arreglo_validacion['disponible']) && !empty($arreglo_validacion['disponible'])) ? $arreglo_validacion['disponible'] : ((isset($arreglo_validacion['val_disponibilidad']) && !empty($arreglo_validacion['val_disponibilidad'])) ? $arreglo_validacion['val_disponibilidad'] : NULL);
		$validacion->val_nota = (isset($arreglo_validacion['notas']) && !empty($arreglo_validacion['notas'])) ? $arreglo_validacion['notas'] : ((isset($arreglo_validacion['val_nota']) && !empty($arreglo_validacion['val_nota'])) ? $arreglo_validacion['val_nota'] : NULL);
		
		return $validacion;
	}

	private function formato_cobertura(&$pub_id, $arreglo_cobertura){
		$cobertura=new Cobertura_dao;
		$cobertura->pub_id = $pub_id;
		$cobertura->cp_tipo = $arreglo_cobertura['tipo'];
		$cobertura->cp_anio = (isset($arreglo_cobertura[$arreglo_cobertura['tipo'].'_anio']) && !empty($arreglo_cobertura[$arreglo_cobertura['tipo'].'_anio'])) ? $arreglo_cobertura[$arreglo_cobertura['tipo'].'_anio'] : NULL;
		$cobertura->cp_vol = (isset($arreglo_cobertura[$arreglo_cobertura['tipo'].'_volumen']) && !empty($arreglo_cobertura[$arreglo_cobertura['tipo'].'_volumen'])) ? $arreglo_cobertura[$arreglo_cobertura['tipo'].'_volumen'] : NULL;
		$cobertura->cp_num = (isset($arreglo_cobertura[$arreglo_cobertura['tipo'].'_numero']) && !empty($arreglo_cobertura[$arreglo_cobertura['tipo'].'_numero'])) ? $arreglo_cobertura[$arreglo_cobertura['tipo'].'_numero'] : NULL;
		
		return $cobertura;
	}

	private function formato_cobertura_val(&$val_id, $arreglo_cobertura){
		$cobertura=new Cobertura_val_dao;
		$cobertura->val_id = $val_id;
		$cobertura->cv_tipo = $arreglo_cobertura['tipo'];
		$cobertura->cv_anio = (isset($arreglo_cobertura[$arreglo_cobertura['tipo'].'_anio']) && isset($arreglo_cobertura[$arreglo_cobertura['tipo'].'_anio'])) ? $arreglo_cobertura[$arreglo_cobertura['tipo'].'_anio'] : NULL;
		$cobertura->cv_vol = (isset($arreglo_cobertura[$arreglo_cobertura['tipo'].'_volumen']) && isset($arreglo_cobertura[$arreglo_cobertura['tipo'].'_volumen'])) ? $arreglo_cobertura[$arreglo_cobertura['tipo'].'_volumen'] : NULL;
		$cobertura->cv_num = (isset($arreglo_cobertura[$arreglo_cobertura['tipo'].'_numero']) && isset($arreglo_cobertura[$arreglo_cobertura['tipo'].'_numero'])) ? $arreglo_cobertura[$arreglo_cobertura['tipo'].'_numero'] : NULL;
		
		return $cobertura;
	}

	private function formato_publicacion(&$pub_id, $arreglo_publicacion){
		$publicacion = array();
		//if(exist_and_not_null_array($arreglo_publicacion, 'titulos')){
			$this->config->load('general');
			$coberturas_config = $this->config->item('publicacion_cobertura');

			$bd_nombre = (isset($arreglo_publicacion['bd_nombre'])) ? $arreglo_publicacion['bd_nombre'] : array();
			$tematica_nombre = (isset($arreglo_publicacion['tematica_nombre'])) ? $arreglo_publicacion['tematica_nombre'] : array();

			$publicacion['titulo'] = (array_key_exists('titulos', $arreglo_publicacion)) ? $this->formato_titulo($pub_id, $arreglo_publicacion['titulos']) : array();
			$publicacion['tematica'] = (array_key_exists('tematica', $arreglo_publicacion)) ? $this->formato_tematica($pub_id, array('tematica'=>$arreglo_publicacion['tematica'], 'tematica_nombre'=>$tematica_nombre)) : array();
			$publicacion['base_datos'] = (array_key_exists('bd', $arreglo_publicacion)) ? $this->formato_bds($pub_id, array('bd'=>$arreglo_publicacion['bd'], 'url'=>$arreglo_publicacion['bd_url'], 'nombre'=>$bd_nombre)) : array();
			$publicacion['cobertura'][] = $this->formato_cobertura($pub_id, $arreglo_publicacion+array('tipo'=>$coberturas_config['reciente']['id']));
			$publicacion['cobertura'][] = $this->formato_cobertura($pub_id, $arreglo_publicacion+array('tipo'=>$coberturas_config['antiguo']['id']));
			
			$pub = new Publicacion_dao;
			//$pub->pub_id = $pub_id;
			$pub->pub_issn = (isset($arreglo_publicacion['issn']) && !empty($arreglo_publicacion['issn'])) ? $arreglo_publicacion['issn'] : ((isset($arreglo_publicacion['pub_issn']) && !empty($arreglo_publicacion['pub_issn'])) ? $arreglo_publicacion['pub_issn'] : NULL);
			$pub->pub_issne = (isset($arreglo_publicacion['issne']) && !empty($arreglo_publicacion['issne'])) ? $arreglo_publicacion['issne'] : ((isset($arreglo_publicacion['pub_issne']) && !empty($arreglo_publicacion['pub_issne'])) ? $arreglo_publicacion['pub_issne'] : NULL);
			$pub->pub_issnl = (isset($arreglo_publicacion['issnl']) && !empty($arreglo_publicacion['issnl'])) ? $arreglo_publicacion['issnl'] : ((isset($arreglo_publicacion['pub_issnl']) && !empty($arreglo_publicacion['pub_issnl'])) ? $arreglo_publicacion['pub_issnl'] : NULL);
			$pub->pub_issnp = (isset($arreglo_publicacion['issnp']) && !empty($arreglo_publicacion['issnp'])) ? $arreglo_publicacion['issnp'] : ((isset($arreglo_publicacion['pub_issnp']) && !empty($arreglo_publicacion['pub_issnp'])) ? $arreglo_publicacion['pub_issnp'] : NULL);
			$pub->pub_periodicidad = (isset($arreglo_publicacion['periodicidad']) && !empty($arreglo_publicacion['periodicidad'])) ? $arreglo_publicacion['periodicidad'] : ((isset($arreglo_publicacion['pub_periodicidad']) && !empty($arreglo_publicacion['pub_periodicidad'])) ? $arreglo_publicacion['pub_periodicidad'] : NULL);
			//$pub->pub_fuentes = (isset($arreglo_publicacion['fuentes']) && !empty($arreglo_publicacion['fuentes'])) ? $arreglo_publicacion['fuentes'] : ((isset($arreglo_publicacion['pub_fuentes']) && !empty($arreglo_publicacion['pub_fuentes'])) ? $arreglo_publicacion['pub_fuentes'] : NULL);
			$pub->pub_notas = (isset($arreglo_publicacion['notas']) && !empty($arreglo_publicacion['notas'])) ? $arreglo_publicacion['notas'] : ((isset($arreglo_publicacion['pub_notas']) && !empty($arreglo_publicacion['pub_notas'])) ? $arreglo_publicacion['pub_notas'] : NULL);
			$pub->lang_id = (isset($arreglo_publicacion['idioma']) && !empty($arreglo_publicacion['idioma'])) ? $arreglo_publicacion['idioma'] : ((isset($arreglo_publicacion['lang_id']) && !empty($arreglo_publicacion['lang_id'])) ? $arreglo_publicacion['lang_id'] : NULL);
			$pub->est_pub_id = (isset($arreglo_publicacion['estado_publicacion']) && !empty($arreglo_publicacion['estado_publicacion'])) ? $arreglo_publicacion['estado_publicacion'] : ((isset($arreglo_publicacion['est_pub_id']) && !empty($arreglo_publicacion['est_pub_id'])) ? $arreglo_publicacion['est_pub_id'] : NULL);
			$pub->ec_id = (isset($arreglo_publicacion['editor_comercial']) && !empty($arreglo_publicacion['editor_comercial'])) ? $arreglo_publicacion['editor_comercial'] : ((isset($arreglo_publicacion['ec_id']) && !empty($arreglo_publicacion['ec_id'])) ? $arreglo_publicacion['ec_id'] : NULL);
			$pub->pais_codigo = (isset($arreglo_publicacion['pais']) && !empty($arreglo_publicacion['pais'])) ? $arreglo_publicacion['pais'] : ((isset($arreglo_publicacion['pais_codigo']) && !empty($arreglo_publicacion['pais_codigo'])) ? $arreglo_publicacion['pais_codigo'] : NULL);
			$pub->responsable_id = (isset($arreglo_publicacion['responsable']) && !empty($arreglo_publicacion['responsable'])) ? $arreglo_publicacion['responsable'] : ((isset($arreglo_publicacion['responsable_id']) && !empty($arreglo_publicacion['responsable_id'])) ? $arreglo_publicacion['responsable_id'] : NULL);
			$pub->pub_formato = (isset($arreglo_publicacion['formato']) && !empty($arreglo_publicacion['formato'])) ? $arreglo_publicacion['formato'] : ((isset($arreglo_publicacion['pub_formato']) && !empty($arreglo_publicacion['pub_formato'])) ? $arreglo_publicacion['pub_formato'] : NULL);
			$publicacion['publicacion'] = $pub;
		//}
		return $publicacion;
	}

	private function formato_bds(&$pub_id, $arreglo_bds){
		$result=array();
		foreach ($arreglo_bds['bd'] as $b) {
			$bds=new Publicacion_base_datos_dao;
			$bds->pub_id = $pub_id;
			$bds->bd_id = $b;
			$bds->pub_bd_url = $arreglo_bds['url'][$b];
			if(isset($arreglo_bds['nombre']) && !empty($arreglo_bds['nombre'])){
				$bds->bd_nombre = $arreglo_bds['nombre'][$b];
			}
			$result[] = $bds;
		}
		return $result;
	}

	private function formato_titulo(&$pub_id, $arreglo_titulos){
		$result=array();
		foreach ($arreglo_titulos as $t) {
			$titulos=new Titulo_dao;
			$titulos->pub_id = $pub_id;
			$titulos->t_titulo = $t;
			$result[] = $titulos;
		}
		return $result;
	}

	private function formato_tematica(&$pub_id, $arreglo_tematicas){
		$result=array();
		foreach ($arreglo_tematicas['tematica'] as $t) {
			$tematicas=new Tematica_dao;
			$tematicas->pub_id = $pub_id;
			$tematicas->t_id = $t;
			if(isset($arreglo_tematicas['tematica_nombre']) && !empty($arreglo_tematicas['tematica_nombre'])) {
				$tematicas->t_nombre = $arreglo_tematicas['tematica_nombre'][$t];
			}
			$result[] = $tematicas;
		}
		return $result;
	}

	/********************************************* Inicio paginación ajax ************************************************/
	/**
     * Método que a través de una petición ajax muestra un listado de publicaciones, estos pueden ser filtrados de acuerdo a parámetros seleccionados
     * @autor 		: Jesús Díaz P.
	 * @modified 	: 
	 * @access 		: public
	 * @param 		: integer - $current_row - Registro actual, donde iniciará la visualización de registros
     */
	public function get_data_ajax($current_row=null){
		if($this->input->is_ajax_request()){ //Solo se accede al método a través de una petición ajax
			if(!is_null($this->input->post())){ //Se verifica que se haya recibido información por método post
				$this->load->model('Revision_model', 'revision');
				$this->load->model('Tematica_model', 'tematica');
				$datos_busqueda = $this->input->post(); //Datos del formulario se envían para generar la consulta
				$datos_busqueda['current_row'] = (isset($current_row) && !empty($current_row)) ? $current_row : 0; //Registro actual, donde inicia la visualización de registros
				$data_publicaciones = $this->publicacion->listado($datos_busqueda); //Obtener datos de la publicación
				//pr($data_publicaciones);
				$data_publicaciones['current_row'] = $datos_busqueda['current_row'];
				$data_publicaciones['per_page'] = $this->input->post('per_page'); //Número de registros a mostrar por página
				if($data_publicaciones['total']>0){
					foreach ($data_publicaciones['data'] as $key_data => $data_publicacion) {
						$data_tematica = array();
						if(isset($data_publicacion['tematica']) && !empty($data_publicacion['tematica'])){
							$data_tematica = $this->tematica->listado_tema(array('conditions'=>'t_id IN ('.$data_publicacion['tematica'].') AND t_activo=1')); //Obtener temáticas
						}
						$data_bd = $this->base_datos->listado_base_datos(array('conditions'=>'bd_id IN ('.$data_publicacion['base_datos'].') AND bd_activo=1')); //Obtener base de datos
						$data_rev = $this->revision->listado_revision(array('conditions'=>'pub_id='.$data_publicacion['pub_id'].' AND bd_id IN ('.$data_publicacion['base_datos'].')')); //Obtener datos de revisiones por base de datos

						foreach ($data_bd['data'] as $keyb => $bd) {
							foreach ($data_rev as $keyr => $rev) {
								if($bd['bd_id']==$rev['bd_id']){
									$data_bd['data'][$keyb]['revision'][] = $rev;
								}
							}
						}
						/*foreach ($data_bd['data'] as $key => $bd) {
							$data_rev = $this->revision->listado_revision(array('conditions'=>array('pub_id'=>$data_publicacion['pub_id'], 'bd_id'=>$bd['bd_id'])));
							$data_bd['data'][$key]['revision'] = $data_rev;
						}*/
                                                echo "<script type='text/javascript'>$('.reportes_excel').show();</script>";
						$data_publicaciones['data'][$key_data]['base_datos'] = $data_bd['data'];
						$data_publicaciones['data'][$key_data]['tematica'] = (array_key_exists('data', $data_tematica)) ? $data_tematica['data'] : $data_tematica;
					}
					$this->resultado_listado($data_publicaciones, array('form_recurso'=>'#form_publicacion', 'elemento_resultado'=>'#publicacion_resultado')); //Generar listado en caso de obtener datos
				} else {
					echo data_not_exist(); //Mostrar mensaje de datos no existentes
                                        echo "<script type='text/javascript'>$('.reportes_excel').hide();</script>";
				}
			}
		} else {
			redirect(site_url()); //Redirigir al inicio del sistema si se desea acceder al método mediante una petición normal, no ajax
		}
	}

	public function getajax_data_titulo($current_row=null){
		if($this->input->is_ajax_request()){ //Solo se accede al método a través de una petición ajax
			if(!is_null($this->input->post())){ //Se verifica que se haya recibido información por método post
				$this->load->model('Revision_model', 'revision');
				$this->load->model('Tematica_model', 'tematica');
				$datos_busqueda = $this->input->post(); //Datos del formulario se envían para generar la consulta
				$datos_busqueda['current_row'] = (isset($current_row) && !empty($current_row)) ? $current_row : 0; //Registro actual, donde inicia la visualización de registros
				$data_publicaciones = $this->publicacion->listado($datos_busqueda); //Obtener datos de la publicación
				//pr($data_publicaciones);
				
				$data_publicaciones['current_row'] = $datos_busqueda['current_row'];
				$data_publicaciones['per_page'] = $this->input->post('per_page'); //Número de registros a mostrar por página
				if($data_publicaciones['total']>0){
                                    
                                    $this->excel_titulos($data_publicaciones['data']);
                                    
                                } else {
					echo data_not_exist(); //Mostrar mensaje de datos no existentes
				}
			}
		} else {
			redirect(site_url()); //Redirigir al inicio del sistema si se desea acceder al método mediante una petición normal, no ajax
		}
	}


        public function excel_titulos(){
            
            if($this->input->post()){
                $datos_busqueda = $this->input->post();
            
            
            $rs = $this->publicacion->listado($datos_busqueda);
            $exceldata="";
            if($rs['total']>0){
            $this->excel->setActiveSheetIndex(0);   //Nombre de la hoja de trabajo
            $this->excel->getActiveSheet()->setTitle('Títulos');  //Titulo en A1   Instituto Mexicano del Seguro Social 
            //INICIO ENCABEZADO
            $objDrawing = new PHPExcel_Worksheet_Drawing();
            $objDrawing->setName('Logo');
            $objDrawing->setDescription('Instituto Mexicano del Seguro Social');
            $objDrawing->setPath('./assets/img/presidencia.png');
            $objDrawing->setCoordinates('A1');
            $objDrawing->setHeight(68);
            $objDrawing->setWidth(213);
            $objDrawing->setWorksheet($this->excel->getActiveSheet());
            
            $objDrawing = new PHPExcel_Worksheet_Drawing();
            $objDrawing->setName('Logo');
            $objDrawing->setPath('./assets/img/imss.png');
            $objDrawing->setCoordinates('F1');
            $objDrawing->setHeight(68);
            $objDrawing->setWidth(52);
            $objDrawing->setWorksheet($this->excel->getActiveSheet());
            
            $this->excel->getActiveSheet()->setCellValue('B1', 'Dirección de Prestaciones Médicas');
            $this->excel->getActiveSheet()->setCellValue('B2', 'Unidad de Educación, Investigación y Políticas de Salud');
            $this->excel->getActiveSheet()->setCellValue('B3', 'Coordinación de Educación en Salud');//            
            $this->excel->getActiveSheet()->setCellValue('B4', 'División de Innovación Educativa');
                        
            $this->excel->getActiveSheet()->mergeCells('B1:E1');    //Se uniran y centralizaran para resaltar el titulo del reporte celdas (A1 to E1)
            $this->excel->getActiveSheet()->mergeCells('B2:E2'); 
            $this->excel->getActiveSheet()->mergeCells('B3:E3');    //Se uniran y centralizaran para resaltar el titulo del reporte celdas (A1 to E1)
            $this->excel->getActiveSheet()->mergeCells('B4:E4'); 
                        
            $this->excel->getActiveSheet()->getStyle('B1')->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_RIGHT);
            $this->excel->getActiveSheet()->getStyle('B2')->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_RIGHT);
            $this->excel->getActiveSheet()->getStyle('B3')->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_RIGHT);
            $this->excel->getActiveSheet()->getStyle('B4')->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_RIGHT);
//TERMINA EMCABEZADO
            
            $this->excel->getActiveSheet()->setCellValue('A6', 'Título');
            $this->excel->getActiveSheet()->setCellValue('B6', 'ISSN');
            $this->excel->getActiveSheet()->setCellValue('C6', 'Estado'); 
            $this->excel->getActiveSheet()->setCellValue('D6', 'Idioma'); 
            $this->excel->getActiveSheet()->setCellValue('E6', 'Responsable'); 
            $this->excel->getActiveSheet()->getStyle('A6')->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
            $this->excel->getActiveSheet()->getStyle('B6')->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
            $this->excel->getActiveSheet()->getStyle('C6')->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
            $this->excel->getActiveSheet()->getStyle('D6')->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
            $this->excel->getActiveSheet()->getStyle('E6')->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
            $this->excel->getActiveSheet()->getStyle('A6')->getFont()->setBold(true);
            $this->excel->getActiveSheet()->getStyle('B6')->getFont()->setBold(true);
            $this->excel->getActiveSheet()->getStyle('C6')->getFont()->setBold(true);
            $this->excel->getActiveSheet()->getStyle('D6')->getFont()->setBold(true);
            $this->excel->getActiveSheet()->getStyle('E6')->getFont()->setBold(true);
            
            
            for($col = ord('A'); $col <= ord('E'); $col++){ 
                $this->excel->getActiveSheet()->getColumnDimension(chr($col))->setAutoSize(true);//Asignar dimencion de celdas
                $this->excel->getActiveSheet()->getStyle(chr($col))->getFont()->setSize(12);//Cambio de tamaño de letra
                $this->excel->getActiveSheet()->getStyle(chr($col))->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);                
                
            }
            
            
            foreach ($rs['data'] as $key => $titulo) {
               
                   // pr($nom);
                $exceldata[] = array($titulo['titulo'],$titulo['issn'],$titulo['estado_publicacion'],$titulo['idioma'],$titulo['responsable']);                
                
            }
                
            //pr($exceldata);
            $this->excel->getActiveSheet()->fromArray($exceldata, null, 'A7');
            $this->excel->getActiveSheet()->getStyle('A7')->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
            $this->excel->getActiveSheet()->getStyle('B7')->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
            $this->excel->getActiveSheet()->getStyle('C7')->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
            $this->excel->getActiveSheet()->getStyle('D7')->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
            $this->excel->getActiveSheet()->getStyle('E7')->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
                                                 
            $filename='rep_tit_'.time().'.xlsx'; //Salvar documento como
            header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet'); //mime type xls
            header('Content-Disposition: attachment;filename="'.$filename.'"'); //tell browser what's the file name
            header('Cache-Control: max-age=0'); //no cache
            //save it to Excel5 format (excel 2003 .XLS file), change this to 'Excel2007' (and adjust the filename extension, also the header mime type)
            //if you want to save it as .XLSX Excel 2007 format
            //pr($this->excel);
            $objWriter = PHPExcel_IOFactory::createWriter($this->excel, 'Excel2007');
            //force user to download the Excel file without writing it to server's HD
            //$objWriter->save($filename);
            $objWriter->save("php://output");
            
            }else{
                redirect(site_url()."/publicacion/");
            }
            }else{
                redirect(site_url()."/publicacion/");
            }
        }
        
        public function excel_tematica(){
            
            if($this->input->post()){
                
                $datos_busqueda = $this->input->post();
            //pr($datos_busqueda);
            
            $rs = $this->publicacion->listar_tematicas($datos_busqueda);
            $exceldata="";
            if(!empty($rs['data'])){
            
            $this->excel->setActiveSheetIndex(0);   //Nombre de la hoja de trabajo             
            $this->excel->getActiveSheet()->setTitle('Temáticas');  //Titulo en A1           
           
             //INICIO ENCABEZADO
            $objDrawing = new PHPExcel_Worksheet_Drawing();
            $objDrawing->setName('Logo');
            $objDrawing->setDescription('Instituto Mexicano del Seguro Social');
            $objDrawing->setPath('./assets/img/presidencia.png');
            $objDrawing->setCoordinates('A1');
            $objDrawing->setHeight(68);
            $objDrawing->setWidth(230);
            $objDrawing->setWorksheet($this->excel->getActiveSheet());
            
            $objDrawing = new PHPExcel_Worksheet_Drawing();
            $objDrawing->setName('Logo');
            $objDrawing->setPath('./assets/img/imss.png');
            $objDrawing->setCoordinates('F1');
            $objDrawing->setHeight(68);
            $objDrawing->setWidth(52);
            $objDrawing->setWorksheet($this->excel->getActiveSheet());
            $this->excel->getActiveSheet()->setTitle('Títulos');  //Titulo en A1   Instituto Mexicano del Seguro Social 
            $this->excel->getActiveSheet()->setCellValue('B1', 'Dirección de Prestaciones Médicas');
            $this->excel->getActiveSheet()->setCellValue('B2', 'Unidad de Educación, Investigación y Políticas de Salud');
            $this->excel->getActiveSheet()->setCellValue('B3', 'Coordinación de Educación en Salud');//            
            $this->excel->getActiveSheet()->setCellValue('B4', 'División de Innovación Educativa');
            
           
            $this->excel->getActiveSheet()->mergeCells('B1:E1');    //Se uniran y centralizaran para resaltar el titulo del reporte celdas (A1 to E1)
            $this->excel->getActiveSheet()->mergeCells('B2:E2'); 
            $this->excel->getActiveSheet()->mergeCells('B3:E3');    //Se uniran y centralizaran para resaltar el titulo del reporte celdas (A1 to E1)
            $this->excel->getActiveSheet()->mergeCells('B4:E4'); 
                        
            $this->excel->getActiveSheet()->getStyle('B1')->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_RIGHT);
            $this->excel->getActiveSheet()->getStyle('B2')->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_RIGHT);
            $this->excel->getActiveSheet()->getStyle('B3')->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_RIGHT);
            $this->excel->getActiveSheet()->getStyle('B4')->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_RIGHT);
//TERMINA EMCABEZADO
            $this->excel->getActiveSheet()->setCellValue('A6', ' ');
            $this->excel->getActiveSheet()->setCellValue('B6', 'Temática');
            $this->excel->getActiveSheet()->setCellValue('C6', 'Publicaciones');
            $this->excel->getActiveSheet()->setCellValue('D6', ' ');
            $this->excel->getActiveSheet()->getStyle('B6')->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
            $this->excel->getActiveSheet()->getStyle('C6')->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);           
            $this->excel->getActiveSheet()->getStyle('B6')->getFont()->setBold(true);
            $this->excel->getActiveSheet()->getStyle('C6')->getFont()->setBold(true);
            
            for($col = ord('B'); $col <= ord('C'); $col++){ 
                $this->excel->getActiveSheet()->getColumnDimension(chr($col))->setAutoSize(true);//Asignar dimencion de celdas
                $this->excel->getActiveSheet()->getStyle(chr($col))->getFont()->setSize(12);//Cambio de tamaño de letra
                $this->excel->getActiveSheet()->getStyle(chr($col))->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);                
                
            }
            
            foreach ($rs['data'] as $tematica) {
                $exceldata[] = array($tematica['t_nombre'],$tematica['total']);                
                //$exceldata[] = $titulo;
            }
            
            $this->excel->getActiveSheet()->fromArray($exceldata, null, 'B7');
            $this->excel->getActiveSheet()->getStyle('A7')->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
            $this->excel->getActiveSheet()->getStyle('D7')->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
            $this->excel->getActiveSheet()->getStyle('B7')->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
            $this->excel->getActiveSheet()->getStyle('C7')->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
            
            $filename='rep_tematica_'.time().'.xlsx'; //Salvar documento como
            
            header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet'); //mime type xls
            header('Content-Disposition: attachment;filename="'.$filename.'"'); //tell browser what's the file name
            header('Cache-Control: max-age=0'); //no cache
            //save it to Excel5 format (excel 2003 .XLS file), change this to 'Excel2007' (and adjust the filename extension, also the header mime type)
            //if you want to save it as .XLSX Excel 2007 format
            //pr($this->excel);
            $objWriter = PHPExcel_IOFactory::createWriter($this->excel, 'Excel2007');
            //force user to download the Excel file without writing it to server's HD
            //$objWriter->save($filename);
            $objWriter->save("php://output");
            
            }else{
                redirect(site_url()."/publicacion/");
            }
            }else{
                redirect(site_url()."/publicacion/");
            }
        }
		
		/**
		
		 * Formato pdf oficial en horizontal para el reporte de  listado de publicaciones  .
     * @autor 		: Dianna P. Ángeles Gualito
	 * @modified 	: 
	 * @access 		: private
	 * @param 		:
	 * @param 		: 
	 *					
	 *					
	
		**/
		public function pdf_listado(){
			$this->load->library('pdfh'); // Load library
            
            if($this->input->post()){    
                $datos_busqueda = $this->input->post();
				//pr($datos_busqueda);
				$rs = $this->publicacion->listado($datos_busqueda);
				//pr($rs['total']);
				//pr($rs['data']);
		
				if(!empty($rs['data'])){
					//Variables del documento pdf
					$datos['titulo_doc']=utf8_decode("Consulta de publicaciones");
					$datos['CtllEncabezado'] = array('Título','ISSN','Estado', 'Idioma','Responsable');
					$datos['CtllCampos']=array('titulo','issn','estado_publicacion', 'idioma','responsable');
					$datos['CtllAnchoC']=50;
					$datos['CtllAlineacionC']='C';
					$datos['CtllNumRegistros']=$rs['total'];
					$datos['CtllDatosTabla']=$rs['data'];
					//Carga la vista de documento PDF
					$result["vista"] = $this->load->view('publicacion/pdf_listado_pub.php',$datos);
					//creación del archivo
					$hoy = getdate();
					$dia=$hoy['year'].$hoy['mon'].$hoy['mday'];
					$NombreArchivo='reporte_listado_pub_'.$dia.'.pdf';
					$this->pdfh->Output($NombreArchivo,'D');
				}else{
					redirect(site_url()."/publicacion/");
				}
            }else{
                redirect(site_url()."/publicacion/");
            }
        }
        
    function asignacion_de_usuarios(){
        $matriculas=array(); $contador=0; $tempPub = array(); // variables que vamos a usar
        
        $usuarios = $this->publicacion->matriculas_usuarios(); ///Se obtiene información de usuarios
        $total_usuarios = count($usuarios); ///Se obtiene el total de usuarios                
               
        foreach ($usuarios as $row){$matriculas[] = $row->usr_matricula;}// descagar matriculas en arreglo                
        
        $query_letras = $this->publicacion->abcd_publicaciones(); //Total por letra  
        
        foreach($query_letras as $letra){
            $publicaciones = explode("_",$letra->publicaciones);
            foreach ($publicaciones as $publicacion){
                    if(!in_array($publicacion, $tempPub)){
                        if($contador < $total_usuarios){ //A 0 < 5
                            $this->publicacion->update_order_pub_us($publicacion,$matriculas[$contador]);                            
                        }else{
                            $contador = 0;
                            $this->publicacion->update_order_pub_us($publicacion,$matriculas[$contador]);
                        }
                        $contador++;
                        $tempPub[]=$publicacion;
                    }
            }
            
            //echo $letra->letra;
        }

    }
		
		/**
		
		 * Formato pdf oficial en horizontal para el reporte de  listado de publicaciones  .
     * @autor 		: Dianna P. Ángeles Gualito
	 * @modified 	: 
	 * @access 		: private
	 * @param 		:
	 * @param 		: 
	 *					
	 *					
	
		**/		
		public function pdf_tematica(){
			$this->load->library('pdfv'); // Load library
            
            if($this->input->post()){
                
                $datos_busqueda = $this->input->post();
				//pr($datos_busqueda);
				$rs = $this->publicacion->listar_tematicas($datos_busqueda);
				//pr($rs);
				//pr($rs['total']);
				//pr($rs['data']);
		
				if(!empty($rs['data'])){
					//Carga la vista de documento PDF
					$datos['titulo_doc']=utf8_decode("Consulta de datos por temática");
					$datos['CtllEncabezado'] = array('Temática','Publicaciones');
					$datos['CtllCampos']=array('t_nombre','total');
					$datos['CtllAnchoC']=95;
					$datos['CtllAlineacionC']='C';
					$datos['CtllNumRegistros']=$rs['total'];
					$datos['CtllDatosTabla']=$rs['data'];
					$result["vista"] = $this->load->view('publicacion/pdf_tematica.php',$datos);
					//creación del archivo
					$hoy = getdate();
					$dia=$hoy['year'].$hoy['mon'].$hoy['mday'];
					$NombreArchivo='reporte_tematica_'.$dia.'.pdf';
					$this->pdfv->Output($NombreArchivo,'D');
				}else{
					redirect(site_url()."/publicacion/");
				}
            }else{
                redirect(site_url()."/publicacion/");
            }
        }
		

	/**
     * Método que imprime el listado de publicaciones, se agrega paginación.
     * @autor 		: Jesús Díaz P.
	 * @modified 	: 
	 * @access 		: private
	 * @param 		: mixed[] $data Arreglo de publicaciones y de información necesaria para generar los links para la paginación
	 * @param 		: mixed[] $form Arreglo asociativo con 2 elementos. 
	 *					form_recurso -> identificador del formulario que contiene los elementos de filtración
	 *					elemento_resultado -> identificador del elemento donde se mostrará el listado
     */
	private function resultado_listado($data, $form){
		$pagination = $this->template->pagination_data($data); //Crear mensaje y links de paginación
		$links = "<div class='col-sm-5 dataTables_info'>".$pagination['total']."</div>
				<div class='col-sm-7'>".$pagination['links']."</div>";
		echo $links.$this->load->view('publicacion/resultado_listado', $data, TRUE).$links.'
			<script>
			$("ul.pagination li a").click(function(event){
		        data_ajax(this, "'.$form['form_recurso'].'", "'.$form['elemento_resultado'].'");
		        event.preventDefault();
		    });
			</script>';
	}

	/**
     * Método que imprime el listado de publicaciones, se agrega paginación. No utiliza view, solo hace uso de elemento 'table'
     * @autor 		: Jesús Díaz P.
	 * @modified 	: 
	 * @access 		: private
	 * @param 		: mixed[] $data Arreglo de publicaciones y de información necesaria para generar los links para la paginación
	 * @param 		: mixed[] $form Arreglo asociativo con 2 elementos. 
	 *					form_recurso -> identificador del formulario que contiene los elementos de filtración
	 *					elemento_resultado -> identificador del elemento donde se mostrará el listado
     */
	private function template_ajax($datos, $form){
		$pagination = $this->template->pagination_data($datos); //Crear mensaje y links de paginación
		$links = "<div class='col-sm-5 dataTables_info'>".$pagination['total']."</div>
				<div class='col-sm-7'>".$pagination['links']."</div>";
		$template = array('table_open' => '<table class="table table-striped table-bordered dataTable no-footer" cellspacing="0">'); //Creación de tabla con datos
		$this->table->set_template($template); //Modifcar template de la tabla
		$this->table->set_heading($datos['columns']); //Encabezado de columnas
		foreach ($datos['data'] as $key => $value) {
			$this->table->add_row($value);
		}

		echo $links."<div class='col-sm-12 center'>".$this->table->generate()."</div>".$links.'
			<script>
			$("ul.pagination li a").click(function(event){
		        data_ajax(this, "'.$form['form_recurso'].'", "'.$form['elemento_resultado'].'");
		        event.preventDefault();
		    });
			</script>';
	}
	/*********************************************** Fin paginación ajax *************************************************/
}

class Titulo_dao {
	/*private $pub_id;
	private $t_titulo;
	public function __get($property) {
		if (property_exists($this, $property)) {
			return $this->$property;
		}
	}

	public function __set($property, $value) {
		if (property_exists($this, $property)) {
			$this->$property = $value;
		}
		return $this;
	}*/
	public $pub_id;
	public $t_titulo;
}

class Tematica_dao {
	public $pub_id;
	public $t_id;
}

class Publicacion_base_datos_dao{
	public $pub_id;
	public $bd_id;
	public $pub_bd_url;
}

class Publicacion_dao{
	//public $pub_id;
	public $pub_issn;
	public $pub_issnl;
	public $pub_issne;
	public $pub_issnp;
	public $pub_periodicidad;
	//public $pub_fuentes;
	public $pub_notas;
	public $lang_id;
	public $est_pub_id;
	public $ec_id;
	public $pais_codigo;
	public $responsable_id;
	public $pub_formato;
}

class Cobertura_dao{
	public $cp_anio;
	public $cp_vol;
	public $cp_num;
	public $cp_tipo;
	public $pub_id;
}

class Cobertura_val_dao{
	public $cv_anio;
	public $cv_vol;
	public $cv_num;
	public $cv_tipo;
	public $val_id;
}

class Validacion_dao{
	public $val_fecha;
	public $rev_id;
	public $ev_id;
	public $cri_id;
	public $bsq_id;
	public $val_folio;
	public $val_disponibilidad;
	public $val_nota;
}

class Revision_dao{
	public $pub_id;
	public $bd_id;
	public $rev_tipo;
	public $rev_estado;
}