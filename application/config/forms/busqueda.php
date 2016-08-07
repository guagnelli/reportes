<?php  if ( ! defined('BASEPATH')) exit('No direct script access allowed');

/*
|--------------------------------------------------------------------------
| 
|--------------------------------------------------------------------------
|
*/

$config['busqueda_form'] = array('action'=>'welcome/index',
	'multipart'=>TRUE,
	'attributes'=>array('style'=>'background-color: grey;')
);
$config['busqueda_element'] = array(
	array(
		'type'=>'div',
		'attributes'=>array('class'=>'panel panel-turqueza'),
		'#div_head'=>array(
			'type'=>'div',
			'value'=>'Registro de revisión de criterios',
			'attributes'=>array('class'=>'panel-heading clearfix')
		),
		'#div_body'=>array(
			'type'=>'div',
			'attributes'=>array('class'=>'panel-body'),
			'#div'=>array(
				'type'=>'div',
				'attributes'=>array('class'=>'row'),
				'#div'=>array(
					'type'=>'div',
					'attributes'=>array('class'=>'col-lg-4 col-sm-4'),
					'#div'=>array(
						'type'=>'div',
						'attributes'=>array('class'=>'panel-body input-group input-group-sm'),
						'#span'=>array(
							'type'=>'span',
							'value'=>'* Tipo de buscador:',
							'attributes'=>array('class'=>'input-group-addon'),
						),
						'#dropdown'=>array(
							'id'=>'dropdown2',
							'type'=>'dropdown',
							'attributes'=>array('data-placement'=>'top','data-toggle'=>'tooltip','data-original-title'=>'Lorem Ipsum3'),
							'validation'=>array(array('rules'=>'required'))
						)
					)
				),
				'#div2'=>array(
					'type'=>'div',
					'attributes'=>array('class'=>'col-lg-4 col-sm-4'),
					'#div'=>array(
						'type'=>'div',
						'attributes'=>array('class'=>'panel-body input-group input-group-sm'),
						'#span'=>array(
							'type'=>'span',
							'value'=>'* Criterio de búsqueda:',
							'attributes'=>array('class'=>'input-group-addon'),
						),
						'#dropdown'=>array(
							'id'=>'dropdown3',
							'type'=>'dropdown',
							'attributes'=>array('data-placement'=>'top','data-toggle'=>'tooltip','data-original-title'=>'Lorem Ipsum3'),
							'validation'=>array(array('rules'=>'required'))
						)
					)
				),
				'#div3'=>array(
					'type'=>'div',
					'attributes'=>array('class'=>'col-lg-4 col-sm-4'),
					'#div'=>array(
						'type'=>'div',
						'attributes'=>array('class'=>'panel-body input-group input-group-sm'),
						'#span'=>array(
							'type'=>'span',
							'value'=>'* Estado de la publicación:',
							'attributes'=>array('class'=>'input-group-addon'),
						),
						'#dropdown'=>array(
							'id'=>'dropdown4',
							'type'=>'dropdown',
							'attributes'=>array('data-placement'=>'top','data-toggle'=>'tooltip','data-original-title'=>'Lorem Ipsum4'),
							'validation'=>array(array('rules'=>'required'))
						)
					)
				)
			)
		)
	),
	/*array(
		'type'=>'label',
		'value'=>'Mi primera label',
		'#busqueda'=>array(
			'id'=>'busqueda_label',
			'type'=>'text',
			'validation'=>'required'
		)
	),
	array(
		'id'=>'search',
		'type'=>'text',
		'#label'=>array('value'=>'Búsqueda en título', 'type'=>'label'), //, 'attributes'=>array('class'=>'panel-body input-group input-group-sm')
		'value'=>'hola',
		'attributes'=>array('name'=>'search', 'id'=>'ss'),
		'validation'=>array(
			array('rules'=>'required'),
			array('rules'=>'alpha', 'errors'=>'Campo {field} solo permite letras...')
		)
	),
	array(
		'id'=>'oculto',
		'type'=>'hidden',
		'label'=>'Campo oculto:',
		'value'=>'hide_field'
	),
	array(
		'id'=>'search0',
		'type'=>'text',
		'validation'=>array(array('rules'=>'required'))
	),
	array(
		'id'=>'correo',
		'type'=>'text',
		'label'=>'Correo electrónico',
		'value'=>'',
		'validation'=>array(
			array('rules'=>'required'),
			array('rules'=>'valid_email', 'errors'=>'El campo {field} no tiene un formato correcto.'),
			array('rules'=>'min_length[10]', 'errors'=>'Campo {field} debe tener un mínimo de {param} caracteres.'),
			array('rules'=>'max_length[50]', 'errors'=>'Campo {field} debe tener un máximo de {param} caracteres.')
		)
	),
	array(
		'id'=>'no_validacion',
		'type'=>'text',
		'label'=>'No validación',
		'value'=>'',
		'validation'=>array()
	),
	array(
		'id'=>'btnButton',
		'type'=>'button',
		'value'=>'BUtton0',
		'label'=>'Botón con evento click',
		'attributes'=>array('style'=>'background-color:green;', "onClick"=>"alert('hola');", "content"=>"Buutton", 'value'=>'BUtton1')
	),
	array(
		'id'=>'checkbox1',
		'type'=>'checkbox',
		'value'=>'1',
		'label'=>'Checkbox Uno',
		'attributes'=>array('style'=>'background-color:blue;', "onClick"=>"alert(this.checked);", "name"=>"groupCheck[]")
	),
	array(
		'id'=>'checkbox2',
		'type'=>'checkbox',
		'value'=>'2',
		'label'=>'Checkbox Dos',
		'attributes'=>array('style'=>'background-color:blue;', "onClick"=>"alert(this.checked);", "name"=>"groupCheck[]")
	),
	array(
		'id'=>'radio1',
		'type'=>'radio',
		'value'=>'1',
		'label'=>'Radio Uno',
		'attributes'=>array('style'=>'background-color:green;', "onClick"=>"alert(this.checked);", "name"=>"groupRadio")
	),
	array(
		'id'=>'radio2',
		'type'=>'radio',
		'value'=>'2',
		'label'=>'Radio Dos',
		'attributes'=>array('style'=>'background-color:green;', "onClick"=>"alert(this.checked);", "name"=>"groupRadio")
	),
	array(
		'id'=>'contrasenia',
		'type'=>'password',
		'label'=>'Contraseña',
		'attributes'=>array('style'=>'background-color:gray;')
	),
	array(
		'id'=>'textarea1',
		'type'=>'textarea',
		'label'=>'Textarea:',
		//'value'=>'Lorem ipsum...',
		'attributes'=>array('rows'=>5, 'cols'=>40)
	),
	array(
		'id'=>'upload1',
		'type'=>'upload',
		'label'=>'Archivo:',
		'attributes'=>array('style'=>'background-color: pink')
	),
	array(
		'id'=>'dropdown1',
		'type'=>'dropdown',
		'label'=>'Listado:',
		'first'=>array(''=>'Seleccione...'),
		'attributes'=>array('style'=>'background-color: pink;', 'width'=>'200px;')
	),
	array(
		'id'=>'multiselect1',
		'type'=>'multiselect',
		'label'=>'Listado multiple:',
		'first'=>array(''=>'Seleccione...'),
		'options'=>array(''=>'Seleccione...','uno'=>'Uno','dos'=>'Dos','tres'=>'Tres','cuatro'=>'Cuatro','cinco'=>'Cinco','seis'=>'Seis'),
		'attributes'=>array('style'=>'background-color: pink;', 'width'=>'200px;')
	),
	array(
		'id'=>'radioTemp',
		'type'=>'radio'
	),
	array(
		'id'=>'checkcolumns',
		'type'=>'checkbox'
	),*/
	array(
		'id'=>'btnSearch',
		'type'=>'submit',
		'value'=>'SUbmit',
		'attributes'=>array('style'=>'background-color:yellow;')
	)
);
