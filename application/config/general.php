<?php  if ( ! defined('BASEPATH')) exit('No direct script access allowed');
/////Usuario activo
$config['usuario_activo'] = 1; //

/////Ruta de evidencias
$config['ruta_revision'] = asset_path()."/files/revision/";

/////Extensiones permitidas en evidencias
$config['extension_revision'] = array('pdf','jpg','jpeg');

/////Tamaño máximo de archivos, dado en KB
$config['max_size_revision'] = 2048; // = 4MB

$config['tiempo_fuerza_bruta'] = 60 * 60; //3600 = 1 hora => Tiempo válido para chequeo de fuerza bruta
$config['intentos_fuerza_bruta'] = 5; ///Número de intentos válidos durante tiempo 'tiempo_fuerza_bruta'

/////Tipos de formatos correspondientes a la publicación
$config['publicacion_formato'] = array(
	'E'=>'Electrónico'
);

/////Tipos de cobertura
$config['publicacion_cobertura'] = array('antiguo'=>array('id'=>'A','text'=>'Antiguo'), 
	'reciente'=>array('id'=>'R','text'=>'Reciente')
);

/////Matrícula de super administrador
$config['usuario_admin'] = array(
	'matricula'=>'111111111'
);

/////Tipos de revisiones
$config['publicacion_tipo_revision'] = array('interna'=>array('id'=>'I','text'=>'Interna','url'=>'revision_interna'), 
	'remota'=>array('id'=>'E','text'=>'Remota','url'=>'revision_remota')
);

/////Validación estados
$config['validacion_estado'] = array('correcta'=>array(1, 4), 'incorrecto'=>array(2,3));

/////Estado de revisión
$config['revision_estado'] = array('completa'=>1, 'incompleta'=>0);

/////Estado de publicación
$config['publicacion_estado'] = array('revisada'=>1, 'pendiente'=>2);

/////Identificador usado para diferenciar inserciones
$config['id_insercion'] = 0;
