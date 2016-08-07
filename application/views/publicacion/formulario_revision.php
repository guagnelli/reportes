<?php $session = $this->session->userdata();
$tipo_revision = $this->config->item('publicacion_tipo_revision'); ?>
<div class="row">
	<div class="col-md-12 col-sm-12 col-xs-12">
		<div class="panel panel-gris">
			<div class="panel-heading clearfix">
				<h3 class="panel-title text-center"><?php echo (exist_and_not_null_array($session['publicacion'], 'tipo') && $session['publicacion']['tipo']==$tipo_revision['interna']['id']) ? 'Revisión desde la Red IMSS' : 'Revisión Remota' ; ?></h3>
			</div>
			<div class="panel-body">
				<div class="row">
					<div class="col-md-12 col-sm-12 col-xs-12">
						<?php if(isset($resultado)){
							echo ($resultado['result']==TRUE) ? html_message($resultado['msg'], 'success') : html_message($resultado['msg']) ; 
						}?>
					</div>
				</div>
				<div class="row">
					<div id="error_revision" class="col-lg-12 col-sm-12"></div>
					<div class="col-lg-8 col-sm-8">
		                <div class="row">
		                	<div class="col-md-3 col-sm-3 col-xs-12">
				                <h4 class="titulo"><b>ISSN:</b></h4>
				            </div>
				            <div class="col-md-9 col-sm-9 col-xs-12">
				            	<h4 class="titulo"><?php echo $publicacion['publicacion']->pub_issn; ?></h4>
				            </div>
			            </div>
			            <div class="row">
		                	<div class="col-md-3 col-sm-3 col-xs-12">
				                <h4 class="titulo"><b>Base de datos:</b></h4>
				            </div>
				            <div class="col-md-9 col-sm-9 col-xs-12">
				            	<h4 class="titulo"><?php echo $base_datos['bd_nombre']; ?></h4>
				            </div>
			            </div>
	                	<?php foreach ($publicacion['titulo'] as $key_titulo => $title) {
		                	echo '<div class="row">
			                		<div class="col-md-3 col-sm-3 col-xs-12">
						                <h4 class="titulo"><b>'.(($key_titulo>0) ? 'Variante del título' : 'Título de la publicación').':</b></h4>
						            </div>
						            <div class="col-md-9 col-sm-9 col-xs-12">
						                <h4 class="titulo"><a href="'.site_url('publicacion/edicion/'.$session['publicacion']['pub_id']).'" data-toggle="tooltip" data-placement="top" title="Editar publicación">'.$title->t_titulo.'</a></h4>
						            </div>
					            </div>';
			            } ?>
		            </div>
		            <div id="div_btn" class="col-lg-4 col-sm-4" align="center">
		                <?php
		                $checado = array();
		                $checado_text = "Completar revisión";
		                $alta_validacion = "";
		                if(isset($revision) && $revision[0]['rev_estado']==true){
		                	$checado = array('checked'=>'checked');
		                	$checado_text = "Revisión completada";
		                } else {
		                	$alta_validacion = $this->form_complete->create_element(array('id'=>'btn_crear_revision', 'type'=>'button', 'value'=>'Validar información', 'attributes'=>array('class'=>'btn btn-gris btn-sm espacio', 'onclick'=>"javascript:cargar_formulario_revision('#capa_revision', '".encrypt_base64(0)."');")));
		                }
		                echo "<div class='checkbox'><label>".$this->form_complete->create_element(array('id'=>'chk_cerrar_revision', 'type'=>'checkbox', 'attributes'=>array('title'=>'Confirmación', 'data-valor'=>'¿Esta seguro de querer marcar como completada la revisión?')+$checado))." ".$checado_text."</label></div>";
		                echo $this->form_complete->create_element(array('id'=>'btn_pendientes', 'type'=>'button', 'value'=>'Relación pendiente de la revisión', 'attributes'=>array('class'=>'btn btn-gris btn-sm espacio', 'data-toggle'=>"modal", 'data-target'=>'#modalPendientes')));
		                //echo $this->form_complete->create_element(array('id'=>'btn_conricyt', 'type'=>'button', 'value'=>'ir a CONRiCyT', 'attributes'=>array('class'=>'btn btn-gris btn-sm espacio', 'onclick'=>"window.open('http://www.conricyt.mx/','_blank');")));
		                echo $alta_validacion;
		                //echo $this->form_complete->create_element(array('id'=>'btn_publicacion', 'type'=>'button', 'value'=>'Edición publicación', 'attributes'=>array('class'=>'btn btn-gris btn-sm espacio', 'onclick'=>"javascript:cargar_formulario_revision('#capa_revision', '".encrypt_base64(0)."');")));
		                echo $this->form_complete->create_element(array('id'=>'btn_detalle_publicacion', 'type'=>'button', 'value'=>'Detalle de publicación', 'attributes'=>array('class'=>'btn btn-gris btn-sm espacio')));
		                //echo '<a class="btn btn-gris btn-sm espacio" href="'.site_url('publicacion/edicion/'.$session['publicacion']['pub_id']).'" data-toggle="tooltip" data-placement="top" title="Detalle de la publicación">Detalle publicación</a>';
		                //echo $this->form_complete->create_element(array('id'=>'btn_cerrar_revision', 'type'=>'button', 'value'=>'<span class="glyphicon glyphicon-unchecked" aria-hidden="true"></span> Completar revisión', 'attributes'=>array('class'=>'btn btn-primary btn-sm espacio', 'onclick'=>"javascript:cerrar_revision('".encrypt_base64($session['publicacion']['rev_id'])."');")));
		                ?>
	                </div>
				</div>				
			</div>  <!-- /panel-body-->
		</div> <!-- /panel panel-color-->
	</div> <!-- /col 12-->
</div> <!-- row 12-->

<div id="capa_revision" class="row"></div> <!-- Capa donde se carga el formulario de alta y edición de validación -->

<div class="row">
	<div class="col-md-12 col-sm-12 col-xs-12">
		<div class="panel panel-amarillo">
			<div class="panel-heading clearfix"> <h3 class="panel-title">Lista de validación de las publicaciones</h3></div>
			<div class="panel-body">
				<table class="table table-bordered table-striped">
					<thead>
						<tr>
							<th>Folio</th>
							<th>Tipo de búsqueda</th>
							<th>Criterio de búsqueda</th>
							<th>Estado</th>
							<th>Fecha de revisión</th>
							<th>Opciones</th>
						</tr>
					</thead>
				<tbody>
					<?php
					if(exist_and_not_null($revisado)){
						foreach ($revisado as $key_val => $validacion) {
							$editar_validacion = "";
							if(isset($revision) && $revision[0]['rev_estado']==false){
								$editar_validacion = '<a href="javascript:cargar_formulario_revision(\'#capa_revision\', \''.encrypt_base64($validacion['val_id']).'\');"><span class="glyphicon glyphicon-pencil amarillo" data-toggle="tooltip" data-placement="top" title="Edición"></span></a>';
							}
							echo '<tr>
									<td>'.$validacion['val_folio'].'</td>
									<td>'.$validacion['bsq_nombre'].'</td>
									<td>'.$validacion['cri_nombre'].'</td>
									<td>'.$validacion['est_nombre'].'</td>
									<td>'.get_fecha(3, new DateTime($validacion['val_fecha'])).'</td>
									<td><a href="javascript:cargar_detalle_revision(\'#capa_revision\', \''.encrypt_base64($validacion['val_id']).'\');"><span class="glyphicon glyphicon-zoom-in amarillo" data-toggle="tooltip" data-placement="top" title="Detalle"></span></a>
										'.$editar_validacion.'
									</td>
								</tr>'; //<span class="glyphicon glyphicon-ok"></span> 
						}
					} else {
						echo '<tr>
								<td colspan="6">Aun no han sido registradas validaciones para esta revisión.</td>
							</tr>';
					} ?>
				</tbody>
				</table>
			</div>
		</div>
	</div>
</div>

<div class="modal fade" id="modalPendientes" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
	<div class="modal-dialog">
		<div class="modal-content">
			<div class="modal-header">
				<?php echo $this->form_complete->create_element(array('id'=>'btn_cerrar_2', 'type'=>'button', 'value'=>'<span aria-hidden="true">&times;</span><span class="sr-only">Cerrar</span>', 'attributes'=>array('class'=>'close', 'data-dismiss'=>"modal"))); ?>
				<!-- <button type="button" class="close" data-dismiss="modal"><span aria-hidden="true">&times;</span><span class="sr-only">Cerrar</span></button> -->
				<h4 class="modal-title" id="myModalLabel">Relación pendienrte de la revisión</h4>
			</div>
			<div class="modal-body">
				<div class="media">
					<div class="media-body">
						<table class="table table-bordered table-striped">
							<thead>
								<tr>
									<th>Tipo de búsqueda</th>
									<th>Criterio de búsqueda</th>
									<th>Revisado</th>
									<th>Validado</th>
								</tr>
							</thead>
							<tbody>
								<?php
								if(exist_and_not_null($busqueda_criterio)){
									foreach ($busqueda_criterio as $key => $bc) {
										$registrado = (array_key_exists('registrado', $bc) && $bc['registrado']==TRUE) ? '<span class="glyphicon glyphicon-ok turqueza" data-toggle="tooltip" data-placement="top" title="Revisado"></span>' : '<span class="glyphicon glyphicon-remove turqueza" data-toggle="tooltip" data-placement="top" title="Pendiente"></span>';
										$validado = (array_key_exists('validado', $bc) && $bc['validado']==TRUE) ? '<span class="glyphicon glyphicon-ok turqueza" data-toggle="tooltip" data-placement="top" title="Validado"></span>' : '<span class="glyphicon glyphicon-remove turqueza" data-toggle="tooltip" data-placement="top" title="Pendiente"></span>';
										echo '<tr>
												<td>'.$bc['busqueda'].'</td>
												<td>'.$bc['criterio'].'</td>
												<td align="center">'.$registrado.'</td>
												<td align="center">'.$validado.'</td>
											</tr>';
									}
								} ?>
							</tbody>
						</table>
					</div>
				</div>
			</div><!--cierra modal-body-->
			<div class="modal-footer"><?php echo $this->form_complete->create_element(array('id'=>'btn_cerrar', 'type'=>'button', 'value'=>'Cerrar', 'attributes'=>array('class'=>'btn btn-default', 'data-dismiss'=>"modal"))); ?></div>
		</div>
	</div>
</div>
<script type="text/javascript">
$(document).ready(function() {
	$('#chk_cerrar_revision').click(function(ev) {
		ev.preventDefault();
		var title = (typeof($(this).attr('title')) === "undefined") ? "Alerta" : $(this).attr('title');
		var valor = $(this).attr('data-valor');
		if($('#dataConfirmRevModal').length>0) {
			$('#dataConfirmRevModal').remove();
		}
		var html = "<div class='modal fade' id='dataConfirmRevModal' role='dialog' aria-labelledby='dataConfirmLabel'>"+
				"<div class='modal-dialog'>"+
					"<div class='modal-content'>"+
						"<div class='modal-header'>"+
							"<button type='button' class='close' data-dismiss='modal' aria-hidden='true'>&times;</button>"+
							"<h4 class='modal-title'>"+title+"</h4>"+
						"</div>"+
						"<div class='modal-body'>"+
							"<p>"+valor+"</p>"+
						"</div>"+
						"<div class='modal-footer'>"+
							"<button type='button' class='btn btn-default' aria-hidden='true' data-dismiss='modal'>Cancelar</button>"+
							"<a class='btn btn-primary' id='dataConfirmOK' aria-hidden='true' data-dismiss='modal'><span>OK</span></a>"+
						"</div>"+
					"</div>"+
				"</div>"+
			"</div>";
		$('body').append(html);
		$('#dataConfirmOK').click(function(){
			cerrar_revision();
		});
		$('#dataConfirmRevModal').modal({show:true});
		return false;
	});
	$("#btn_detalle_publicacion").click(function(ev){
		detalle_publicacion();
	});
});
</script>