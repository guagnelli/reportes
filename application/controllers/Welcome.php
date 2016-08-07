<?php
defined('BASEPATH') OR exit('No direct script access allowed');
/*
class Welcome extends CI_Controller {

	public function __construct() {
        // Call the CI_Model constructor
        parent::__construct();
        $this->load->helper('url');
        $this->load->library('session');
        $this->load->model('Welcome_model', 'welcome');
    }

	public function index()	{
            
            $this->load->library('form_complete');
            $this->config->load('forms/busqueda');

		$data = $this->welcome->listado();
		$checkcolumns=array();
		foreach ($data['columns'] as $key => $value) { ///Agregar varios checkboxes
			$cc['id']='col_'.$value;
			$cc['type']='checkbox';
			$cc['value']=$value;
			$cc['label']=strtoupper($value);
			$cc['attributes']=array('style'=>'background-color:blue;', "onClick"=>"alert(this.checked);", "name"=>"gC[]");
			$checkcolumns[] = $cc;
		}
		$data_element_form = array( //Enviar datos para crear elementos. Ej: dropdown, multiselect, radios y checkboxes
			'dropdown1'=>array(array('id'=>'dropdown1','type'=>'dropdown','first'=>array(''=>'Seleccione...'),'options'=>array_combine($data['columns'], $data['columns']))),
			'dropdown2'=>array(array('id'=>'dropdown2','type'=>'dropdown','first'=>array(''=>'Seleccione...'),'options'=>array_combine($data['columns'], $data['columns']))),
			'dropdown3'=>array(array('id'=>'dropdown3','type'=>'dropdown','first'=>array(''=>'Seleccione...'),'options'=>array_combine($data['columns'], $data['columns']))),
			'dropdown4'=>array(array('id'=>'dropdown4','type'=>'dropdown','first'=>array(''=>'Seleccione...'),'options'=>array_combine($data['columns'], $data['columns']))),
			'dropdownX'=>array(array('id'=>'dropdownX','type'=>'dropdown','first'=>array(''=>'Seleccione...'),'options'=>array_combine($data['columns'], $data['columns']))),
			'multiselect1'=>array(array('id'=>'multiselect1','type'=>'multiselect','options'=>array_combine($data['columns'], $data['columns']))),
			'radioTemp'=>array(
					array(
						'id'=>'genero1',
						'type'=>'radio',
						'value'=>'F',
						'label'=>'Femenino',
						'attributes'=>array('style'=>'background-color:green;', "onClick"=>"alert('X');", "name"=>"groupRadio")
					),
					array(
						'id'=>'genero2',
						'type'=>'radio',
						'value'=>'M',
						'label'=>'Masculino',
						'attributes'=>array('style'=>'background-color:green;', "onClick"=>"alert('Y');", "name"=>"groupRadio")
					),
					array(
						'id'=>'genero3',
						'type'=>'radio',
						'value'=>'O',
						'label'=>'Otro',
						'attributes'=>array('style'=>'background-color:green;', "onClick"=>"alert(this.checked);", "name"=>"groupRadio")
					)
				),
			'checkcolumns'=>$checkcolumns
		);
		$form = $this->config->item('busqueda_form');
		$search_form = $this->config->item('busqueda_element');
		if($this->input->post()){
			//pr($this->input->post());
			$this->load->library('form_validation');
			$this->form_complete->set_array_validation($search_form);
			$validations = $this->form_complete->get_array_validation();
			//pr($validations);
			$this->form_validation->set_rules($validations);
			if ($this->form_validation->run() == TRUE){
				echo "Yuhuuu!!!";
			}
		}
//$datos['main_content']= $this->form_complete->create_form_elements($form, $search_form, $data_element_form);  
                
                //$uslog = $this->session->userdata('usuario_logeado');
                
                $datos['main_content']= $this->form_complete->create_form_elements($form, $search_form, $data_element_form); 
                
                
                $this->template->template_conricyt($datos);
	}
*/
	/********************************************* Inicio paginación ajax ************************************************/
/*	public function get_data_ajax($current_row=null){
		if($this->input->is_ajax_request()){ //Solo se accede al método a través de una petición ajax
			if(!is_null($this->input->post())){ //Se verifica que se haya recibido información por método post
				$datos_busqueda = $this->input->post(); //Datos del formulario se envían para generar la consulta
				$datos_busqueda['current_row'] = (isset($current_row) && !empty($current_row)) ? $current_row : 0; //Registro actual, donde inicia la visualización
				$data = $this->welcome->listado($datos_busqueda); //Obtener datos
				$data['per_page'] = $this->input->post('per_page'); //Número de registros a mostrar por página
				if($data['total']>0){
					$this->template_ajax($data);
				}
			}
		} else {
			redirect(site_url());
		}
	}

	private function template_ajax($datos){*/
		/********************************************** Inicio paginación ************************************************/
	/*	$this->load->library(array('pagination', 'table')); 
		$config['base_url'] = site_url(array('welcome', 'get_data_ajax')); //Path que se utilizará en la generación de los links
		$config['total_rows'] = $datos['total']; //Número total de registros
		$config['per_page'] = $datos['per_page']; //Sobreescribir número de registros a mostrar
		$this->pagination->initialize($config);
		$links = "<div class='links'>".$this->pagination->create_links()."</div>";
		*//*********************************************** Fin paginación **************************************************/
		/*
		$template = array('table_open' => '<table border="1" cellpadding="2" cellspacing="1">'); //Creación de tabla con datos
		$this->table->set_template($template);
		$this->table->set_heading($datos['columns']);
		foreach ($datos['data'] as $key => $value) {
			$this->table->add_row($value);
		}

		echo $links.$this->table->generate().$links.'
			<script>
			$(".links a").click(function(event){
		        data_ajax(this);
		        event.preventDefault();
		    });
			</script>';
	}
	*//*********************************************** Fin paginación ajax *************************************************/
/*}
*/