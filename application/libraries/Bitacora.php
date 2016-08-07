<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Bitacora {

	var $sessionData;

	public function __construct() {
    	$this->CI =& get_instance();
        $this->CI->load->model('Bitacora_model');
        $this->sessionData = $this->CI->session->userdata();
    }
	
	/**
	 * 
	 * @param 	array $params Arreglo con parametros esperados: modulo(), modulo_url(), accion()
	 * 
	 **/
	public function bitacora_insertar($params=array()){
		$bitacora = $this->formato_bitacora($params);
		$this->CI->Bitacora_model->insertar($bitacora);
	}

	public function bitacora_login_insertar($matricula){
		$bitacora = $this->formato_bitacora_login($matricula);
		$this->CI->Bitacora_model->insertar_login($bitacora);
	}

	private function formato_bitacora($data=array()){
		$bitacora = new Bitacora_dao();
		$bitacora->usr_matricula = $this->sessionData['matricula'];
		//$bitacora->bi_fecha = date('Y-m-d h:i:s');
		$bitacora->bi_ip = $this->get_ip_address();
		$bitacora->bi_modulo = (array_key_exists('modulo', $data)) ? $data['modulo'] : current_url();
		$bitacora->bi_modulo_url = (array_key_exists('modulo_url', $data)) ? $data['modulo_url'] : current_url();
		$bitacora->bi_accion = (array_key_exists('accion', $data)) ? $data['accion'] : NULL;

		return $bitacora;
	}

	private function formato_bitacora_login($matricula){
        $bitacora = new Bitacora_login_dao();
        $bitacora->usr_matricula = $matricula;
        $bitacora->bi_ip = $this->get_ip_address();

        return $bitacora;
    }

	private function get_ip_address() {
	    $ip_keys = array('HTTP_CLIENT_IP', 'HTTP_X_FORWARDED_FOR', 'HTTP_X_FORWARDED', 'HTTP_X_CLUSTER_CLIENT_IP', 'HTTP_FORWARDED_FOR', 'HTTP_FORWARDED', 'REMOTE_ADDR');
	    foreach ($ip_keys as $key) {
	        if (array_key_exists($key, $_SERVER) === true) {
	            foreach (explode(',', $_SERVER[$key]) as $ip) {
	                // trim for safety measures
	                $ip = trim($ip);
	                // attempt to validate IP
	                if ($this->validate_ip($ip)) {
	                    return $ip;
	                }
	            }
	        }
	    }

	    return isset($_SERVER['REMOTE_ADDR']) ? $_SERVER['REMOTE_ADDR'] : false;
	}

	/**
	 * Ensures an ip address is both a valid IP and does not fall within
	 * a private network range.
	 */
	private function validate_ip($ip) {
	    if (filter_var($ip, FILTER_VALIDATE_IP, FILTER_FLAG_IPV4 | FILTER_FLAG_NO_PRIV_RANGE | FILTER_FLAG_NO_RES_RANGE) === false) {
	        return false;
	    }
	    return true;
	}
}

class Bitacora_dao{
    public $usr_matricula;
    //public $bi_fecha;
    public $bi_ip;
    public $bi_modulo;
    public $bi_modulo_url;
    public $bi_accion;
}

class Bitacora_login_dao{
    public $usr_matricula;
    //public $bi_fecha;
    public $bi_ip;
}