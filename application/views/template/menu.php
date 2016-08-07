<?php defined('BASEPATH') OR exit('No direct script access allowed'); ?>
<!-- Navigation -->
<nav class="navbar navbar-default" role="navigation">
    <div class="container">
        <!-- Brand and toggle get grouped for better mobile display -->
        <div class="navbar-header">
            <button type="button" class="navbar-toggle" data-toggle="collapse" data-target="#bs-example-navbar-collapse-1">
                <span class="sr-only">Toggle navigation</span>
                <span class="icon-bar"></span>
                <span class="icon-bar"></span>
                <span class="icon-bar"></span>
            </button>
            <!--<a class="navbar-brand" href="index.html">Inicio</a>-->
        </div>
        <!-- Collect the nav links, forms, and other content for toggling -->
        <div class="collapse navbar-collapse" id="bs-example-navbar-collapse-1">
            <ul class="nav navbar-nav navbar-left">
                <li>
                    <a href="#"> <span class="glyphicon glyphicon-home"></span> Inicio</a>
                </li>
                <?php
                //if($this->session->userdata('usuario_logeado')) echo "OK";
                
                ?>
                <!-- si el usuario esta logeado mostrar el sigiente menu
                
                <li>
                    <a href="formulario_2.html">Buscador de publicaciones</a>
                </li>
                <li>
                    <a href="formulario_1.html">Gestión de revisiones</a>
                </li>
                <li>
                    <a href="login.html" target="_blank">Iniciar session</a>
                </li>-->
                <!-- si el usuario esta logeado-->
                <li class="active">
                    <a href="index.html" target="_blank">Ir al sitio Web</a>
                </li>
            </ul>
        </div>
        <!-- /.navbar-collapse -->
    </div>
    <!-- /.container -->
</nav>