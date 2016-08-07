<div class="col-md-12 col-sm-12 col-xs-12">
	<div class="panel panel-turqueza">
		<div class="panel-heading clearfix">
			<h3 class="panel-title">Validación de criterios <?php echo $validacion->val_folio; ?></h3>
		</div>
		<div class="panel-body">
			<div class="row">
				<div class="col-md-4 col-sm-6 col-xs-12">
					<div class="panel-body input-group input-group-sm">
						<span class="input-group-addon">* Tipo de búsqueda:</span>
						<?php echo $this->form_complete->create_element(array('id'=>'tipo_busqueda', 'type'=>'text', 'value'=>$data_busqueda[0]['bsq_nombre'], 'attributes'=>array('class'=>'form-control disabled', 'disabled'=>'disabled'))); ?>
					</div>
				</div>
				<div class="col-md-4 col-sm-6 col-xs-12">
					<div class="panel-body input-group input-group-sm">
						<span class="input-group-addon">* Criterio de búsqueda:</span>
						<?php echo $this->form_complete->create_element(array('id'=>'criterio_busqueda', 'type'=>'text', 'value'=>$data_criterio[0]['cri_nombre'], 'attributes'=>array('class'=>'form-control disabled', 'disabled'=>'disabled'))); ?>
					</div>
				</div>
				<div class="col-md-4 col-sm-6 col-xs-12">
					<div class="panel-body input-group input-group-sm">
						<span class="input-group-addon">* Estado de la revisión:</span>
						<?php echo $this->form_complete->create_element(array('id'=>'estado_revision', 'type'=>'text', 'value'=>$data_estado_validacion[0]['est_nombre'], 'attributes'=>array('class'=>'form-control disabled', 'disabled'=>'disabled'))); ?>
					</div>
				</div>
				<div class="col-md-12 col-sm-12 col-xs-12">
					<div class="panel-body  input-group input-group-sm">
						<span class="input-group-addon">Disponible en:</span>
						<?php echo $this->form_complete->create_element(array('id'=>'disponible', 'type'=>'text', 'value'=>$validacion->val_disponibilidad, 'attributes'=>array('class'=>'form-control disabled', 'disabled'=>'disabled'))); ?>
					</div>
				</div>
			</div>

			<div class="row">
				<div class="col-md-6 col-sm-12 col-xs-12">
					<h4 class="panel-body">Fecha más reciente</h4>
					<div class="col-md-4 col-sm-4 col-xs-12">
						<div class="input-group input-group-sm">
							<span class="input-group-addon">* Año:</span>
							<?php echo $this->form_complete->create_element(array('id'=>$coberturas_config['reciente']['id'].'_anio', 'type'=>'text', 'value'=>((isset($coberturas[$coberturas_config['antiguo']['id']])) ? $coberturas[$coberturas_config['reciente']['id']]->cv_anio : ''), 'attributes'=>array('class'=>'form-control disabled', 'disabled'=>'disabled'))); ?>
						</div>
					</div>
					<div class="col-md-4 col-sm-4 col-xs-12">
						<div class="input-group input-group-sm">
							<span class="input-group-addon">* Volúmen:</span>
							<?php echo $this->form_complete->create_element(array('id'=>$coberturas_config['reciente']['id'].'_volumen', 'type'=>'text', 'value'=>((isset($coberturas[$coberturas_config['antiguo']['id']])) ? $coberturas[$coberturas_config['reciente']['id']]->cv_vol : ''), 'attributes'=>array('class'=>'form-control disabled', 'disabled'=>'disabled'))); ?>
						</div>
					</div>
					<div class="col-md-4 col-sm-4 col-xs-12">
						<div class="input-group input-group-sm">
							<span class="input-group-addon">* Número:</span>
							<?php echo $this->form_complete->create_element(array('id'=>$coberturas_config['reciente']['id'].'_numero', 'type'=>'text', 'value'=>((isset($coberturas[$coberturas_config['antiguo']['id']])) ? $coberturas[$coberturas_config['reciente']['id']]->cv_num : ''), 'attributes'=>array('class'=>'form-control disabled', 'disabled'=>'disabled'))); ?>
						</div>
					</div>
				</div><!-- /col-md.6 -->
				<div class="col-md-6 col-sm-12 col-xs-12">
					<h4 class="panel-body">Fecha más antigua</h4>
					<div class="col-md-4 col-sm-4 col-xs-12">
						<div class="input-group input-group-sm">
							<span class="input-group-addon">* Año:</span>
							<?php echo $this->form_complete->create_element(array('id'=>$coberturas_config['antiguo']['id'].'_anio', 'type'=>'text', 'value'=>((isset($coberturas[$coberturas_config['antiguo']['id']])) ? $coberturas[$coberturas_config['antiguo']['id']]->cv_anio : ''), 'attributes'=>array('class'=>'form-control disabled', 'disabled'=>'disabled'))); ?>
						</div>
					</div>
					<div class="col-md-4 col-sm-4 col-xs-12">
						<div class="input-group input-group-sm">
							<span class="input-group-addon">* Volúmen:</span>
							<?php echo $this->form_complete->create_element(array('id'=>$coberturas_config['antiguo']['id'].'_volumen', 'type'=>'text', 'value'=>((isset($coberturas[$coberturas_config['antiguo']['id']])) ? $coberturas[$coberturas_config['antiguo']['id']]->cv_vol : ''), 'attributes'=>array('class'=>'form-control disabled', 'disabled'=>'disabled'))); ?>
						</div>
					</div>
					<div class="col-md-4 col-sm-4 col-xs-12">
						<div class="input-group input-group-sm">
							<span class="input-group-addon">* Número:</span>
							<?php echo $this->form_complete->create_element(array('id'=>$coberturas_config['antiguo']['id'].'_numero', 'type'=>'text', 'value'=>((isset($coberturas[$coberturas_config['antiguo']['id']])) ? $coberturas[$coberturas_config['antiguo']['id']]->cv_num : ''), 'attributes'=>array('class'=>'form-control disabled', 'disabled'=>'disabled'))); ?>
						</div>
					</div>
				</div><!-- /col-md.6 -->
			</div><!-- /Row -->


			<div class="row">
				<div class="col-md-12 col-sm-12 col-xs-12">					
					<div class="panel-body">
						<br><h4>Observaciones</h4>
						<div><?php echo $validacion->val_nota; ?></div>
					</div>
				</div>
			</div><!-- /Row -->
			<hr>
			<?php //echo $this->form_complete->create_element(array('id'=>'val_id', 'type'=>'hidden', 'value'=>encrypt_base64($validacion->val_id))); ?>
			<div class="row">
				<div class="col-md-12 col-sm-12 col-xs-12">
					<div class="panel">
						<div class="panel-heading clearfix"> 
							<h3 class="panel-title">Evidencias</h3> 
						</div>
						<div class="panel-body">
							<table class="table table-bordered table-striped">
							<thead>
								<tr>
									<th width="100%">Evidencia</th>
								</tr>
							</thead>
							<tbody>
								<?php 
								if(exist_and_not_null($evidencias)){
									foreach ($evidencias as $key_ev => $evidencia) {
										if($evidencia!="." && $evidencia!=".."){
											echo '<tr>
													<td><a href="'.$evidencias_ruta.$evidencia.'" target="_blank">'.$evidencia.'</a></td>
												</tr>';
										}
									}
								} else {
									echo '<tr>
											<td colspan="2">Aun no se han registrado evidencias para esta validación.</td>
										</tr>';
								} ?>
							</tbody>
						</table>
						</div>
					</div>
				</div>
			</div>
			<div class="row">
				<div class="col-md-12 col-sm-12 col-xs-12">
					<div class="panel-body  pull-right">
						<?php echo $this->form_complete->create_element(array('id'=>'btn_cancelar_validacion', 'type'=>'button', 'value'=>'Cancelar', 'attributes'=>array('class'=>'btn btn-turqueza btn-sm', 'onclick'=>'javascript:cancelar_validacion();'))); ?>
					</div>
				</div>
			</div>
		</div>
	</div>
</div>
<script type="text/javascript">
	$(document).ready(function() {
		
	});
	cargar_archivo_listado('<?php echo encrypt_base64($validacion->val_id); ?>');
</script>
