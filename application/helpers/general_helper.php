<?php  if ( ! defined('BASEPATH')) exit('No direct script access allowed');
// HELPER General
/**
 * Método que preformatea una cadena
 * @autor 		: Jesús Díaz P.
 * @param 		: mixed $mix Cadena, objeto, arreglo a mostrar
 * @return  	: Cadena preformateada
 */
if(!function_exists('pr')){
    function pr($mix){
        echo "<pre>";
        print_r($mix);
        echo "</pre>";
    }
}

/**
 * Método que valida una variable; que exista, no sea nula o vacía
 * @autor 		: Jesús Díaz P.
 * @modified 	: 
 * @param 		: mixed $valor Parámetro a validar
 * @return 		: bool. TRUE en caso de que exista, no sea vacía o nula de lo contrario devolverá FALSE
 */
if(!function_exists('exist_and_not_null')){
	function exist_and_not_null($valor){
		return (isset($valor) && !empty($valor) && !is_null($valor)) ? TRUE : FALSE;
	}
}

/**
 * Método que valida una variable; que exista, no sea nula o vacía
 * @autor 		: Jesús Díaz P.
 * @modified 	: 
 * @param 		: mixed $valor Parámetro a validar
 * @return 		: bool. TRUE en caso de que exista, no sea vacía o nula de lo contrario devolverá FALSE
 */
if(!function_exists('exist_and_not_null_array')){
	function exist_and_not_null_array($arreglo, $llave){
		return (array_key_exists($llave, $arreglo) && !empty($arreglo[$llave]) && !is_null($arreglo[$llave])) ? TRUE : FALSE;
	}
}

/**
 * Método que genera un arreglo asociativo de la forma llave => valor, derivado de un arreglo multidimensional
 * @autor 		: Jesús Díaz P.
 * @modified 	: 
 * @param 		: mixed[] $array_data 
 * @param 		: string $field_key 
 * @param 		: string $field_value 
 * @return 		: mixed[]. TRUE en caso de que exista, no sea vacía o nula de lo contrario devolverá FALSE
 * Ejemplo: $array_multi = array(
 *		array('llave1'=>'valor1.0', 'llave2'=>'valor2.0', 'llave3'=>'valor3.0'),
 *		array('llave1'=>'valor1.1', 'llave2'=>'valor2.1', 'llave3'=>'valor3.1'),
 *		array('llave1'=>'valor1.2', 'llave2'=>'valor2.2', 'llave3'=>'valor3.2'),
 * )
 * dropdown_options($array_multi, 'llave2', 'llave3');
 * Resultado: array(
 *		array('valor2.0'=>'valor3.0'),
 *		array('valor2.1'=>'valor3.1'),
 *		array('valor2.2'=>'valor3.2'),
 * )
 */
if(!function_exists('dropdown_options')){
	function dropdown_options($array_data, $field_key, $field_value){
		$options = array();
		foreach ($array_data as $key => $value) {
			$options[$value[$field_key]] = $value[$field_value];
		}
		return $options;
	}
}

/**
 * Método utilizado para mostrar un mensaje en formato predefinido
 * @autor 		: Jesús Díaz P.
 * @modified 	: 
 * @param 		: string $msg Texto a mostrar
 * @return 		: string Texto con formato predefinido
 */
if(!function_exists('data_not_exist')){
	function data_not_exist($msg=null){
		return '<h2 align="center" style="padding-top:100px; padding-bottom:100px;">'.((exist_and_not_null($msg)) ? $msg : 'No han sido encontrados datos con los criterios seleccionados.').'</h2>';
	}
}

/**
 * Método que crea un elemento div
 * @autor 		: Jesús Díaz P.
 * @modified 	: 
 * @param 		: string $label_text Contenido de la etiqueta div
 * @param 		: mixed[] $attributes Atributos de la etiqueta div
 * @return 		: string Elemento div
 */
if(!function_exists('html_div')){
	function html_div($label_text = '', $attributes = array()){
		$label = '<div';
		if(is_array($attributes) && count($attributes) > 0){
			foreach ($attributes as $key => $val){
				$label .= ' '.$key.'="'.$val.'"';
			}
		}
		return $label.'>'.$label_text.'</div>';
	}
}

/**
 * Método que crea un elemento span
 * @autor 		: Jesús Díaz P.
 * @modified 	: 
 * @param 		: string $label_text Contenido de la etiqueta span
 * @param 		: mixed[] $attributes Atributos de la etiqueta span
 * @return 		: string Elemento span
 */
if(!function_exists('html_span')){
	function html_span($label_text = '', $attributes = array()){
		$label = '<span';
		if(is_array($attributes) && count($attributes) > 0){
			foreach ($attributes as $key => $val){
				$label .= ' '.$key.'="'.$val.'"';
			}
		}
		return $label.'>'.$label_text.'</span>';
	}
}

/**
 * Método que crea un elemento p
 * @autor 		: Jesús Díaz P.
 * @modified 	: 
 * @param 		: string $label_text Contenido de la etiqueta p
 * @param 		: mixed[] $attributes Atributos de la etiqueta p
 * @return 		: string Elemento p
 */
if(!function_exists('html_p')){
	function html_p($label_text = '', $attributes = array()){
		$label = '<p';
		if(is_array($attributes) && count($attributes) > 0){
			foreach ($attributes as $key => $val){
				$label .= ' '.$key.'="'.$val.'"';
			}
		}
		return $label.'>'.$label_text.'</p>';
	}
}

/**
 * Método que crea un elemento number
 * @autor 		: Jesús Díaz P.
 * @modified 	: 
 * @param 		: string $label_text Contenido de la etiqueta number
 * @param 		: mixed[] $attributes Atributos de la etiqueta number
 * @return 		: string Elemento p
 */
if(!function_exists('form_number')){
	function form_number($data = '', $value = '', $extra = ''){
		$defaults = array(
			'type' => 'number',
			'name' => is_array($data) ? '' : $data,
			'value' => $value
		);

		return '<input '._parse_form_attributes($data, $defaults).$extra." />\n";
	}
}

/**
 * Método que codifica una cadena a base 64
 * @autor 		: Jesús Díaz P.
 * @modified 	: 
 * @param 		: string $string Cadena a codificar
 * @return 		: string Cadena codificada
 */
if(!function_exists('encrypt_base64')){
	function encrypt_base64($string){
		//return base64_encode($string); //convert_uuencode($string);
		//return strtr(base64_encode($string), '+/=', '-_*');
		return rtrim(strtr(base64_encode($string), '+/', '-_'), '=');
	}
}

/**
 * Método que decodifica una cadena en base 64
 * @autor 		: Jesús Díaz P.
 * @modified 	: 
 * @param 		: string $string Cadena a decodificar
 * @return 		: string Cadena decodificada
 */
if(!function_exists('decrypt_base64')){
	function decrypt_base64($string){
		//return base64_decode($string); //convert_uudecode($string);
		//return base64_decode(strtr($string, '-_*', '+/='));
		return base64_decode(str_pad(strtr($string, '-_', '+/'), strlen($string) % 4, '=', STR_PAD_RIGHT));
	}
}

/**
 * Método que define una plantilla para los mensajes que mostrará un formulario
 * @autor 		: Jesús Díaz P.
 * @modified 	: 
 * @param 		: string $elemento Nombre del elemento form
 * @param 		: string $tipo Posibles valores('success','info','warning','danger')
 * @return 		: string Mensaje con formato predefinido
 */
if(!function_exists('form_error_format')){
	function form_error_format($elemento, $tipo=null){
		if(is_null($tipo)){
			$tipo='danger';
		}
		return form_error($elemento, '<div class="alert alert-'.$tipo.'" role="alert"><button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button>', '</div>');
	}
}

/**
 * Método que define una plantilla para los mensajes que se mostrarán con bootstrap
 * @autor 		: Jesús Díaz P.
 * @modified 	: 
 * @param 		: string $message Texto a mostrar
 * @param 		: string $tipo Posibles valores('success','info','warning','danger')
 * @return 		: string Mensaje de alerta con formato predefinido
 */
if(!function_exists('html_message')){
	function html_message($message, $tipo=null){
		if(is_null($tipo)){
			$tipo='danger';
		}
		return '<div class="alert alert-'.$tipo.'" role="alert"><button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button>'.$message.'</div>';
	}
}

/**
 * Método que obtiene un listado de archivos de la ruta otorgada
 * @autor 		: Jesús Díaz P.
 * @modified 	: 
 * @param 		: string $path Ruta de donde se obtendrá el listado de archivos
 * @return 		: mixed[] $result Listado de archivos
 */
if(!function_exists('get_files')){
	function get_files($path){
		if(file_exists($path)){
			return scandir($path);
		}
	}
}

// ------------------------------------------------------------------------
if(!function_exists('merge_arrays')){
	function merge_arrays($key, $value){
		return $key .'="'. $value .'" ';
	}
}
/* End of file general_helper.php */
