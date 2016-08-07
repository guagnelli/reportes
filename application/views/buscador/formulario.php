<?php   echo form_open('buscador/index', array('id'=>'form_buscador')); ?>

<div class="row">
    <aside class="col-sm-2">
        <p style="padding: 0px 15px 15px 15px;"><strong class="text-info" >
                Buscar por base de datos:</strong></p>
        
        <?php
        //TODAS LAS BASES DE DATOS
        echo '<label class="col-lg-12 col-md-12 col-sm-12 col-xs-12" data-placement="top" data-toggle="tooltip" data-original-title="Todas las bases de datos">
            <input type="radio" name="databases" value="" id="databases0" class="radio-hide" data-toggle="tooltip" data-placement="top" style="display:none;" title="" onclick="set_db_val(this.value, \'Todas las bases de datos\'); data_ajax(site_url+\'/buscador/get_data_ajax\', \'#form_buscador\', \'#listado_resultado\')" data-original-title="Base de datos">
            <img src="'. base_url().'assets/img/journals/img-journal-.png" class="img-responsive img-thumbnail" style="width:100%">
                  </label>';
                      
            $nbd=1;
        //LISTA DE BASES DE DATOS
        foreach ($bases_datos as $key_db=>$value_db){
            echo '<label class="col-lg-12 col-md-12 col-sm-12 col-xs-4" data-placement="top" data-toggle="tooltip" data-original-title="'.$value_db.'">';            
            echo $this->form_complete->create_element(array('id'=>'databases'.$key_db, 'type'=>'radio', 'value'=>$key_db, 'attributes'=>array('name'=>'databases','class'=>'radio-hide', 'data-toggle'=>'tooltip', 'data-placement'=>'top', 'style'=>'display:none;', 'title'=>'Base de datos', 'onclick'=>"set_db_val(this.value, '".$value_db."'); data_ajax(site_url+'/buscador/get_data_ajax', '#form_buscador', '#listado_resultado')")));
            echo '<img src="'. base_url().'assets/img/journals/logo-'.$key_db.'.png" class="img-responsive img-thumbnail" style="width:100%">
                  </label>
                  ';
            
            $nbd++;
        }
        
        echo '<br><br>';
        // 
        //pr($bases_datos); ?>
        
        <br><br>
    </aside>
    <section class="col-sm-10">
        <div class="row">
        <div class="col-md-12 col-sm-12 col-xs-12">
		<div class="panel panel-amarillo">
			<div class="panel-heading clearfix">
				<h3 class="panel-title">Buscador de recursos de información</h3>
                        </div>
                        <div class="panel-body">
                                <div class="row">
                                        <div class="col-lg-6 col-sm-6">
                                                <div class="panel-body  input-group input-group-sm">
                                                        <span class="input-group-addon">Título:</span>
                                                                <?php echo $this->form_complete->create_element(array('id'=>'titulo', 'type'=>'text', 'attributes'=>array('class'=>'form-control', 'placeholder'=>'Título de la publicación', 'data-toggle'=>'tooltip', 'data-placement'=>'top', 'title'=>'Título de la publicación'))); ?>
                                                </div>
                                        </div>
                                        <div class="col-lg-6 col-sm-6">
                                                    <div class="panel-body input-group input-group-sm">
                                                            <span class="input-group-addon">ISSN:</span>
                                                                    <?php echo $this->form_complete->create_element(array('id'=>'issn', 'type'=>'text', 'attributes'=>array('class'=>'form-control', 'placeholder'=>'ISSN', 'data-toggle'=>'tooltip', 'data-placement'=>'top', 'title'=>'ISSN'))); ?>
                                                    </div>
                                        </div>
                                        
                                </div>
                                <div class="row" id="busqueda_avanzada" style="display: none;">
                                        <div class="col-lg-6 col-sm-6">
                                                <!-- <div class="panel-body input-group input-group-sm">
                                                        <span class="input-group-addon">ISSN:</span>
                                                                <?php // echo $this->form_complete->create_element(array('id'=>'issn', 'type'=>'text', 'attributes'=>array('class'=>'form-control', 'placeholder'=>'ISSN', 'data-toggle'=>'tooltip', 'data-placement'=>'top', 'title'=>'ISSN'))); ?>
                                                </div>-->
                                                <div class="panel-body input-group input-group-sm">
                                                        <span class="input-group-addon">Temática:</span>
                                                                <?php echo $this->form_complete->create_element(array('id'=>'tematica', 'type'=>'dropdown', 'options'=>$tematicas, 'first'=>array(''=>'Seleccione...'), 'attributes'=>array('name'=>'tematica[]', 'class'=>'form-control', 'placeholder'=>'Temática', 'data-toggle'=>'tooltip', 'data-placement'=>'top', 'title'=>'Temática'))); ?>
                                                </div>
                                                
                                        </div>
                                        <div class="col-lg-6 col-sm-6">
                                                <!--<div class="panel-body input-group input-group-sm">
                                                        <span class="input-group-addon">Base de datos:</span>
                                                                <?php //echo $this->form_complete->create_element(array('id'=>'base_datos', 'type'=>'dropdown', 'options'=>$bases_datos, 'first'=>array(''=>'Seleccione...'), 'attributes'=>array('name'=>'base_datos[]', 'class'=>'form-control', 'placeholder'=>'Base de datos', 'data-toggle'=>'tooltip', 'data-placement'=>'top', 'title'=>'Base de datos'))); ?>
                                                </div>-->                                                
                                                <div class="panel-body input-group input-group-sm">
                                                        <span class="input-group-addon">Idioma:</span>
                                                                <?php echo $this->form_complete->create_element(array('id'=>'idioma', 'type'=>'dropdown', 'options'=>$idiomas, 'first'=>array(''=>'Seleccione...'), 'attributes'=>array('name'=>'idioma[]', 'class'=>'form-control', 'placeholder'=>'Idioma', 'data-toggle'=>'tooltip', 'data-placement'=>'top', 'title'=>'Idioma'))); ?>
                                                </div>
                                        </div>
                                        
                                </div> <!-- /Row -->
                                <div class="row">
                                    <div class="col-lg-6 col-sm-6">
                                        <h3> Base de datos seleccionada: <strong id="txt_db_name">Todas las bases de datos</strong> </h3>                        
                                        
                                    </div>
                                    <div class="col-lg-6 col-sm-6">
                                                <?php
                                                echo $this->form_complete->create_element(array('id'=>'base_datos', 'type'=>'hidden'));
                                                echo $this->form_complete->create_element(array('id'=>'name_db', 'type'=>'hidden'));
                                                echo $this->form_complete->create_element(array('id'=>'btn_submit', 'type'=>'submit', 'value'=>'Buscar', 'attributes'=>array('class'=>'btn btn-info btn-sm espacio')));
                                                echo $this->form_complete->create_element(array('id'=>'btn_reset', 'type'=>'button', 'value'=>'Limpiar', 'attributes'=>array('class'=>'btn btn-default btn-sm espacio', 'onclick'=>'this.form.reset();')));
                                                echo $this->form_complete->create_element(array('id'=>'btnAvanzado', 'type'=>'button', 'value'=>'Busqueda avanzada', 'attributes'=>array('class'=>'btn btn-link pull-right'))); 
                                                echo $this->form_complete->create_element(array('id'=>'btnOculta', 'type'=>'button', 'value'=>'Ocultar busqueda avanzada', 'attributes'=>array('class'=>'btn btn-link pull-right','style'=>'display: none;'))); 
                                                        ?>
                                    </div>
                                </div>
                        </div>  <!-- /panel-body-->
                </div> <!-- /panel panel-amarillo-->
        </div> <!-- /col 12-->
        </div>
<div class="row">
	<div class="col-md-12 col-sm-12 col-xs-12">
		<div class="panel panel-turqueza">
			<div class="panel-heading clearfix"><h3 class="panel-title">Listado de recursos</h3></div>
			<div class="panel-body">
				<div class="row">
					<div class="col-lg-4 col-sm-4">
						<div class="panel-body input-group input-group-sm">
							<span class="input-group-addon">Número de registros a mostrar:</span>
							<?php echo $this->form_complete->create_element(array('id'=>'per_page', 'type'=>'dropdown', 'options'=>array(10=>10, 20=>20, 50=>50, 100=>100), 'attributes'=>array('class'=>'form-control', 'placeholder'=>'Número de registros a mostrar', 'data-toggle'=>'tooltip', 'data-placement'=>'top', 'title'=>'Número de registros a mostrar', 'onchange'=>"data_ajax(site_url+'/buscador/get_data_ajax', '#form_buscador', '#listado_resultado')"))); ?>
						</div>
					</div>
					<div class="col-lg-4 col-sm-4">
						<div class="panel-body input-group input-group-sm">
							<span class="input-group-addon">Ordenar por:</span>
							<?php echo $this->form_complete->create_element(array('id'=>'order', 'type'=>'dropdown', 'options'=>$order_columns, 'attributes'=>array('class'=>'form-control', 'placeholder'=>'Ordernar por', 'data-toggle'=>'tooltip', 'data-placement'=>'top', 'title'=>'Ordenar por', 'onchange'=>"data_ajax(site_url+'/buscador/get_data_ajax', '#form_buscador', '#listado_resultado')"))); ?>
						</div>
					</div>
					<div class="col-lg-4 col-sm-4">
						<div class="panel-body input-group input-group-sm">
							<span class="input-group-addon">Tipo de orden:</span>
							<?php echo $this->form_complete->create_element(array('id'=>'order_type', 'type'=>'dropdown', 'options'=>array('ASC'=>'Ascendente', 'DESC'=>'Descendente'), 'attributes'=>array('class'=>'form-control', 'placeholder'=>'Ordernar por', 'data-toggle'=>'tooltip', 'data-placement'=>'top', 'title'=>'Ordenar por', 'onchange'=>"data_ajax(site_url+'/buscador/get_data_ajax', '#form_buscador', '#listado_resultado')"))); ?>
						</div>
					</div>
				</div>
				<div id="listado_resultado" class="row">        </div>
			</div>
		</div>
	</div>
</div>
</section>
</div> <!-- row 12-->



<?php echo form_close(); ?>
<script type="text/javascript">
$( document ).ready(function() {
	data_ajax(site_url+"/buscador/get_data_ajax", "#form_buscador", "#listado_resultado");
	$("#btn_submit").click(function(event){
        data_ajax(site_url+"/buscador/get_data_ajax", "#form_buscador", "#listado_resultado");
        event.preventDefault();
        });
        $("#btn_reset").click(function(event){
            this.form.reset();
            data_ajax(site_url+"/buscador/get_data_ajax", "#form_buscador", "#listado_resultado");
            event.preventDefault();
        });/**/
        $("#btnAvanzado").click(function(event){
            $("#busqueda_avanzada").show();
            $("#btnAvanzado").hide();
            $("#btnOculta").show();
        });
        $("#btnOculta").click(function(event){
            $("#busqueda_avanzada").hide();
            $("#btnAvanzado").show();
            $("#btnOculta").hide();
        });
});

function set_db_val($txt_val, $txt_name) {
    $('#base_datos').val($txt_val);
    $('#name_db').val($txt_name);
    $('#txt_db_name').html($txt_name);
    return false;
}

</script>