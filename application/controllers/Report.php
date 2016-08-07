<?php
defined('BASEPATH') OR exit('No direct script access allowed');

/**
 * Clase que gestiona las publicaciones
 * @version 	: 1.0.0
 * @autor 		: Jesús Díaz P.
 */
class Report extends CI_Controller {
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
    
    public function index(){
        $this->load->library('twig');
        $data['title'] = "twig loaded";
        $this->twig->display('template/reportes', $data);

    }
}
