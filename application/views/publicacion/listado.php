<?php echo form_open('publicacion/index', array('id'=>'form_publicacion')); ?>
<div class="row">
	<div class="col-md-12 col-sm-12 col-xs-12">
		<div class="panel panel-amarillo">
			<div class="panel-heading clearfix">
				<h3 class="panel-title">BUSCADOR DE PUBLICACIONES</h3>
        	</div>
	        <div class="panel-body">
          		<div class="row">
					<div class="col-md-12 col-sm-12 col-xs-12">
						<div class="panel-body  input-group input-group-sm">
							<span class="input-group-addon">Título:</span>
							<?php echo $this->form_complete->create_element(array('id'=>'titulo', 'type'=>'text', 'attributes'=>array('class'=>'form-control', 'placeholder'=>'Título de la publicación', 'data-toggle'=>'tooltip', 'data-placement'=>'top', 'title'=>'Título de la aplicación'))); ?>
						</div>
					</div>
					<div class="col-lg-6 col-sm-6">
						<div class="panel-body input-group input-group-sm">
							<span class="input-group-addon">ISSN:</span>
							<?php echo $this->form_complete->create_element(array('id'=>'issn', 'type'=>'text', 'attributes'=>array('class'=>'form-control', 'placeholder'=>'ISSN', 'data-toggle'=>'tooltip', 'data-placement'=>'top', 'title'=>'ISSN'))); ?>
						</div>
						<div class="panel-body input-group input-group-sm">
							<span class="input-group-addon">Estado de la publicación:</span>
							<?php echo $this->form_complete->create_element(array('id'=>'estado', 'type'=>'dropdown', 'options'=>$estados_publicacion, 'first'=>array(''=>'Seleccione...'), 'attributes'=>array('name'=>'estado[]', 'class'=>'form-control', 'placeholder'=>'Estado', 'data-toggle'=>'tooltip', 'data-placement'=>'top', 'title'=>'Estado de la publicación'))); ?>
						</div> 
					</div>
					<div class="col-lg-6 col-sm-6">
						<div class="panel-body input-group input-group-sm">
							<span class="input-group-addon">Base de datos:</span>
							<?php echo $this->form_complete->create_element(array('id'=>'base_datos', 'type'=>'dropdown', 'options'=>$bases_datos, 'first'=>array(''=>'Seleccione...'), 'attributes'=>array('name'=>'base_datos[]', 'class'=>'form-control', 'placeholder'=>'Base de datos', 'data-toggle'=>'tooltip', 'data-placement'=>'top', 'title'=>'Base de datos'))); ?>
						</div>
						<div class="panel-body input-group input-group-sm">
							<span class="input-group-addon">Idioma:</span>
							<?php echo $this->form_complete->create_element(array('id'=>'idioma', 'type'=>'dropdown', 'options'=>$idiomas, 'first'=>array(''=>'Seleccione...'), 'attributes'=>array('name'=>'idioma[]', 'class'=>'form-control', 'placeholder'=>'Idioma', 'data-toggle'=>'tooltip', 'data-placement'=>'top', 'title'=>'Idioma'))); ?>
						</div>
					</div>
					<div class="text-center">
						<?php echo $this->form_complete->create_element(array('id'=>'btn_submit', 'type'=>'submit', 'value'=>'Buscar', 'attributes'=>array('class'=>'btn btn-amarillo btn-sm espacio')));
						echo $this->form_complete->create_element(array('id'=>'btn_reset', 'type'=>'button', 'value'=>'Limpiar', 'attributes'=>array('class'=>'btn btn-amarillo btn-sm espacio', 'onclick'=>'this.form.reset();')));
						?>
					</div>
				</div> <!-- /Row -->
			</div>  <!-- /panel-body-->
		</div> <!-- /panel panel-amarillo-->
    </div> <!-- /col 12-->
</div> <!-- row 12-->

<div class="row">
	<div class="col-md-12 col-sm-12 col-xs-12">
		<div class="panel panel-turqueza">
			<div class="panel-heading clearfix"> <h3 class="panel-title">Listado de publicaciones</h3></div>
			<div class="panel-body">
				<div class="row">
					<div class="col-lg-4 col-sm-4">
						<div class="panel-body input-group input-group-sm">
							<span class="input-group-addon">Número de registros a mostrar:</span>
							<?php echo $this->form_complete->create_element(array('id'=>'per_page', 'type'=>'dropdown', 'options'=>array(10=>10, 20=>20, 50=>50, 100=>100), 'attributes'=>array('class'=>'form-control', 'placeholder'=>'Número de registros a mostrar', 'data-toggle'=>'tooltip', 'data-placement'=>'top', 'title'=>'Número de registros a mostrar', 'onchange'=>"data_ajax(site_url+'/publicacion/get_data_ajax', '#form_publicacion', '#publicacion_resultado')"))); ?>
						</div>
					</div>
					<div class="col-lg-4 col-sm-4">
						<div class="panel-body input-group input-group-sm">
							<span class="input-group-addon">Ordenar por:</span>
							<?php echo $this->form_complete->create_element(array('id'=>'order', 'type'=>'dropdown', 'options'=>$order_columns, 'attributes'=>array('class'=>'form-control', 'placeholder'=>'Ordernar por', 'data-toggle'=>'tooltip', 'data-placement'=>'top', 'title'=>'Ordenar por', 'onchange'=>"data_ajax(site_url+'/publicacion/get_data_ajax', '#form_publicacion', '#publicacion_resultado')"))); ?>
						</div>
					</div>
					<div class="col-lg-4 col-sm-4">
						<div class="panel-body input-group input-group-sm">
							<span class="input-group-addon">Tipo de orden:</span>
							<?php echo $this->form_complete->create_element(array('id'=>'order_type', 'type'=>'dropdown', 'options'=>array('ASC'=>'Ascendente', 'DESC'=>'Descendente'), 'attributes'=>array('class'=>'form-control', 'placeholder'=>'Ordernar por', 'data-toggle'=>'tooltip', 'data-placement'=>'top', 'title'=>'Ordenar por', 'onchange'=>"data_ajax(site_url+'/publicacion/get_data_ajax', '#form_publicacion', '#publicacion_resultado')"))); ?>
						</div>
					</div>
				</div>
				<div id="publicacion_resultado" class="row">

				</div>
			</div>
		</div>
	</div>
</div>
<?php echo form_close(); ?>
<script type="text/javascript">
$( document ).ready(function() {
	data_ajax(site_url+"/publicacion/get_data_ajax", "#form_publicacion", "#publicacion_resultado");
	$("#btn_submit").click(function(event){
        data_ajax(site_url+"/publicacion/get_data_ajax", "#form_publicacion", "#publicacion_resultado");
        event.preventDefault();
    });
});
</script>