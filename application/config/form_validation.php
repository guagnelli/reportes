<?php  if ( ! defined('BASEPATH')) exit('No direct script access allowed');

/*
|--------------------------------------------------------------------------
| 
|--------------------------------------------------------------------------
|
*/
$config = array(
	'inicio_sesion' => array(
		array(
			'field'=>'matricula',
			'label'=>'Matrícula',
			'rules'=>'required|max_length[18]|alpha_dash'
		),
		array(
			'field'=>'passwd',
			'label'=>'Contraseña',
			'rules'=>'required'
		),
		array(
			'field'=>'userCaptcha',
			'label'=>'Código de verificación',
			'rules'=>'required|callback_check_captcha'
		),
	),
	'publicacion_formulario' => array(
		array(
			'field'=>'responsable',
			'label' => 'Responsable',
			'rules'=>'required|alpha_dash|callback_check_user'
		),
		array(
			'field'=>'estado_publicacion',
			'label' => 'Estado de publicación',
			'rules'=>'required'
		),
		/*array(
			'field'=>'titulos[]',
			'label'=>'Títulos',
			'rules'=>'required|alpha_numeric_spaces'
		),*/
		array(
			'field'=>'issn',
			'label'=>'ISSN',
			'rules'=>'required|alpha_dash|max_length[9]'
		),
		array(
			'field'=>'issne',
			'label'=>'ISSNE',
			'rules'=>'alpha_dash|max_length[9]'
		),
		array(
			'field'=>'issnp',
			'label'=>'ISSNP',
			'rules'=>'alpha_dash|max_length[9]'
		),
		array(
			'field'=>'issnl',
			'label'=>'ISSNL',
			'rules'=>'alpha_dash|max_length[9]'
		),
		array(
			'field'=>'periodicidad',
			'label'=>'Periodicidad',
			'rules'=>'alpha_numeric_accent_space'
		),
		/*array(
			'field'=>'tematica[]',
			'label'=>'Temática',
			'rules'=>'required'
		),
		array(
			'field'=>'bd[]',
			'label'=>'Base de datos',
			'rules'=>'required'
		),
		array(
			'field'=>'bd_url[]',
			'label'=>'URL de la base de datos',
			'rules'=>'valid_url'
		),*/
		array(
			'field'=>'R_anio',
			'label'=>'Año reciente',
			'rules'=>'required|integer|exact_length[4]'
		),
		array(
			'field'=>'R_volumen',
			'label'=>'Volúmen reciente',
			'rules'=>'required|integer|max_length[4]'
		),
		array(
			'field'=>'R_numero',
			'label'=>'Número reciente',
			'rules'=>'required|integer|max_length[4]'
		),
		array(
			'field'=>'A_anio',
			'label'=>'Año antiguo',
			'rules'=>'required|integer|exact_length[4]'
		),
		array(
			'field'=>'A_volumen',
			'label'=>'Volúmen antiguo',
			'rules'=>'required|integer|max_length[4]'
		),
		array(
			'field'=>'A_numero',
			'label'=>'Número antiguo',
			'rules'=>'required|integer|max_length[4]'
		),
		array(
			'field'=>'fuentes',
			'label'=>'Fuentes',
			'rules'=>'alpha_numeric_accent_space_dot|max_length[4000]'
		),
		array(
			'field'=>'notas',
			'label'=>'Notas',
			'rules'=>'alpha_numeric_accent_space_dot|max_length[200]'
		)
	),
	'validacion_formulario' => array(
		array(
			'field'=>'tipo_busqueda',
			'label'=>'Tipo de buscador',
			'rules'=>'required'
		),
		array(
			'field'=>'criterio_busqueda',
			'label'=>'Criterio de búsqueda',
			'rules'=>'required'
		),
		array(
			'field'=>'estado_revision',
			'label'=>'Estado de la revisión',
			'rules'=>'required'
		),
		array(
			'field'=>'disponible',
			'label'=>'Disponible en',
			'rules'=>'validate_url|max_length[255]'
		),
		array(
			'field'=>'notas',
			'label'=>'Notas',
			'rules'=>'alpha_numeric_accent_space_dot|max_length[4000]'
		),
		array(
			'field'=>'R_anio',
			'label'=>'Año reciente',
			'rules'=>'required|integer|exact_length[4]|greater_than[1900]|less_than[2100]',
			'errors' => array(
                'integer'=>'Debe contener un año válido.',
            ),
		),
		array(
			'field'=>'R_volumen',
			'label'=>'Volúmen reciente',
			'rules'=>'required|integer|max_length[4]'
		),
		array(
			'field'=>'R_numero',
			'label'=>'Número reciente',
			'rules'=>'required|integer|max_length[4]'
		),
		array(
			'field'=>'A_anio',
			'label'=>'Año antiguo',
			'rules'=>'required|integer|exact_length[4]|greater_than[1900]|less_than[2100]',
			'errors' => array(
                'integer'=>'Debe contener un año válido.',
            ),
		),
		array(
			'field'=>'A_volumen',
			'label'=>'Volúmen antiguo',
			'rules'=>'required|integer|max_length[4]'
		),
		array(
			'field'=>'A_numero',
			'label'=>'Número antiguo',
			'rules'=>'required|integer|max_length[4]'
		)
	)
);
/*$config['publicacion_formulario'] = array(
	array(
		'#responsable'=>array(
			'id'=>'responsable',
			'validation'=>'required'
		),
		'#estado_publicacion'=>array(
			'id'=>'estado_publicacion',
			'validation'=>'required'
		),
		'#titulos'=>array(
			'id'=>'titulos[]',
			'validation'=>array(
				array('rules'=>'required'),
				//array('rules'=>'alpha_numeric_spaces', 'errors'=>'Campo {field} solo permite letras...')
				array('rules'=>'alpha_numeric_spaces')
			)
		),
		'#issn'=>array(
			'id'=>'issn',
			'validation'=>array(array('rules'=>'required'))
		),
		'#issne'=>array(
			'id'=>'issne',
			'validation'=>array(array('rules'=>'alpha_dash'))
		),
		'#issnp'=>array(
			'id'=>'issnp',
			'validation'=>array(array('rules'=>'alpha_dash'))
		),
		'#issnl'=>array(
			'id'=>'issnl',
			'validation'=>array(array('rules'=>'alpha_dash'))
		),
		'#periodicidad'=>array(
			'id'=>'periodicidad',
			'validation'=>array(array('rules'=>'alpha', 'errors'=>'Campo {field} solo permite letras...'))
		),
		'#tematica'=>array(
			'id'=>'tematica[]',
			'validation'=>array(array('rules'=>'required'))
		),
		'#bd'=>array(
			'id'=>'bd[]',
			'validation'=>array(array('rules'=>'required'))
		),
		'#bd_url'=>array(
			'id'=>'bd_url[]',
			'validation'=>array(array('rules'=>'valid_url'))
		),
		'#R_anio'=>array(
			'id'=>'R_anio',
			'validation'=>array(array('rules'=>'required'),
				array('rules'=>'integer'))
		),
		'#R_volumen'=>array(
			'id'=>'R_volumen',
			'validation'=>array(array('rules'=>'required'),
				array('rules'=>'integer'))
		),
		'#R_numero'=>array(
			'id'=>'R_numero',
			'validation'=>array(array('rules'=>'required'),
				array('rules'=>'integer'))
		),
		'#A_anio'=>array(
			'id'=>'A_anio',
			'validation'=>array(array('rules'=>'required'),
				array('rules'=>'integer'))
		),
		'#A_volumen'=>array(
			'id'=>'A_volumen',
			'validation'=>array(array('rules'=>'required'),
				array('rules'=>'integer'))
		),
		'#A_numero'=>array(
			'id'=>'A_numero',
			'validation'=>array(array('rules'=>'required'),
				array('rules'=>'integer'))
		),
		'#fuentes'=>array(
			'id'=>'fuentes',
			'validation'=>array(array('rules'=>'alpha_numeric_spaces'))
		),
		'#notas'=>array(
			'id'=>'notas',
			'validation'=>array(array('rules'=>'alpha_numeric_spaces'))
		)
	)
);*/
