<?php
$tipo_revision = $this->config->item('publicacion_tipo_revision');
$session = $this->session->userdata();
?>
<div class="col-md-12 col-sm-12 col-xs-12">
	<div class="panel panel-turqueza">
		<div class="panel-heading clearfix">
			<h3 class="panel-title">Validación de criterios <?php echo $validacion->val_folio; ?></h3>
		</div>
		<div class="panel-body">
			<div id="error_validacion"></div>
			<div class="row">
				<div class="col-md-10 col-sm-12 col-xs-12">
					Los campos con asterisco (*) son obligatorios
				</div>
				<div class="col-md-2 col-sm-12 col-xs-12 text-right">
				<?php echo $this->form_complete->create_element(array('id'=>'btn_conricyt', 'type'=>'button', 'value'=>'ir a CONRiCyT', 'attributes'=>array('class'=>'btn btn-gris btn-sm espacio', 'onclick'=>"window.open('http://www.conricyt.mx/','_blank');"))); ?>
				</div>
				<div class="col-md-12 col-sm-12 col-xs-12">
					<?php if(isset($resultado)){
						echo ($resultado['result']==TRUE) ? html_message($resultado['msg'], 'success') : html_message($resultado['msg']) ; 
					}?>
				</div>
			</div>
			<?php echo form_open('publicacion/'.(($session['publicacion']['tipo']==$tipo_revision['interna']['id']) ? $tipo_revision['interna']['url'] : $tipo_revision['remota']['url']).'/'.$session['publicacion']['pub_id'], array('id'=>'form_revision')); ?>
			<div class="row">
				<div class="col-md-4 col-sm-6 col-xs-12">
					<div class="panel-body input-group input-group-sm">
						<span class="input-group-addon">* Tipo de búsqueda:</span>
						<?php echo $this->form_complete->create_element(array('id'=>'tipo_busqueda', 'type'=>'dropdown', 'options'=>$tipo_busqueda, 'value'=>$validacion->bsq_id, 'first'=>array(''=>'Seleccione...'), 'attributes'=>array('name'=>'tipo_busqueda[]', 'class'=>'form-control', 'placeholder'=>'Tipo de búsqueda', 'data-toggle'=>'tooltip', 'data-placement'=>'top', 'title'=>'Tipo de búsqueda', 'onchange'=>"javascript:cargar_criterio(this.value, '#criterio_busqueda', '');"))); ?>
					</div>
					<?php echo form_error_format('tipo_busqueda'); ?>
				</div>
				<div class="col-md-4 col-sm-6 col-xs-12">
					<div class="panel-body input-group input-group-sm">
						<span class="input-group-addon">* Criterio de búsqueda:</span>
						<?php echo $this->form_complete->create_element(array('id'=>'criterio_busqueda', 'type'=>'dropdown', 'options'=>$criterio_busqueda, 'value'=>$validacion->cri_id, 'first'=>array(''=>'Seleccione...'), 'attributes'=>array('name'=>'criterio_busqueda[]', 'class'=>'form-control', 'placeholder'=>'Criterio de búsqueda', 'data-toggle'=>'tooltip', 'data-placement'=>'top', 'title'=>'Criterio de búsqueda'))); ?>
					</div>
					<?php echo form_error_format('criterio_busqueda'); ?>
				</div>
				<div class="col-md-4 col-sm-6 col-xs-12">
					<div class="panel-body input-group input-group-sm">
						<span class="input-group-addon">* Estado de la revisión:</span>
						<?php echo $this->form_complete->create_element(array('id'=>'estado_revision', 'type'=>'dropdown', 'options'=>$estado_revision, 'value'=>$validacion->ev_id, 'first'=>array(''=>'Seleccione...'), 'attributes'=>array('name'=>'estado_revision[]', 'class'=>'form-control', 'placeholder'=>'Estado de la revisión', 'data-toggle'=>'tooltip', 'data-placement'=>'top', 'title'=>'Estado de la revisión'))); ?>
					</div>
					<?php echo form_error_format('estado_revision'); ?>
				</div>
				<div class="col-md-12 col-sm-12 col-xs-12">
					<div class="panel-body  input-group input-group-sm">
						<span class="input-group-addon">Disponible en:</span>
						<?php echo $this->form_complete->create_element(array('id'=>'disponible', 'type'=>'text', 'value'=>$validacion->val_disponibilidad, 'attributes'=>array('class'=>'form-control', 'placeholder'=>'Disponible', 'data-toggle'=>'tooltip', 'data-placement'=>'top', 'title'=>'Disponible', 'maxlength'=>100))); ?>
					</div>
					<?php echo form_error_format('disponible'); ?>
				</div>
			</div>

			<div class="row">
				<div class="col-md-12 col-sm-12 col-xs-12">
					<h4 class="panel-body">Fecha más reciente</h4>
					<div class="col-md-4 col-sm-4 col-xs-12">
						<div class="input-group input-group-sm">
							<span class="input-group-addon">* Año:</span>
							<?php echo $this->form_complete->create_element(array('id'=>$coberturas_config['reciente']['id'].'_anio', 'type'=>'text', 'value'=>((isset($coberturas[$coberturas_config['reciente']['id']]->cv_anio)) ? $coberturas[$coberturas_config['reciente']['id']]->cv_anio : ''), 'attributes'=>array('class'=>'form-control', 'placeholder'=>'Año', 'data-toggle'=>'tooltip', 'data-placement'=>'top', 'title'=>'Año', 'maxlength'=>4))); ?>
						</div>
						<?php echo form_error_format($coberturas_config['reciente']['id'].'_anio'); ?>
					</div>
					<div class="col-md-4 col-sm-4 col-xs-12">
						<div class="input-group input-group-sm">
							<span class="input-group-addon">* Volúmen:</span>
							<?php echo $this->form_complete->create_element(array('id'=>$coberturas_config['reciente']['id'].'_volumen', 'type'=>'text', 'value'=>((isset($coberturas[$coberturas_config['reciente']['id']]->cv_vol)) ? $coberturas[$coberturas_config['reciente']['id']]->cv_vol : ''), 'attributes'=>array('class'=>'form-control', 'placeholder'=>'Volúmen', 'data-toggle'=>'tooltip', 'data-placement'=>'top', 'title'=>'Volúmen', 'maxlength'=>4))); ?>
						</div>
						<?php echo form_error_format($coberturas_config['reciente']['id'].'_volumen'); ?>
					</div>
					<div class="col-md-4 col-sm-4 col-xs-12">
						<div class="input-group input-group-sm">
							<span class="input-group-addon">* Número:</span>
							<?php echo $this->form_complete->create_element(array('id'=>$coberturas_config['reciente']['id'].'_numero', 'type'=>'text', 'value'=>((isset($coberturas[$coberturas_config['reciente']['id']]->cv_num)) ? $coberturas[$coberturas_config['reciente']['id']]->cv_num : ''), 'attributes'=>array('class'=>'form-control', 'placeholder'=>'Número', 'data-toggle'=>'tooltip', 'data-placement'=>'top', 'title'=>'Número', 'maxlength'=>4))); ?>
						</div>
						<?php echo form_error_format($coberturas_config['reciente']['id'].'_numero'); ?>
					</div>
				</div><!-- /col-md.6 -->
				<div class="col-md-12 col-sm-12 col-xs-12">
					<h4 class="panel-body">Fecha más antigua</h4>
					<div class="col-md-4 col-sm-4 col-xs-12">
						<div class="input-group input-group-sm">
							<span class="input-group-addon">* Año:</span>
							<?php echo $this->form_complete->create_element(array('id'=>$coberturas_config['antiguo']['id'].'_anio', 'type'=>'text', 'value'=>((isset($coberturas[$coberturas_config['antiguo']['id']]->cv_anio)) ? $coberturas[$coberturas_config['antiguo']['id']]->cv_anio : ''), 'attributes'=>array('class'=>'form-control', 'placeholder'=>'Año', 'data-toggle'=>'tooltip', 'data-placement'=>'top', 'title'=>'Año', 'maxlength'=>4))); ?>
						</div>
						<?php echo form_error_format($coberturas_config['antiguo']['id'].'_anio'); ?>
					</div>
					<div class="col-md-4 col-sm-4 col-xs-12">
						<div class="input-group input-group-sm">
							<span class="input-group-addon">* Volúmen:</span>
							<?php echo $this->form_complete->create_element(array('id'=>$coberturas_config['antiguo']['id'].'_volumen', 'type'=>'text', 'value'=>((isset($coberturas[$coberturas_config['antiguo']['id']]->cv_vol)) ? $coberturas[$coberturas_config['antiguo']['id']]->cv_vol : ''), 'attributes'=>array('class'=>'form-control', 'placeholder'=>'Volúmen', 'data-toggle'=>'tooltip', 'data-placement'=>'top', 'title'=>'Volúmen', 'maxlength'=>4))); ?>
						</div>
						<?php echo form_error_format($coberturas_config['antiguo']['id'].'_volumen'); ?>
					</div>
					<div class="col-md-4 col-sm-4 col-xs-12">
						<div class="input-group input-group-sm">
							<span class="input-group-addon">* Número:</span>
							<?php echo $this->form_complete->create_element(array('id'=>$coberturas_config['antiguo']['id'].'_numero', 'type'=>'text', 'value'=>((isset($coberturas[$coberturas_config['antiguo']['id']]->cv_num)) ? $coberturas[$coberturas_config['antiguo']['id']]->cv_num : ''), 'attributes'=>array('class'=>'form-control', 'placeholder'=>'Número', 'data-toggle'=>'tooltip', 'data-placement'=>'top', 'title'=>'Número', 'maxlength'=>4))); ?>
						</div>
						<?php echo form_error_format($coberturas_config['antiguo']['id'].'_numero'); ?>
					</div>
				</div><!-- /col-md.6 -->
			</div><!-- /Row -->
			<div class="row">
				<div class="col-md-12 col-sm-12 col-xs-12">					
					<div class="panel-body">
						<br><h4>Observaciones</h4>
						<?php echo $this->form_complete->create_element(array('id'=>'notas', 'type'=>'textarea', 'value'=>$validacion->val_nota, 'attributes'=>array('class'=>'form-control', 'placeholder'=>'Notas', 'data-toggle'=>'tooltip', 'data-placement'=>'top', 'title'=>'Notas', 'rows'=>'3', 'maxlength'=>4000)));
						echo form_error_format('notas'); ?>
					</div>
				</div>
			</div><!-- /Row -->
			<div class="row">
				<div class="col-md-12 col-sm-12 col-xs-12">
					<div class="panel-body  pull-right">
						<?php echo $this->form_complete->create_element(array('id'=>'btn_guardar_validacion', 'type'=>'button', 'value'=>'Guardar validación', 'attributes'=>array('class'=>'btn btn-turqueza btn-sm', 'onclick'=>'javascript:guardar_validacion();')))."&nbsp;&nbsp;";
						echo $this->form_complete->create_element(array('id'=>'btn_cancelar_validacion', 'type'=>'button', 'value'=>'Cancelar', 'attributes'=>array('class'=>'btn btn-turqueza btn-sm', 'onclick'=>'javascript:cancelar_validacion();'))); ?>
					</div>
				</div>
			</div>
			<?php echo $this->form_complete->create_element(array('id'=>'val_id', 'type'=>'hidden', 'value'=>encrypt_base64($validacion->val_id)));
			echo form_close();
			?>
		</div>
		<?php
		echo form_open_multipart('publicacion/cargar_archivo/', array('id'=>'form_validacion_archivo'));
		if($validacion->val_id!=0){ //Mostrar solo si es una edición
		?>
		<div class="panel-heading clearfix"><h3 class="panel-title">Evidencias</h3></div>
		<div class="panel-body">
			<?php //echo $this->form_complete->create_element(array('id'=>'btn_add_evidencia', 'type'=>'button', 'value'=>'<span class="glyphicon glyphicon-plus"></span> Agregar evidencia', 'attributes'=>array('class'=>'btn btn-turqueza btn-sm pull-right', 'style'=>'margin-bottom:10px;'))); ?>
			<div id="error_carga_archivo"></div>
			<div id="capa_carga_archivo" class="row">
				<div class="col-md-2 col-sm-2 col-xs-12">
					<span>Archivo:</span>
				</div>
				<div class="col-md-6 col-sm-6 col-xs-12">									
					<?php echo $this->form_complete->create_element(array('id'=>'evidencia_archivo', 'type'=>'upload', 'attributes'=>array('class'=>'btn btn-sm', 'style'=>'margin-bottom:10px;'))); ?>
				</div>
				<div class="col-md-4 col-sm-4 col-xs-12">
					<?php echo $this->form_complete->create_element(array('id'=>'btn_add_file', 'type'=>'button', 'value'=>'<span class="glyphicon glyphicon-plus"></span> Agregar evidencia', 'attributes'=>array('class'=>'btn btn-turqueza btn-sm pull-right', 'style'=>'margin-bottom:10px;', 'onclick'=>"javascript:cargar_validacion_archivo();"))); ?>
				</div>
			</div>
			<div class="row"><div class="col-md-12 col-sm-12 col-xs-12">Los extensiones de los archivos permitidos deben ser: <?php echo implode(", ", $this->config->item('extension_revision')); ?>. Y no deben tener un peso mayor a 2MB</div></div><br>
			<div id="listado_archivos"></div>	
		</div>
		<?php }
		echo form_close(); ?>
	</div>
</div>
<script type="text/javascript">
	$(document).ready(function() {
		cargar_criterio('<?php echo $validacion->bsq_id; ?>', '#criterio_busqueda', '<?php echo (exist_and_not_null($validacion->cri_id) ? $validacion->cri_id : ''); ?>');
		<?php if($validacion->val_id!=0){ //En caso de que sea una edición, se deshabilitan tipo de búsqueda y criterio ?>
			$('#tipo_busqueda').attr('readonly', 'readonly');
			$('#criterio_busqueda').attr('readonly', 'readonly');
		<?php }
		if(isset($resultado)){
			if($resultado['result']==TRUE) {
				echo 'mensaje_modal("'.$resultado['msg'].'", "Alerta");';
			}
		} ?>
		$('#dataMessageModal').on('hidden.bs.modal', function () {
			location.reload();
		})
	});
	var extension_revision = <?php echo json_encode($this->config->item('extension_revision')); ?>;
	cargar_archivo_listado('<?php echo encrypt_base64($validacion->val_id); ?>');
</script>
