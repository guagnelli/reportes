<?php
class MY_Form_validation extends CI_Form_validation{    
	
	function __construct($config = array()){
		parent::__construct($config);
	}

	public function alpha_accent_space($str){
		$exp = '/^[\p{L}- ]*$/u';
		return ( ! preg_match($exp, $str)) ? FALSE : TRUE;
	}

	public function alpha_numeric_accent_space($str){
		$exp = '/^[\p{L}-0123456789 ]*$/u';
		return ( ! preg_match($exp, $str)) ? FALSE : TRUE;
	}

	public function alpha_numeric_accent_space_dot($str){
		$exp = '/^[\p{L}-0123456789,. \s]*$/u';
		return ( ! preg_match($exp, $str)) ? FALSE : TRUE;
	}

	public function issn($str){
		$exp = '/^[\p{L}-0123456789,. \s]*$/u';
		return ( ! preg_match($exp, $str)) ? FALSE : TRUE;
	}

	public function validate_url($url) {
		$url = trim($url);
		$url = stripslashes($url);
		$url = htmlspecialchars($url);
		
		// check address syntax is valid or not(this regular expression also allows dashes in the URL)
		if (!preg_match("/\b(?:(?:https?|ftp):\/\/|www\.)[-a-z0-9+&@#\/%?=~_|!:,.;]*[-a-z0-9+&@#\/%=~_|]/i",$url)) {
			return FALSE;
		} else {
			return TRUE;
		}
	} 
}