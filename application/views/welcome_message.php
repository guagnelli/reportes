<?php
defined('BASEPATH') OR exit('No direct script access allowed');
?><!DOCTYPE html>
<html lang="en">
<head>
	<meta charset="utf-8">
	<title>Welcome to CodeIgniter</title>

	<style type="text/css">

	::selection { background-color: #E13300; color: white; }
	::-moz-selection { background-color: #E13300; color: white; }

	body {
		background-color: #fff;
		margin: 40px;
		font: 13px/20px normal Helvetica, Arial, sans-serif;
		color: #4F5155;
	}

	a {
		color: #003399;
		background-color: transparent;
		font-weight: normal;
	}

	h1 {
		color: #444;
		background-color: transparent;
		border-bottom: 1px solid #D0D0D0;
		font-size: 19px;
		font-weight: normal;
		margin: 0 0 14px 0;
		padding: 14px 15px 10px 15px;
	}

	code {
		font-family: Consolas, Monaco, Courier New, Courier, monospace;
		font-size: 12px;
		background-color: #f9f9f9;
		border: 1px solid #D0D0D0;
		color: #002166;
		display: block;
		margin: 14px 0 14px 0;
		padding: 12px 10px 12px 10px;
	}

	#body {
		margin: 0 15px 0 15px;
	}

	p.footer {
		text-align: right;
		font-size: 11px;
		border-top: 1px solid #D0D0D0;
		line-height: 32px;
		padding: 0 10px 0 10px;
		margin: 20px 0 0 0;
	}

	#container {
		margin: 10px;
		border: 1px solid #D0D0D0;
		box-shadow: 0 0 8px #D0D0D0;
	}
	</style>
	<?php echo js("jquery-1.10.2.min.js"); ?>
	<script type="text/javascript">
		
		var formulario = "#form_search";
		
		$( document ).ready(function() {
			data_ajax(site_url+"/welcome/get_data_ajax");

			$("#btnEnviar").click(function(event){
		        data_ajax(site_url+"/welcome/get_data_ajax");
		        event.preventDefault();
		    });
		});

		function data_ajax(path){
			var elemento = "#main";
			//validar();
			$.ajax({
				url: path,
				data: $(formulario).serialize(),
				method: 'POST',
				beforeSend: function( xhr ) {
					$(elemento).html(create_loader());
				}
			})
			.done(function(response) {
				$(elemento).html(response);
			})
			.fail(function( jqXHR, textStatus ) {
				$(elemento).html("Ocurrió un error durante el proceso, inténtelo más tarde.");
			})
			.always(function() {
				remove_loader();
			});
		}

		//function validar(){	}

		function create_loader(){
			return '<div id="ajax_loader"><img src="'+img_url_loader+'" alt="Cargando..." title="Cargando..." /></div>';
		}

		function remove_loader(){
			$("#ajax_loader").remove();
		}
	</script>
</head>
<body>

<div id="container">
	<h1>Welcome to CodeIgniter!</h1>

	<div id="body">
		<p>The page you are looking at is being generated dynamically by CodeIgniter.</p>

		<p>If you would like to edit this page you'll find it located at:</p>
		<code>application/views/welcome_message.php</code>

		<p>The corresponding controller for this page is found at:</p>
		<code>application/controllers/Welcome.php</code>

		<p>If you are exploring CodeIgniter for the very first time, you should start by reading the <a href="user_guide/">User Guide</a>.</p>
	</div>
	<form id="form_search" name="form_search" method="POST">
		<label>Búsqueda en título: <input id="search" name="search" /></label>
		<label>Número de registro: <select id="per_page" name="per_page">
				<option value="10">10</option>
				<option value="20">20</option>
				<option value="50">50</option>
				<option value="100">100</option>
			</select></label>
		<label>Orden: <select id="order" name="order">
				<?php foreach ($columns_title as $key => $value) {
					echo '<option value="'.$value.'">'.$value.'</option>';
				} ?>
			</select></label>
		<label>Orden type: <select id="order_type" name="order_type">
				<option value="asc">ASC</option>
				<option value="desc">DESC</option>
			</select></label>
		<label><input type="button" id="btnEnviar" name="btnEnviar" value="Enviar" /></label>
	</form>

	<div id="formulario_busqueda" style="background-color:yellow; padding:20px;"><?php echo $search_form; ?></div>

	<div id="main">--</div>

	<p class="footer">Page rendered in <strong>{elapsed_time}</strong> seconds. <?php echo  (ENVIRONMENT === 'development') ?  'CodeIgniter Version <strong>' . CI_VERSION . '</strong>' : '' ?></p>
</div>

</body>
</html>