<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Catalogos extends CI_Controller {

	public function __construct()
	{
		parent::__construct();

		$this->load->database();
		$this->load->helper('url');
		$this->load->library('grocery_CRUD');
		$this->load->library('form_complete');//esta libreria es para 
	}

	public function _mostrar($output = null)
	{
		$template['menu'] = $this->load->view('template/menu', null, TRUE);
		$template['main_content'] = $this->load->view('catalogo/mostrar_form.php',$output,TRUE);
		$this->template->template_conricyt($template);
	}

	

	public function cat_base_datos()
	{
		$crud = new grocery_CRUD;
		$crud->set_table('base_datos');
		$crud->columns('bd_nombre','bd_activo');
		$crud->change_field_type('bd_activo', 'true_false');
		$crud->display_as('bd_nombre','Descripción de la base de datos');
		$crud->display_as('bd_activo','Estatus de base de datos');
		$crud->set_subject('Una base de datos');
		$crud->required_fields('bd_nombre','bd_activo');
		$crud->unset_delete();
		$output = $crud->render();
		$this->_mostrar($output);
        
	}

		public function cat_busqueda()
	{
		$crud = new grocery_CRUD;
		$crud->set_table('busqueda');
		$crud->columns('bsq_nombre','bsq_orden','bsql_activo');
		$crud->change_field_type('bsql_activo', 'true_false');
		$crud->display_as('bsq_nombre','Nombre de la búsqueda');
		$crud->display_as('bsq_orden','Orden de la búsqueda');
		$crud->display_as('bsql_activo','Estatus de búsqueda');
		$crud->required_fields('bsq_nombre','bsq_orden','bsql_activo');
		$crud->unset_delete();
		$crud->set_subject('Un tipo de búsqueda');		
		$output = $crud->render();
		$this->_mostrar($output);
        
	}

			public function cat_criterio()
	{
		$crud = new grocery_CRUD;
		$crud->set_table('criterio');
		$crud->columns('cri_nombre','cri_activo','bsq_id');
		$crud->change_field_type('cri_activo', 'true_false');
		$crud->set_relation('bsq_id','pd_busqueda','bsq_nombre');
		$crud->display_as('bsq_id','Tipo busqueda');
		$crud->display_as('cri_nombre','Descripción del Criterio');
		$crud->display_as('cri_activo','Estatus del criterio');
		$crud->required_fields('cri_nombre','cri_activo','bsq_id');	
		$crud->unset_delete();
		$crud->set_subject('Un tipo de criterio');
		$output = $crud->render();
		$this->_mostrar($output);
        
	}

			public function cat_editor_comercial()
	{
		$crud = new grocery_CRUD;
		$crud->set_table('editor_comercial');
		$crud->columns('ec_nombre','ec_activo');
		$crud->change_field_type('ec_activo', 'true_false');
		$crud->display_as('ec_nombre','Descripción del editor comercial');
		$crud->display_as('ec_activo','Estatus del editor comercial');
		$crud->required_fields('ec_nombre','ec_activo');
		$crud->unset_delete();
		$crud->set_subject('Un editor comercial');
		$output = $crud->render();
		$this->_mostrar($output);
        
	}

			public function cat_estado_val()
	{
		$crud = new grocery_CRUD;
		$crud->set_table('estado_val');
		$crud->columns('est_nombre');
		$crud->edit_fields('est_nombre');
		$crud->display_as('est_nombre','Descripción del estado de validación');
		$crud->required_fields('est_nombre');
		$crud->unset_delete();
        $crud->set_subject('Un estado de validación');
        $output = $crud->render();
		$this->_mostrar($output);
        
	}

			public function cat_idioma()
	{
		$crud = new grocery_CRUD;
		$crud->set_table('idioma');
		$crud->columns('lang_idioma','lang_activo');
		$crud->change_field_type('lang_activo', 'true_false');
		$crud->display_as('lang_idioma','Descripción del idioma');
		$crud->display_as('lang_activo','Estatus del idioma');
		$crud->required_fields('lang_idioma','lang_activo');
		$crud->unset_delete();
		$crud->set_subject('Un tipo de idioma');
		$output = $crud->render();
		$this->_mostrar($output);
        
	}
			public function cat_pais()
	{
		$crud = new grocery_CRUD;
		$crud->set_table('pais');
		$crud->columns('pais_codigo','pais_nombre','pais_activo');
		$crud->change_field_type('pais_activo', 'true_false');
		$crud->display_as('pais_codigo','Código del país');
		$crud->display_as('pais_nombre','Descripción del país');
		$crud->display_as('pais_activo','Estatus del país');
		$crud->required_fields('pais_codigo','pais_nombre','pais_activo');	
		$crud->unset_delete();
		$crud->set_subject('Un tipo de idioma');
		$output = $crud->render();
		$this->_mostrar($output);
        
	}
			public function cat_tema()
	{
		$crud = new grocery_CRUD;
		$crud->set_table('tema');
		$crud->columns('t_nombre','t_activo');
		$crud->change_field_type('t_activo', 'true_false');
		$crud->display_as('t_nombre','Descripción del tema');
		$crud->display_as('t_activo','Estatus del tema');
		$crud->required_fields('t_nombre','t_activo');
		$crud->unset_delete();
		$crud->set_subject('Un tipo de tema');
		$output = $crud->render();
		$this->_mostrar($output);
        
	}
			public function cat_estado_publicacion()
	{
		$crud = new grocery_CRUD;
		$crud->set_table('estado_publicacion');
		$crud->columns('est_pub_nombre');
		$crud->display_as('est_pub_nombre','Descripción del estado de la publicación');
		$crud->required_fields('est_pub_nombre');
		$crud->unset_delete();
		$crud->set_subject('Un estado de la publicación');
		$output = $crud->render();
		$this->_mostrar($output);
        
	}


	public function index()
	{
		$this->_mostrar((object)array('output' => '' , 'js_files' => array() , 'css_files' => array()));
	}

	
}