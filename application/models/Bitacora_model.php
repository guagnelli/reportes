<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Bitacora_model extends CI_Model {
	public function __construct() {
        // Call the CI_Model constructor
        parent::__construct();
        $this->load->database();
    }


    /**
     * Método que almacena 
     * @access 	public
     * @param   array   $params     Arreglo con parametro módulo, acción y url del módulo
    **/
    public function insertar($bitacora) {
        $this->db->insert('bitacora', $bitacora);
    }

    function insertar_login($bitacora){
      $this->db->insert('bitacora_login', $bitacora);
  }
}
