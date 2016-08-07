<?php
defined('BASEPATH') OR exit('No direct script access allowed');

/**
 * Clase que gestiona las publicaciones
 * @version 	: 1.0.0
 * @autor 		: Jesús Díaz P.
 */
class Buscador extends CI_Controller {
	/**
     * Carga de clases para el acceso a base de datos y para la creación de elementos del formulario
	 * @access 		: public
	 * @modified 	: 
     */
	var $sessionData;
	public function __construct() {
            parent::__construct();
            $this->load->library('form_complete');        
            $this->load->model('Buscador_model', 'buscador');  

            $this->config->load('general');        
        }

    /**
     * Método que carga el formulario de búsqueda y el listado de publicaciones.
     * @autor 		: Jesús Díaz P.
	 * @modified 	: 
	 * @access 		: public
     */
	public function index()	{
		//$this->load->model('Idioma_model', 'idioma');
                //$this->load->model('Tematica_model', 'tematica');
                
		
                $idiomas = $this->buscador->listado_idiomas(array('conditions'=>array('lang_activo'=>1)));		
		$bases_datos = $this->buscador->listado_base_datos(array('conditions'=>array('bd_activo'=>1)));
		$tematicas = $this->buscador->get_pub_tematica(array('conditions'=>array('t_activo'=>1)));
		
                $datos['idiomas'] = dropdown_options($idiomas['data'], 'lang_id', 'lang_idioma');
		$datos['bases_datos'] = dropdown_options($bases_datos['data'], 'bd_id', 'bd_nombre');
		$datos['tematicas'] = dropdown_options($tematicas, 't_id', 't_nombre');
		$datos['order_columns'] = array('issn'=>'ISSN', 'idioma'=>'Idioma', 'titulo'=>'Título', 'base_datos'=>'BD');

                
                
		$datos[''] = null;
                
                //pr($datos);
		$template['main_content'] = $this->load->view('buscador/formulario', $datos, TRUE);
		$this->template->template_buscador($template);
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
                            
                                //aqui va la nueva conexion a la base de datos del buscador
                                
                                //Se guarda lo que se busco asi como la matricula de quien realizo la busqueda
                                                            
				//$this->load->model('Revision_model', 'revision');
				//$this->load->model('Tematica_model', 'tematica');
				$datos_busqueda = $this->input->post(); //Datos del formulario se envían para generar la consulta
				/*pr($datos_busqueda);
                                
                                pr($this->input->ip_address());
                                if(!empty($datos_busqueda['titulo'])){
                                    pr("OK<BR>");
                                }
                                */
                                
                                $datos_busqueda['ip_usuario'] = $this->input->ip_address();
                                $datos_busqueda['current_row'] = (isset($current_row) && !empty($current_row)) ? $current_row : 0; //Registro actual, donde inicia la visualización de registros
				$data_publicaciones = $this->buscador->listado($datos_busqueda); //Obtener datos de la publicación
				//pr($data_publicaciones);
				$data_publicaciones['current_row'] = $datos_busqueda['current_row'];
				$data_publicaciones['per_page'] = $this->input->post('per_page'); //Número de registros a mostrar por página
				if($data_publicaciones['total'] > 0){
					foreach ($data_publicaciones['data'] as $key_data => $data_publicacion) {
						$data_tematica = array();
						if(isset($data_publicacion['tematica']) && !empty($data_publicacion['tematica'])){
							$data_tematica = $this->buscador->listado_tema(array('conditions'=>'t_id IN ('.$data_publicacion['tematica'].') AND t_activo=1')); //Obtener temáticas
						}
						$data_bd = $this->buscador->listado_link_base_datos(array('conditions'=>'base_datos.bd_id IN ('.$data_publicacion['base_datos'].') AND base_datos.bd_activo=1 AND pub_bd.pub_id='.$data_publicacion['pub_id'].' ')); //Obtener base de datos
						/*$data_rev = $this->revision->listado_revision(array('conditions'=>'pub_id='.$data_publicacion['pub_id'].' AND bd_id IN ('.$data_publicacion['base_datos'].')')); //Obtener datos de revisiones por base de datos
                                                //pr($data_bd);
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
						$data_publicaciones['data'][$key_data]['base_datos'] = $data_bd['data'];
						$data_publicaciones['data'][$key_data]['tematica'] = (array_key_exists('data', $data_tematica)) ? $data_tematica['data'] : $data_tematica;
					}
					$this->listado_resultado($data_publicaciones, array('form_recurso'=>'#form_buscador', 'elemento_resultado'=>'#listado_resultado')); //Generar listado en caso de obtener datos
				} else {
					echo data_not_exist(); //Mostrar mensaje de datos no existentes
				}
			}
		} else {
			redirect(site_url()); //Redirigir al inicio del sistema si se desea acceder al método mediante una petición normal, no ajax
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
	private function listado_resultado($data, $form){
		$pagination = $this->template->pagination_data_buscador($data); //Crear mensaje y links de paginación
		$links = "<div class='col-sm-5 dataTables_info' style='line-height: 50px;'>".$pagination['total']."</div>
				<div class='col-sm-7 text-right'>".$pagination['links']."</div>";
		echo $links.$this->load->view('buscador/resultado_busqueda', $data, TRUE).$links.'
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
		$pagination = $this->template->pagination_data_buscador($datos); //Crear mensaje y links de paginación
		$links = "<div class='col-sm-5 dataTables_info' style='line-height: 50px;'>".$pagination['total']."</div>
				<div class='col-sm-7 text-right'>".$pagination['links']."</div>";
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
        
        
}
