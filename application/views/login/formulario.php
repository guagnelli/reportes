<?php echo form_open('login/index', array('id'=>'login')); ?>
<div class="row">
    <div class="container">
        <div class="col-md-12 col-sm-12 col-xs-12 text-center">
            <h2 style="margin-top: 10px; margin-bottom: 20px;">Bienvenido al Sistema de Recursos Electrónicos</h2>
        </div>
        <!-- <div class="col-md-6 col-sm-12 col-xs-12 text-center">
            <div class="panel panel-turqueza">
                <div class="panel-body">

                </div>
            </div>
        </div> -->
        <div class="col-md-3 col-sm-12 col-xs-12 text-center"></div>
        <div class="col-md-6 col-sm-12 col-xs-12 text-center">
            <div class="panel panel-turqueza">
                <div class="panel-heading clearfix">
                    <h3 class="panel-title">Iniciar sesi&oacute;n</h3>
                </div>
                <div class="panel-body">
                    <?php if(exist_and_not_null($error)) {
                        echo '<div class="row">
                                <div class="col-md-1 col-sm-1 col-xs-1"></div>
                                <div class="col-md-10 col-sm-10 col-xs-10 alert alert-danger">
                                    '.$error.'
                                </div>
                                <div class="col-md-1 col-sm-1 col-xs-1"></div>
                            </div>';
                    }
                    ?>
                    <div class="row">
                        <div class="col-md-12 col-sm-12 col-xs-12">
                            <div class="panel-body  input-group input-group-sm">
                                <span class="input-group-addon"><div class="text-right" style="width:120px;">Matr&iacute;cula:</div></span>
                                    <?php 
                                    echo $this->form_complete->create_element(array('id'=>'matricula', 'type'=>'text', 'attributes'=>array('class'=>'form-control', 'maxlength'=>'20', 'autocomplete'=>'off', 'placeholder'=>'Matr&iacute;cula', 'data-toggle'=>'tooltip', 'data-placement'=>'top', 'title'=>'Matricula'))); 
                                    ?>
                            </div>
                            <span class="text-danger"> <?php echo form_error('matricula','','');?> </span>
                        </div>
                        <div class="col-md-12 col-sm-12 col-xs-12">
                            <div class="panel-body input-group input-group-sm">
                                <span class="input-group-addon"><div class="text-right" style="width:120px;">Contrase&ntilde;a:</div></span>
                                    <?php 
                                    echo $this->form_complete->create_element(array('id'=>'passwd', 'type'=>'password', 'attributes'=>array('class'=>'form-control', 'autocomplete'=>'off', 'placeholder'=>'Contrase&ntilde;a', 'data-toggle'=>'tooltip', 'data-placement'=>'top', 'title'=>'Contrase&ntilde;a'))); 
                                    ?>
                            </div>
                            <span class="text-danger"> <?php echo form_error('passwd','','');?> </span>
                        </div>                        
                        <div class="col-md-12 col-sm-12 col-xs-12">
                            <div class="panel-body input-group input-group-sm">
                                <span class="input-group-addon"><div class="text-right" style="width:120px;">Código de verificación:</div></span>
                                <input type="text" class="form-control" name="userCaptcha" id="userCaptcha" placeholder="Escribe el texto de la imagen" autocomplete="off" value="<?php if(!empty($userCaptcha))echo $userCaptcha; ?>">
                            </div>
                            <span class="text-warning"> <?php echo form_error('userCaptcha','','');?> </span><br>
                            </div>
                            <div class="text-center">   <?php echo $captcha['image']; ?></div>
                            
                        </div>
                        <div class="text-center">
                            <?php 
                             //echo $this->form_complete->create_element(array('id'=>'token', 'type'=>'hidden', 'value'=>$token, 'attributes'=>array('class'=>'btn btn-amarillo btn-sm espacio')));
                            echo $this->form_complete->create_element(array('id'=>'btn_login', 'type'=>'submit', 'value'=>'Iniciar sesión', 'attributes'=>array('class'=>'btn btn-amarillo btn-sm espacio')));
                           
                            ?>
                        </div>
                        
    				<div id="publicacion_resultado" class="row">

    				</div>
                    
                    </div> <!-- /Row -->
                </div>  <!-- /panel-body-->
            </div> <!-- /panel panel-amarillo-->
        </div> <!-- /col 12-->
        <div class="col-md-3 col-sm-12 col-xs-12 text-center"></div>
    </div>
</div> <!-- row 12-->
<input type="hidden" id="token" name="token" value="<?php echo (exist_and_not_null($this->session->userdata('token')) ? $this->session->userdata('token') : ''); ?>">
<?php //echo $this->form_complete->create_element(array('id'=>'token', 'type'=>'hidden', 'value'=>((exist_and_not_null($this->session->userdata('token'))) ? $this->session->userdata('token') : '' )));
echo form_close(); ?>
<!--
<script type="text/javascript">
$( document ).ready(function() {
	data_ajax(site_url+"/publicacion/get_data_ajax", "#form_publicacion", "#publicacion_resultado");
	$("#btn_submit").click(function(event){
        data_ajax(site_url+"/publicacion/get_data_ajax", "#form_publicacion", "#publicacion_resultado");
        event.preventDefault();
    });
});
</script>-->