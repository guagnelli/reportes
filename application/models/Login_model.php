<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Login_model extends CI_Model {
	public function __construct() {
        // Call the CI_Model constructor
        parent::__construct();
        $this->config->load('general');
       // $this->load->database();
  }
  
  function check_usuario($usr){
        $resultado = array('result'=>false, 'data'=>null);
        $this->db->where('usr_activo', $this->config->item('usuario_activo')); //Usuario activo
        $this->db->where("sha2(usr_matricula, 512)='".$usr."'");
        $this->db->limit(1);
        //$this->db->where('usr_passwd', $pass);
        $query = $this->db->get('usuario'); //Obtener conjunto de registros
        if ($query->num_rows()==1){
            $resultado['result'] = true;
            $resultado['data'] = $query->row();
        }
        return $resultado;
  }

  function check_brute_attempts($usr, $lapso_intentos){
      $this->db->select('fecha');
      $this->db->where('usr_matricula', $usr);
      $this->db->where("fecha > now() - ".$lapso_intentos);
      $query = $this->db->get('inicio_sesion_intentos'); //Obtener número de registros
      /*pr($this->db->last_query());
      pr($query->num_rows());
      exit();*/
      return $query->num_rows();
  }

  function intento_fallido($matricula){
      $intento['usr_matricula'] = $matricula;
      $this->db->insert('inicio_sesion_intentos', $intento);
  }
}
