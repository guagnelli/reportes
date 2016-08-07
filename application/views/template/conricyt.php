<?php defined('BASEPATH') OR exit('No direct script access allowed'); ?>
<!DOCTYPE html>
<html lang="es">
<head>
	<meta charset="utf-8">
    <?php
    echo meta('X-UA-Compatible', 'IE=edge', 'equiv');
	echo meta('viewport', 'width=device-width, initial-scale=1');
	echo meta('description', 'Sitio Conricyt');
	?>
	<title><?php $title; ?></title>
	<?php
	echo css('bootstrap.css'); //<!-- Bootstrap Core CSS -->
	echo css('dataTables.css');
	echo css('ui/jquery-ui.theme.css');
	echo css('ui/jquery-ui.css');
	echo css('ui/jquery-ui.structure.css');
	//<!-- Custom CSS -->
	echo css('conricyt.css');
	echo css('formulario.css');
	//<!-- Custom Fonts -->
	echo css('font-awesome-4.1.0/css/font-awesome.min.css');
	echo $css_files;
	?>
    <!-- HTML5 Shim and Respond.js IE8 support of HTML5 elements and media queries -->
    <!-- WARNING: Respond.js doesn't work if you view the page via file:// -->
    <!--[if lt IE 9]>
    	<?php echo js("html5shiv.js");
    	echo js("respond.min.js"); ?>
    <![endif]-->	
	<!-- jQuery Version 1.11.0 -->
	<?php 
	echo js("jquery-1.11.0.js");
	echo js("tooltip.js");
	echo js("popover.js");
	echo js("jquery-ui.js");
	echo js("dataTables.js");
	echo js("dataTables-bootstrap.js");	
	echo js("bootstrap.min.js"); //<!-- Bootstrap Core JavaScript -->
	?>
	<script type="text/javascript">
		var img_url_loader = "<?php echo img_url_loader(); ?>";
		var site_url = "<?php echo site_url(); ?>";
		<?php echo $css_script; ?>
	</script>
	<?php 
	echo js("general.js");
	echo $js_files;
	?>
</head>
<body>
	<!-- Header -->
    <div class="container">
        <div class="row">
            <div class="col-lg-3 col-md-3 col-sm-3 col-xs-6 cabeza">
                 <a href="http://www.presidencia.gob.mx/" target="_blank"><?php echo img('presidencia.png', array('class'=>'img-responsive')); ?></a>
            </div>
            <div class="col-lg-2 col-md-2 col-sm-2 col-xs-2 cabeza">
                <a href="http://www.conacyt.mx/" target="_blank"><?php echo img('conricyt.png', array('class'=>'img-responsive',"style"=>"height: 68px; width: 102px")); ?></a>
            </div>
            <div class="col-lg-1 col-md-1 col-sm-1 col-xs-2 cabeza">
                <a href="http://www.imss.gob.mx/" target="_blank"><?php echo img('imss.png', array('class'=>'img-responsive')); ?></a>
            </div>
            <div class="col-lg-6 col-md-6 col-sm-6 col-xs-12 cabeza">                
            </div>
        </div>
    </div>
    <!-- Navigation -->	
    <?php echo $menu; ?>
    <div style="clear:both;"></div>
    <!-- CONTENIDO PRINCIPAL -->
	<div class="colorgraph espacio"></div>
	<div class="container">
        <div class="row">
			<div class="col-lg-12 col-md-12 col-sm-12 col-xs-12 text-left">
			<?php   if($this->session->userdata('usuario_logeado')==TRUE){ ?>
				<label class="text-primary user-name">Bienvenido(a):
                <?php echo " ".htmlentities($this->session->userdata('nombre'). " ".$this->session->userdata('apaterno')." ".$this->session->userdata('amaterno')); ?>
				</label>
			<?php }else{
				echo "<br />";
			}?>	
			</div>
		</div>                           
	</div><!-- /container -->
	
	<div class="container">
        <div class="row">
			<div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
				<?php echo $main_content; ?>
			</div>
		</div>
	</div>
    <div style="clear:both;"></div>
	<!-- Footer -->
	<div class="container">
	    <div class="navbar navbar-inverse navbar-static-bottom footer">
	        <div class="container">
                <p class="navbar-text text-center" style="color:#FFF; font-size:13px;">Copyright © IMSS 2015. Este sitio se visualiza mejor a partir de resoluciones 1024px por 768px con Internet Explorer 11 / <a href="https://www.mozilla.org/es-MX/firefox/new/" target="_blank">Mozilla Firefox 33.0</a> / <a href="http://www.google.com/chrome/" target="_blank">Google Chrome 38.0</a></p>
	        </div><!-- /.container -->
	    </div><!-- /.nav -->
	</div><!-- /.container -->
</body>
</html>