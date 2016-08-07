<!DOCTYPE html>
<html>
<head>
	<meta charset="utf-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
<?php 
foreach($css_files as $file): ?>
	<link type="text/css" rel="stylesheet" href="<?php echo $file; ?>" />
<?php endforeach; ?>
<?php foreach($js_files as $file): ?>
	<script src="<?php echo $file; ?>"></script>
<?php endforeach; ?>
<style type='text/css'>
body
{
	font-family: Arial;
	font-size: 14px;
}
a {
    color: blue;
    text-decoration: none;
    font-size: 14px;
}
a:hover
{
	text-decoration: underline;
}
</style>
</head>
<body>
	<div>
		<!-- <a href='<?php echo site_url('Catalogos/cat_base_datos')?>'>-Base de datos-</a> |
		<a href='<?php echo site_url('Catalogos/cat_busqueda')?>'>-Busqueda-</a> |
		<a href='<?php echo site_url('Catalogos/cat_criterio')?>'>-Criterio-</a> |
		<a href='<?php echo site_url('Catalogos/cat_editor_comercial')?>'>-Editor Comercial-</a> | 
		<a href='<?php echo site_url('Catalogos/cat_estado_val')?>'>-Estado de validacion-</a> |		 
		<a href='<?php echo site_url('Catalogos/cat_idioma')?>'>-Idioma-</a> |
		<a href='<?php echo site_url('Catalogos/cat_pais')?>'>-Pais-</a> |
		<a href='<?php echo site_url('Catalogos/cat_tema')?>'>-Tema-</a> |
		<a href='<?php echo site_url('Catalogos/cat_estado_publicacion')?>'>-Estado de publicacion-</a> |linea que hablitan el menu superior-->
		
	</div>
	<div style='height:20px;'></div>  
    <div>
		<?php echo $output; ?>
    </div>
</body>
</html>
