<?php
defined('BASEPATH') OR exit('No direct script access allowed');

/**
 * Clase que gestiona las publicaciones
 * @version 	: 1.0.0
 * @autor 		: Jesús Díaz P.
 */
class Reporte extends CI_Controller {
	/**
     * Carga de clases para el acceso a base de datos y para la creación de elementos del formulario
	 * @access 		: public
	 * @modified 	: 
     */
	public function __construct() {
        parent::__construct();
        $this->load->library('form_complete');
        //$this->load->model('Publicacion_model', 'publicacion');
    }

    /**
     * Método que carga el formulario de búsqueda y el listado de publicaciones.
     * @autor 		: Jesús Díaz P.
	 * @modified 	: 
	 * @access 		: public
     */
	public function index()	{
		//$this->load->model('Idioma_model', 'idioma');
		//$this->load->model('Estado_publicacion_model', 'estado_publicacion');
		//$this->load->model('Base_datos_model', 'base_datos');

		//$idiomas = $this->idioma->listado_idiomas(array('conditions'=>array('lang_activo'=>1)));
		//$estados_publicacion = $this->estado_publicacion->listado_estados_publicacion();
		//$bases_datos = $this->base_datos->listado_base_datos(array('conditions'=>array('bd_activo'=>1)));

		//$datos['idiomas'] = dropdown_options($idiomas['data'], 'lang_id', 'lang_idioma');
		//$datos['estados_publicacion'] = dropdown_options($estados_publicacion['data'], 'est_pub_id', 'est_pub_nombre');
		//$datos['bases_datos'] = dropdown_options($bases_datos['data'], 'bd_id', 'bd_nombre');
		//$datos['order_columns'] = array('base_datos'=>'Base de datos', 'issn'=>'ISSN', 'idioma'=>'Idioma', 'titulo'=>'Título');
$datos['CtllCampos']=array("matricula","pat","mat","nom","nom_nombre","nom_depto_adscripcion","numred","calificacion");
		$template['menu'] = $this->load->view('template/menu', null, TRUE);

		$template['main_content'] = $this->load->view('reportes/cdpi_pdf.tpl.php', $datos, TRUE);
		$this->template->template_conricyt($template);
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
				$datos_busqueda = $this->input->post(); //Datos del formulario se envían para generar la consulta
				$datos_busqueda['current_row'] = (isset($current_row) && !empty($current_row)) ? $current_row : 0; //Registro actual, donde inicia la visualización de registros
				$data = $this->publicacion->listado($datos_busqueda); //Obtener datos
				$data['current_row'] = $datos_busqueda['current_row'];
				$data['per_page'] = $this->input->post('per_page'); //Número de registros a mostrar por página
				if($data['total']>0){
					//$data['columns'] = array('ISSN','TÍTULO','IDIOMA','ESTADO','BASES DE DATOS');
					//$this->template_ajax($data, array('form_recurso'=>'#form_publicacion', 'elemento_resultado'=>'#publicacion_resultado'));
					$this->resultado_listado($data, array('form_recurso'=>'#form_publicacion', 'elemento_resultado'=>'#publicacion_resultado')); //Generar listado en caso de obtener datos
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
