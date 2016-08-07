<?php
defined('BASEPATH') OR exit('No direct script access allowed');

/**
 * Clase que contiene métodos para la carga de la plantilla base del sistema y creación de la paginación
 * @version 	: 1.0.0
 * @autor 		: Jesús Díaz P.
 */
class Template {

	public function __construct() {
    	$this->CI =& get_instance();
        $this->CI->load->helper('html');
        $this->CI->load->library('session');
        
    }

    /**
     * Método que carga la plantilla base del sistema
     * @autor 		: Jesús Díaz P.
	 * @modified 	: 
	 * @access 		: public
	 * @param 		: mixed[] $elements Elementos configurables en la plantilla
     */
	public function template_conricyt($elements=array()){
		$elements['title'] = (array_key_exists('title', $elements)) ? $elements['title'] : 'CONRICYT';
		$elements['css_files'] = (array_key_exists('css_files', $elements)) ? $elements['css_files'] : null;
		$elements['js_files'] = (array_key_exists('js_files', $elements)) ? $elements['js_files'] : null;
		$elements['css_script'] = (array_key_exists('css_script', $elements)) ? $elements['css_script'] : null;
		$elements['menu'] = $this->templete_menu();//(array_key_exists('menu', $elements)) ? $elements['menu'] : null;
		$elements['main_content'] = (array_key_exists('main_content', $elements)) ? $elements['main_content'] : null;
		$this->CI->load->view('template/conricyt', $elements);
	}

	public function template_buscador($elements=array()){
		$elements['css_files'] = (array_key_exists('css_files', $elements)) ? $elements['css_files'] : null;
		$elements['js_files'] = (array_key_exists('js_files', $elements)) ? $elements['js_files'] : null;
		$elements['css_script'] = (array_key_exists('css_script', $elements)) ? $elements['css_script'] : null;
		//$elements['menu'] = $this->templete_menu();
		$elements['main_content'] = (array_key_exists('main_content', $elements)) ? $elements['main_content'] : null;
		$this->CI->load->view('template/buscador', $elements);
	}
    
    public function templete_menu() {
        $logeado = $this->CI->session->userdata('usuario_logeado');
        if($logeado === true){
            $menu_templete = $this->CI->load->view('template/menu_admin', null, TRUE);
            return $menu_templete;
        }else{
            $menu_templete = $this->CI->load->view('template/menu', null, TRUE);
            return $menu_templete;
            
        }            
    }
        
        
        
        /**
     * Método que crea links de paginación y mensaje sobre registros mostrados
     * @autor 		: Jesús Díaz P.
	 * @modified 	: 
	 * @access 		: public
	 * @param 		: mixed[] $pagination_data Parámetros usados para generar las ligas
	 * @return 		: midex[] links -> Ligas para la paginación
	 *						total -> Mensaje sobre registros mostrados
     */
	public function pagination_data($pagination_data){
		$this->CI->load->library(array('pagination', 'table')); 
		$config['base_url'] = site_url(array('publicacion', 'get_data_ajax')); //Path que se utilizará en la generación de los links
		$config['total_rows'] = $pagination_data['total']; //Número total de registros
		$config['per_page'] = $pagination_data['per_page']; //Sobreescribir número de registros a mostrar
		$this->CI->pagination->initialize($config);
		
		return array('links'=>"<div class='dataTables_paginate paging_simple_numbers'>".$this->CI->pagination->create_links()."</div>",
				'total'=>"Mostrando ".($pagination_data['current_row']+1)." a ".((($pagination_data['current_row']+$config['per_page'])>$pagination_data['total']) ? $pagination_data['total'] : $pagination_data['current_row']+$config['per_page'])." de ".$pagination_data['total']
			);
	}
        
        public function pagination_data_buscador($pagination_data){
		$this->CI->load->library(array('pagination', 'table')); 
		$config['base_url'] = site_url(array('buscador', 'get_data_ajax')); //Path que se utilizará en la generación de los links
		$config['total_rows'] = $pagination_data['total']; //Número total de registros
		$config['per_page'] = $pagination_data['per_page']; //Sobreescribir número de registros a mostrar
		$this->CI->pagination->initialize($config);
		
		return array('links'=>"<div class='dataTables_paginate paging_simple_numbers'>".$this->CI->pagination->create_links()."</div>",
				'total'=>"Mostrando ".($pagination_data['current_row']+1)." a ".((($pagination_data['current_row']+$config['per_page'])>$pagination_data['total']) ? $pagination_data['total'] : $pagination_data['current_row']+$config['per_page'])." de ".$pagination_data['total']
			);
	}
}
