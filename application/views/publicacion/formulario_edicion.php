<?php echo form_open('publicacion/edicion/'.encrypt_base64($pub_id), array('id'=>'form_publicacion')); ?>
<div class="row">
	<div class="col-md-12 col-sm-12 col-xs-12">
		<div class="panel panel-turqueza">
			<div class="panel-heading clearfix">
				<h3 class="panel-title text-center"> <?php echo (isset($titulo[0]->t_titulo) && !empty($titulo[0]->t_titulo)) ? "Edición de: {$titulo[0]->t_titulo}" : "REGISTRAR NUEVA PUBLICACIÓN"; ?></h3>
			</div>
			<div class="panel-body">
				<div class="row">
					<div class="col-md-12 col-sm-12 col-xs-12">
						<?php if(isset($resultado)){
							echo ($resultado['result']==TRUE) ? html_message($resultado['msg'], 'success') : html_message($resultado['msg']) ; 
						}
						$insercion_msg = $this->input->get('r');
						if($insercion_msg==1){
							echo html_message("Inserción completada", 'success');
						} ?>
					</div>
				</div>
				<div class="row">
					<div class="col-md-12 col-sm-12 col-xs-12">
						Los campos con asterisco (*) son obligatorios
					</div>
					<div class="col-md-6 col-sm-6 col-xs-12">
						<div class="panel-body input-group input-group-sm">
							<span class="input-group-addon">* Responsable:</span>
							<?php echo $this->form_complete->create_element(array('id'=>'responsable', 'type'=>'dropdown', 'options'=>$responsables, 'value'=>$publicacion->responsable_id, 'first'=>array(''=>'Seleccione...'), 'attributes'=>array('class'=>'form-control', 'placeholder'=>'Responsable', 'data-toggle'=>'tooltip', 'data-placement'=>'top', 'title'=>'Responsable'))); ?>
						</div><!-- /panel-body input-group -->
						<?php //echo form_error('responsable', '<div class="has-error">', '</div>');
						echo form_error_format('responsable'); ?>
					</div><!-- /col-md.6 -->
					<div class="col-md-6 col-sm-6 col-xs-12">
						<div class="panel-body input-group input-group-sm">
							<span class="input-group-addon">* Estado de la publicación:</span>
							<?php echo $this->form_complete->create_element(array('id'=>'estado_publicacion', 'type'=>'dropdown', 'options'=>$estados_publicacion, 'value'=>$publicacion->est_pub_id, 'first'=>array(''=>'Seleccione...'), 'attributes'=>array('class'=>'form-control', 'placeholder'=>'Estado de publicación', 'data-toggle'=>'tooltip', 'data-placement'=>'top', 'title'=>'Estado de publicación'))); ?>
						</div><!-- /panel-body input-group -->
						<?php //echo form_error('estado_publicacion', '<div class="has-error">', '</div>'); 
						echo form_error_format('estado_publicacion'); ?>
					</div><!-- /col-md.6 -->
				</div><!-- /Row --><hr>
				<div id="div_publicacion" class="row">
					<div id="div_publicacion_titulo" class="col-md-10 col-sm-10 col-xs-10">
						<?php $inc_tit=0;
						if(!empty($titulo)){
							foreach ($titulo as $key_titulo => $title) {
								$titulo_text = (($inc_tit>0) ? 'Variante del título' : 'Título de la publicación');
								$remove_titulo = '<div class="col-md-1 col-sm-1 col-xs-1">
										<div class="btn badge" onclick="remove_titulo(\''.$key_titulo.'\');" style="float: right; margin-top:20px; margin-right: 20px;">x</div>
									</div>';
								echo '<div id="div_titulo_'.$key_titulo.'" class="row btn-default" style="margin-left: -8px;">
									<div class="col-md-11 col-sm-11 col-xs-11">
										<div class="badge_tematica">
											<div class="panel-body  input-group input-group-sm">
												<span class="input-group-addon">'.$titulo_text.':</span>
												'.$this->form_complete->create_element(array('id'=>'titulo', 'type'=>'text', 'value'=>$title->t_titulo, 'attributes'=>array('class'=>'form-control', 'name'=>'titulos[]', 'placeholder'=>$titulo_text, 'data-toggle'=>'tooltip', 'data-placement'=>'top', 'title'=>$titulo_text, 'maxlength'=>100))).'
											</div>
										</div>
									</div>
									'.(($inc_tit>0) ? $remove_titulo : '').'
								</div>';
								echo form_error_format('titulos['.$key_titulo.']');
								$inc_tit++;
							}
						} else {
							echo '<div id="div_titulo_'.$inc_tit.'" class="btn-default">
								<div class="col-md-11 col-sm-11 col-xs-11">
									<div class="badge_tematica">
										<div class="panel-body  input-group input-group-sm">
											<span class="input-group-addon">* Título de la publicación:</span>
											'.$this->form_complete->create_element(array('id'=>'titulo', 'type'=>'text', 'attributes'=>array('class'=>'form-control', 'name'=>'titulos[]', 'placeholder'=>'Título de la publicación', 'data-toggle'=>'tooltip', 'data-placement'=>'top', 'title'=>'Título de la publicación', 'maxlength'=>100))).'
										</div>
									</div>
								</div>
							</div>';
							echo form_error_format('titulos['.$inc_tit.']');
						}
						?>
					</div><!-- /col-md-11 -->
					<div class="col-md-2 col-sm-2 col-xs-2">
						<div class="panel-body"><?php echo $this->form_complete->create_element(array('id'=>'btn_agregar_titulo', 'type'=>'button', 'value'=>'Agregar', 'attributes'=>array('class'=>'btn btn-turqueza btn-sm btn-block', 'onclick'=>'agregar_titulo();'))); ?></div>
					</div>
				</div> <!-- /Row -->
				<div class="row">
					<div class="col-md-3 col-sm-6 col-xs-12">
						<div class="panel-body  input-group input-group-sm">
							<span class="input-group-addon">* ISSN:</span>
							<?php echo $this->form_complete->create_element(array('id'=>'issn', 'type'=>'text', 'value'=>$publicacion->pub_issn, 'attributes'=>array('class'=>'form-control', 'placeholder'=>'ISSN', 'data-toggle'=>'tooltip', 'data-placement'=>'top', 'title'=>'ISSN', 'maxlength'=>14))); ?>
						</div><!-- /panel-body input-group -->
						<?php echo form_error_format('issn'); ?>
					</div><!-- /col-md.3 -->
					<div class="col-md-3 col-sm-6 col-xs-12">
						<div class="panel-body  input-group input-group-sm">
							<span class="input-group-addon">ISSN-Electrónico:</span>
							<?php echo $this->form_complete->create_element(array('id'=>'issne', 'type'=>'text', 'value'=>$publicacion->pub_issne, 'attributes'=>array('class'=>'form-control', 'placeholder'=>'ISSN-Electrónico', 'data-toggle'=>'tooltip', 'data-placement'=>'top', 'title'=>'ISSN-Electrónico', 'maxlength'=>14))); ?>
						</div><!-- /panel-body input-group -->
						<?php echo form_error_format('issne'); ?>
					</div><!-- /col-md.3 -->
					<div class="col-md-3 col-sm-6 col-xs-12">
						<div class="panel-body  input-group input-group-sm">
							<span class="input-group-addon">ISSN-Impreso:</span>
							<?php echo $this->form_complete->create_element(array('id'=>'issnp', 'type'=>'text', 'value'=>$publicacion->pub_issnp, 'attributes'=>array('class'=>'form-control', 'placeholder'=>'ISSN-Impreso', 'data-toggle'=>'tooltip', 'data-placement'=>'top', 'title'=>'ISSN-Impreso', 'maxlength'=>14))); ?>
						</div><!-- /panel-body input-group -->
						<?php echo form_error_format('issnp'); ?>
					</div><!-- /col-md.3 -->
					<div class="col-md-3 col-sm-6 col-xs-12">
						<div class="panel-body  input-group input-group-sm">
							<span class="input-group-addon">ISSN-Linking:</span>
							<?php echo $this->form_complete->create_element(array('id'=>'issnl', 'type'=>'text', 'value'=>$publicacion->pub_issnl, 'attributes'=>array('class'=>'form-control', 'placeholder'=>'ISSN-Linking', 'data-toggle'=>'tooltip', 'data-placement'=>'top', 'title'=>'ISSN-Linking', 'maxlength'=>14))); ?>
						</div><!-- /panel-body input-group -->
						<?php echo form_error_format('issnl'); ?>
					</div><!-- /col-md.3 -->
				</div><!-- /Row -->
				<div class="row">
					<div class="col-md-12 col-sm-12 col-xs-12">
						<div class="panel-body input-group input-group-sm">
							<span class="input-group-addon">Editor comercial:</span>
							<?php echo $this->form_complete->create_element(array('id'=>'editor_comercial', 'type'=>'dropdown', 'options'=>$editores_comerciales, 'value'=>$publicacion->ec_id, 'first'=>array(''=>'Seleccione...'), 'attributes'=>array('name'=>'editor_comercial[]', 'class'=>'form-control', 'placeholder'=>'Editor comercial', 'data-toggle'=>'tooltip', 'data-placement'=>'top', 'title'=>'Editor comercial'))); ?>
						</div><!-- /panel-body input-group -->   
						<?php echo form_error_format('editor_comercial'); ?>
					</div><!-- /col-md.6 -->
				</div><!-- /Row -->
				<div class="row">
					<div class="col-md-6 col-sm-6 col-xs-12">
						<div class="panel-body input-group input-group-sm">
							<span class="input-group-addon">País:</span>
							<?php echo $this->form_complete->create_element(array('id'=>'pais', 'type'=>'dropdown', 'options'=>$paises, 'value'=>$publicacion->pais_codigo, 'first'=>array(''=>'Seleccione...'), 'attributes'=>array('name'=>'pais[]', 'class'=>'form-control', 'placeholder'=>'País', 'data-toggle'=>'tooltip', 'data-placement'=>'top', 'title'=>'País'))); ?>
						</div><!-- /panel-body input-group -->
						<?php echo form_error_format('pais'); ?>
					</div><!-- /col-md.6 -->
					<div class="col-md-6 col-sm-6 col-xs-12">
						<div class="panel-body input-group input-group-sm">
							<span class="input-group-addon">Idioma:</span>
							<?php echo $this->form_complete->create_element(array('id'=>'idioma', 'type'=>'dropdown', 'options'=>$idiomas, 'value'=>$publicacion->lang_id, 'first'=>array(''=>'Seleccione...'), 'attributes'=>array('name'=>'idioma[]', 'class'=>'form-control', 'placeholder'=>'Idioma', 'data-toggle'=>'tooltip', 'data-placement'=>'top', 'title'=>'Idioma'))); ?>
						</div><!-- /panel-body input-group -->  
						<?php echo form_error_format('idioma'); ?>
					</div><!-- /col-md.6 -->
				</div><!-- /Row -->
				<div class="row">
					<div class="col-md-6 col-sm-6 col-xs-12">
						<div class="panel-body  input-group input-group-sm">
							<span class="input-group-addon">Periodicidad:</span>
							<?php echo $this->form_complete->create_element(array('id'=>'periodicidad', 'type'=>'text', 'value'=>$publicacion->pub_periodicidad, 'attributes'=>array('class'=>'form-control', 'placeholder'=>'Periodicidad', 'data-toggle'=>'tooltip', 'data-placement'=>'top', 'title'=>'Periodicidad', 'maxlength'=>100))); ?>
						</div><!-- /panel-body input-group -->
						<?php echo form_error_format('periodicidad'); ?>
					</div><!-- /col-md.6 -->
					<div class="col-md-6 col-sm-6 col-xs-12">
						<div class="panel-body input-group input-group-sm">
							<span class="input-group-addon">Formato:</span>
							<?php echo $this->form_complete->create_element(array('id'=>'formato', 'type'=>'dropdown', 'options'=>$formatos, 'value'=>$publicacion->pub_formato, 'first'=>array(''=>'Seleccione...'), 'attributes'=>array('name'=>'formato[]', 'class'=>'form-control', 'placeholder'=>'Formato', 'data-toggle'=>'tooltip', 'data-placement'=>'top', 'title'=>'Formato'))); ?>
						</div><!-- /panel-body input-group -->
						<?php echo form_error_format('formato'); ?>
					</div><!-- /col-md.6 -->
				</div><!-- /Row --><hr>
				<div class="row">
					<div class="col-md-10 col-sm-10 col-xs-10">
						<div class="panel-body input-group input-group-sm">
							<span class="input-group-addon">* Temática:</span>
							<?php echo $this->form_complete->create_element(array('id'=>'tema', 'type'=>'dropdown', 'options'=>$tematicas, 'first'=>array(''=>'Seleccione...'), 'attributes'=>array('name'=>'tema[]', 'class'=>'form-control', 'placeholder'=>'Temática', 'data-toggle'=>'tooltip', 'data-placement'=>'top', 'title'=>'Temática'))); ?>
						</div><!-- /panel-body input-group -->
						<?php echo form_error_format('tematica'); ?>
					</div><!-- /col-md.6 -->
					<div class="col-md-2 col-sm-2 col-xs-2">
						<div class="panel-body"><?php echo $this->form_complete->create_element(array('id'=>'btn_add_tematica', 'type'=>'button', 'value'=>'Agregar', 'attributes'=>array('class'=>'btn btn-turqueza btn-sm btn-block', 'onclick'=>'add_tematica();'))); ?></div>
						<!-- <div class="panel-body"><button class="btn btn-turqueza btn-sm btn-block" type="submit">Agregar</button></div> -->
					</div><!-- /col-md.2 -->
				</div><!-- /Row -->
				<div id="div_tematicas" class="row">
					<?php foreach ($tematica as $key_pt => $publicacion_tematica) {
						echo '<div id="div_tematica_'.$publicacion_tematica->t_id.'" class="col-md-4 col-sm-6 col-xs-12">
								<div class="panel-body">
									<div class="btn btn-default btn-block btn-no-pointer">
										<div class="badge_tematica">'.$publicacion_tematica->t_nombre.'</div>
										<div class="badge" onclick="remove_tematica(\''.$publicacion_tematica->t_id.'\');">x</div></div>
									'.$this->form_complete->create_element(array('id'=>'tematica_'.$publicacion_tematica->t_id, 'type'=>'hidden', 'value'=>$publicacion_tematica->t_id, 'attributes'=>array('name'=>'tematica['.$publicacion_tematica->t_id.']'))).'
								</div>
								'.form_error_format('tematica['.$publicacion_tematica->t_id.']').'
							</div>';
					} ?>
				</div><!-- /Row --><hr>
				<div class="row">
					<div class="col-md-10 col-sm-10 col-xs-10">
						<div class="panel-body input-group input-group-sm">
							<span class="input-group-addon">* Base de datos:</span>
							<?php echo $this->form_complete->create_element(array('id'=>'bases_datos', 'type'=>'dropdown', 'options'=>$bases_datos, 'first'=>array(''=>'Seleccione...'), 'attributes'=>array('name'=>'bases_datos[]', 'class'=>'form-control', 'placeholder'=>'Base de datos', 'data-toggle'=>'tooltip', 'data-placement'=>'top', 'title'=>'Base de datos'))); ?>
						</div><!-- /panel-body input-group -->
						<?php echo form_error_format('bd'); ?>
					</div><!-- /col-md.6 -->
					<div class="col-md-2 col-sm-2 col-xs-2">
						<div class="panel-body"><?php echo $this->form_complete->create_element(array('id'=>'btn_add_bd', 'type'=>'button', 'value'=>'Agregar', 'attributes'=>array('class'=>'btn btn-turqueza btn-sm btn-block', 'onclick'=>'add_base_datos();'))); ?></div>
						<!-- <div class="panel-body"><button class="btn btn-turqueza btn-sm btn-block" type="submit">Agregar</button></div> -->
					</div><!-- /col-md.2 -->
					<!-- <div class="col-md-12 col-sm-12 col-xs-12">
						<div class="panel-body  input-group input-group-sm">
							<span class="input-group-addon">URL de la base de datos:</span>
							<input type="text" class="form-control" placeholder="texo" data-toggle="tooltip" data-placement="top" title="Lorem Ipsum">
						</div>
					</div> -->
				</div>
				<div id="div_base_datos" class="row">
					<?php foreach ($base_datos as $key_bds => $bds) {
						echo '<div id="div_bd_'.$bds->bd_id.'" class="col-md-12 col-sm-12 col-xs-12">
								<div class="panel-body">
									<div class="btn btn-default btn-block btn-no-pointer">
										<div class="badge_bds">'.$bds->bd_nombre.'</div>
										<div class="badge" onclick="remove_bd(\''.$bds->bd_id.'\');" style="cursor: pointer;">x</div>
										'.$this->form_complete->create_element(array('id'=>'bd_'.$bds->bd_id, 'type'=>'hidden', 'value'=>$bds->bd_id, 'attributes'=>array('name'=>'bd['.$bds->bd_id.']'))).'
										<div class="panel-body  input-group input-group-sm">
											<span class="input-group-addon">URL de la base de datos:</span>
											'.$this->form_complete->create_element(array('id'=>'bd_url_'.$bds->bd_id, 'type'=>'text', 'value'=>$bds->pub_bd_url, 'attributes'=>array('name'=>'bd_url['.$bds->bd_id.']', 'class'=>'form-control', 'placeholder'=>'URL de la base de datos', 'data-toggle'=>'tooltip', 'data-placement'=>'top', 'title'=>'URL de la base de datos', 'maxlength'=>100))).'
										</div>
										'.form_error_format('bd_url['.$bds->bd_id.']').'
									</div>
								</div>
							</div>';
					}?>
				</div><!-- /Row --><hr>
				<div class="row">
					<div class="col-md-12 col-sm-12 col-xs-12"><h3 class="centrame">Cobertura/Vigencia en CONRICyT:</h3></div>
					<div class="col-md-12 col-sm-12 col-xs-12">
						<h4 class="panel-body">Fecha más reciente</h4>
						<div class="col-md-4 col-sm-4 col-xs-12">
							<div class="input-group input-group-sm">
								<span class="input-group-addon">* Año:</span>
								<?php echo $this->form_complete->create_element(array('id'=>$coberturas_config['reciente']['id'].'_anio', 'type'=>'text', 'value'=>((isset($publicacion_coberturas[$coberturas_config['reciente']['id']]->cp_anio)) ? $publicacion_coberturas[$coberturas_config['reciente']['id']]->cp_anio : ''), 'attributes'=>array('class'=>'form-control', 'placeholder'=>'Año', 'data-toggle'=>'tooltip', 'data-placement'=>'top', 'title'=>'Año', 'maxlength'=>4))); ?>
							</div>
							<?php echo form_error_format($coberturas_config['reciente']['id'].'_anio'); ?>
						</div>
						<div class="col-md-4 col-sm-4 col-xs-12">
							<div class="input-group input-group-sm">
								<span class="input-group-addon">* Volúmen:</span>
								<?php echo $this->form_complete->create_element(array('id'=>$coberturas_config['reciente']['id'].'_volumen', 'type'=>'text', 'value'=>((isset($publicacion_coberturas[$coberturas_config['reciente']['id']]->cp_vol)) ? $publicacion_coberturas[$coberturas_config['reciente']['id']]->cp_vol : ''), 'attributes'=>array('class'=>'form-control', 'placeholder'=>'Volúmen', 'data-toggle'=>'tooltip', 'data-placement'=>'top', 'title'=>'Volúmen', 'maxlength'=>4))); ?>
							</div>
							<?php echo form_error_format($coberturas_config['reciente']['id'].'_volumen'); ?>
						</div>
						<div class="col-md-4 col-sm-4 col-xs-12">
							<div class="input-group input-group-sm">
								<span class="input-group-addon">* Número:</span>
								<?php echo $this->form_complete->create_element(array('id'=>$coberturas_config['reciente']['id'].'_numero', 'type'=>'text', 'value'=>((isset($publicacion_coberturas[$coberturas_config['reciente']['id']]->cp_num)) ? $publicacion_coberturas[$coberturas_config['reciente']['id']]->cp_num : ''), 'attributes'=>array('class'=>'form-control', 'placeholder'=>'Número', 'data-toggle'=>'tooltip', 'data-placement'=>'top', 'title'=>'Número', 'maxlength'=>4))); ?>
							</div>
							<?php echo form_error_format($coberturas_config['reciente']['id'].'_numero'); ?>
						</div>
					</div>
					<div class="col-md-12 col-sm-12 col-xs-12">
						<h4 class="panel-body">Fecha más antigua</h4>
						<div class="col-md-4 col-sm-4 col-xs-12">
							<div class="input-group input-group-sm">
								<span class="input-group-addon">* Año:</span>
								<?php echo $this->form_complete->create_element(array('id'=>$coberturas_config['antiguo']['id'].'_anio', 'type'=>'text', 'value'=>((isset($publicacion_coberturas[$coberturas_config['antiguo']['id']]->cp_anio)) ? $publicacion_coberturas[$coberturas_config['antiguo']['id']]->cp_anio : ''), 'attributes'=>array('class'=>'form-control', 'placeholder'=>'Año', 'data-toggle'=>'tooltip', 'data-placement'=>'top', 'title'=>'Año', 'maxlength'=>4))); ?>
							</div>
							<?php echo form_error_format($coberturas_config['antiguo']['id'].'_anio'); ?>
						</div>
						<div class="col-md-4 col-sm-4 col-xs-12">
							<div class="input-group input-group-sm">
								<span class="input-group-addon">* Volúmen:</span>
								<?php echo $this->form_complete->create_element(array('id'=>$coberturas_config['antiguo']['id'].'_volumen', 'type'=>'text', 'value'=>((isset($publicacion_coberturas[$coberturas_config['antiguo']['id']]->cp_vol)) ? $publicacion_coberturas[$coberturas_config['antiguo']['id']]->cp_vol : ''), 'attributes'=>array('class'=>'form-control', 'placeholder'=>'Volúmen', 'data-toggle'=>'tooltip', 'data-placement'=>'top', 'title'=>'Volúmen', 'maxlength'=>4))); ?>
							</div>
							<?php echo form_error_format($coberturas_config['antiguo']['id'].'_volumen'); ?>
						</div>
						<div class="col-md-4 col-sm-4 col-xs-12">
							<div class="input-group input-group-sm">
								<span class="input-group-addon">* Número:</span>
								<?php echo $this->form_complete->create_element(array('id'=>$coberturas_config['antiguo']['id'].'_numero', 'type'=>'text', 'value'=>((isset($publicacion_coberturas[$coberturas_config['antiguo']['id']]->cp_num)) ? $publicacion_coberturas[$coberturas_config['antiguo']['id']]->cp_num : ''), 'attributes'=>array('class'=>'form-control', 'placeholder'=>'Número', 'data-toggle'=>'tooltip', 'data-placement'=>'top', 'title'=>'Número', 'maxlength'=>4))); ?>
							</div>
							<?php echo form_error_format($coberturas_config['antiguo']['id'].'_numero'); ?>
						</div>
					</div><!-- /col-md.6 -->
				</div><!-- /Row --><br>
				<div class="row">
					<div class="col-md-12 col-sm-12 col-xs-12">
						<div class="panel-body"> 
							<!-- <h4 class="panel-body">Fuentes de texto completo</h4> -->
							<?php /*echo $this->form_complete->create_element(array('id'=>'fuentes', 'type'=>'textarea', 'value'=>$publicacion->pub_fuentes, 'attributes'=>array('class'=>'form-control', 'placeholder'=>'Fuentes de texto completo', 'data-toggle'=>'tooltip', 'data-placement'=>'top', 'title'=>'Fuentes de texto completo', 'rows'=>'3', 'maxlength'=>200)));
								echo form_error_format('fuentes');*/
								echo "<h4 class='panel-body'>Notas</h4>";
							 echo $this->form_complete->create_element(array('id'=>'notas', 'type'=>'textarea', 'value'=>$publicacion->pub_notas, 'attributes'=>array('class'=>'form-control', 'placeholder'=>'Notas', 'data-toggle'=>'tooltip', 'data-placement'=>'top', 'title'=>'Notas', 'rows'=>'3', 'maxlength'=>4000)));
							 	echo form_error_format('notas'); ?>
						</div>
					</div>
				</div><!-- /Row -->
				<div class="row">
					<div class="col-md-4 col-sm-4 col-xs-4"></div>
					<div class="col-md-4 col-sm-4 col-xs-4">
						<div class="panel-body"><button class="btn btn-turqueza btn-md btn-block" type="submit">Guardar</button></div> 
					</div>
					<div class="col-md-4 col-sm-4 col-xs-4"></div>
				</div><!-- /Row -->
			</div>  <!-- /panel-body-->
		</div> <!-- /panel panel-morado-->
	</div> <!-- /col 12-->
</div> <!-- row 12-->
<?php echo form_close(); ?>
