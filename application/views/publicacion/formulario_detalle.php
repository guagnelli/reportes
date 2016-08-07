<div class="row">
	<div class="col-md-12 col-sm-12 col-xs-12">
		<div class="panel panel-turqueza espacio-color">
			<div class="panel-heading clearfix">
				<h3 class="panel-title text-center"><?php echo $publicacion->pub_issn; ?> - <span><?php echo (!empty($publicacion->titulos)) ? $publicacion->titulos[0] : ""; ?></span></h3>
			</div>
			<div class="panel-body">
				<div class="row">
					<div class="col-md-6 col-sm-6 col-xs-12">
						<div class="panel-body input-group input-group-sm">
							<span class="input-group-addon">Responsable:</span>
							<?php echo $this->form_complete->create_element(array('id'=>'responsable', 'type'=>'text', 'value'=>$publicacion->usr_nombre.' '.$publicacion->usr_paterno.' '.$publicacion->usr_materno, 'attributes'=>array('class'=>'form-control', 'disabled'=>'disabled'))); ?>
						</div><!-- /panel-body input-group -->
					</div><!-- /col-md.6 -->
					<div class="col-md-6 col-sm-6 col-xs-12">
						<div class="panel-body input-group input-group-sm">
							<span class="input-group-addon">Estado de la publicación:</span>
							<?php echo $this->form_complete->create_element(array('id'=>'estado_publicacion', 'type'=>'text', 'value'=>$publicacion->est_pub_nombre, 'attributes'=>array('class'=>'form-control', 'disabled'=>'disabled'))); ?>
						</div><!-- /panel-body input-group -->
					</div><!-- /col-md.6 -->
				</div><!-- /Row --><hr>
				<div id="div_publicacion" class="row">
						<?php $inc_tit=0;
						if(!empty($publicacion->titulos)){
							foreach ($publicacion->titulos as $key_titulo => $title) {
								$titulo_text = (($inc_tit>0) ? 'Variante del título' : 'Título de la publicación');
								echo '<div class="col-md-12 col-sm-12 col-xs-12">
										<div class="panel-body  input-group input-group-sm">
											<span class="input-group-addon">'.$titulo_text.':</span>
											'.$this->form_complete->create_element(array('id'=>'titulo', 'type'=>'text', 'value'=>$title, 'attributes'=>array('class'=>'form-control', 'maxlength'=>100, 'disabled'=>'disabled'))).'
										</div>
									</div>';
								$inc_tit++;
							}
						}
						?>
				</div> <!-- /Row -->
				<div class="row">
					<div class="col-md-3 col-sm-6 col-xs-12">
						<div class="panel-body  input-group input-group-sm">
							<span class="input-group-addon">* ISSN:</span>
							<?php echo $this->form_complete->create_element(array('id'=>'issn', 'type'=>'text', 'value'=>$publicacion->pub_issn, 'attributes'=>array('class'=>'form-control', 'maxlength'=>14, 'disabled'=>'disabled'))); ?>
						</div><!-- /panel-body input-group -->
					</div><!-- /col-md.3 -->
					<div class="col-md-3 col-sm-6 col-xs-12">
						<div class="panel-body  input-group input-group-sm">
							<span class="input-group-addon">ISSN-Electrónico:</span>
							<?php echo $this->form_complete->create_element(array('id'=>'issne', 'type'=>'text', 'value'=>$publicacion->pub_issne, 'attributes'=>array('class'=>'form-control', 'maxlength'=>14, 'disabled'=>'disabled'))); ?>
						</div><!-- /panel-body input-group -->
					</div><!-- /col-md.3 -->
					<div class="col-md-3 col-sm-6 col-xs-12">
						<div class="panel-body  input-group input-group-sm">
							<span class="input-group-addon">ISSN-Impreso:</span>
							<?php echo $this->form_complete->create_element(array('id'=>'issnp', 'type'=>'text', 'value'=>$publicacion->pub_issnp, 'attributes'=>array('class'=>'form-control', 'maxlength'=>14, 'disabled'=>'disabled'))); ?>
						</div><!-- /panel-body input-group -->
					</div><!-- /col-md.3 -->
					<div class="col-md-3 col-sm-6 col-xs-12">
						<div class="panel-body  input-group input-group-sm">
							<span class="input-group-addon">ISSN-Linking:</span>
							<?php echo $this->form_complete->create_element(array('id'=>'issnl', 'type'=>'text', 'value'=>$publicacion->pub_issnl, 'attributes'=>array('class'=>'form-control', 'maxlength'=>14, 'disabled'=>'disabled'))); ?>
						</div><!-- /panel-body input-group -->
					</div><!-- /col-md.3 -->
				</div><!-- /Row -->
				<div class="row">
					<div class="col-md-12 col-sm-12 col-xs-12">
						<div class="panel-body input-group input-group-sm">
							<span class="input-group-addon">Editor comercial:</span>
							<?php echo $this->form_complete->create_element(array('id'=>'editor_comercial', 'type'=>'text', 'value'=>$publicacion->ec_nombre, 'attributes'=>array('class'=>'form-control', 'disabled'=>'disabled'))); ?>
						</div><!-- /panel-body input-group -->
					</div><!-- /col-md.6 -->
				</div><!-- /Row -->
				<div class="row">
					<div class="col-md-6 col-sm-6 col-xs-12">
						<div class="panel-body input-group input-group-sm">
							<span class="input-group-addon">País:</span>
							<?php echo $this->form_complete->create_element(array('id'=>'pais', 'type'=>'text', 'value'=>$publicacion->pais_nombre, 'attributes'=>array('class'=>'form-control', 'disabled'=>'disabled'))); ?>
						</div><!-- /panel-body input-group -->
					</div><!-- /col-md.6 -->
					<div class="col-md-6 col-sm-6 col-xs-12">
						<div class="panel-body input-group input-group-sm">
							<span class="input-group-addon">Idioma:</span>
							<?php echo $this->form_complete->create_element(array('id'=>'idioma', 'type'=>'text', 'value'=>$publicacion->lang_idioma, 'attributes'=>array('class'=>'form-control', 'disabled'=>'disabled'))); ?>
						</div><!-- /panel-body input-group -->
					</div><!-- /col-md.6 -->
				</div><!-- /Row -->
				<div class="row">
					<div class="col-md-6 col-sm-6 col-xs-12">
						<div class="panel-body  input-group input-group-sm">
							<span class="input-group-addon">Periodicidad:</span>
							<?php echo $this->form_complete->create_element(array('id'=>'periodicidad', 'type'=>'text', 'value'=>$publicacion->pub_periodicidad, 'attributes'=>array('class'=>'form-control', 'maxlength'=>100, 'disabled'=>'disabled'))); ?>
						</div><!-- /panel-body input-group -->
					</div><!-- /col-md.6 -->
					<div class="col-md-6 col-sm-6 col-xs-12">
						<div class="panel-body input-group input-group-sm">
							<span class="input-group-addon">Formato:</span>
							<?php echo $this->form_complete->create_element(array('id'=>'formato', 'type'=>'text', 'value'=>$formatos[$publicacion->pub_formato], 'attributes'=>array('class'=>'form-control', 'disabled'=>'disabled'))); ?>
						</div><!-- /panel-body input-group -->
					</div><!-- /col-md.6 -->
				</div><!-- /Row --><hr>
				<div class="row">
					<div class="col-md-10 col-sm-10 col-xs-10">
						Temática(s):
					</div><!-- /col-md.6 -->
				</div><!-- /Row -->
				<div id="div_tematicas" class="row">
					<?php foreach ($publicacion->tematica_nombre as $key_pt => $publicacion_tematica) {
						echo '<div class="col-md-4 col-sm-6 col-xs-12">
								<div class="panel-body">
									<div class="btn btn-default btn-block btn-no-pointer">
										<div class="badge_tematica">'.$publicacion_tematica.'</div>
									</div>
								</div>
							</div>';
					} ?>
				</div><!-- /Row --><hr>
				<div class="row">
					<div class="col-md-10 col-sm-10 col-xs-10">
						Base de datos:
					</div><!-- /col-md.6 -->
				</div>
				<div id="div_base_datos" class="row">
					<?php foreach ($publicacion->bd_nombre as $key_bds => $bds) {
						echo '<div class="col-md-12 col-sm-12 col-xs-12">
								<div class="panel-body">
									<div class="btn btn-default btn-block btn-no-pointer">
										<div class="badge_bds">'.$bds.'</div>
										<div class="panel-body  input-group input-group-sm">
											<span class="input-group-addon">URL de la base de datos:</span>
											'.$this->form_complete->create_element(array('id'=>'bdurl_'.$key_bds, 'type'=>'text', 'value'=>$publicacion->bd_url[$key_bds], 'attributes'=>array('class'=>'form-control', 'maxlength'=>100, 'disabled'=>'disabled'))).'
										</div>
									</div>
								</div>
							</div>';
					} ?>
				</div><!-- /Row --><hr>
				<div class="row">
					<div class="col-md-12 col-sm-12 col-xs-12"><h3 class="centrame">Cobertura/Vigencia en CONRICyT:</h3></div>
					<div class="col-md-6 col-sm-12 col-xs-12">
						<h4 class="panel-body">Fecha más reciente</h4>
						<div class="col-md-4 col-sm-4 col-xs-12">
							<div class="input-group input-group-sm">
								<span class="input-group-addon">* Año:</span>
								<?php echo $this->form_complete->create_element(array('id'=>$coberturas_config['reciente']['id'].'_anio', 'type'=>'text', 'value'=>((isset($publicacion_coberturas[$coberturas_config['reciente']['id']]->cp_anio)) ? $publicacion_coberturas[$coberturas_config['reciente']['id']]->cp_anio : ''), 'attributes'=>array('class'=>'form-control', 'maxlength'=>4, 'disabled'=>'disabled'))); ?>
							</div>
						</div>
						<div class="col-md-4 col-sm-4 col-xs-12">
							<div class="input-group input-group-sm">
								<span class="input-group-addon">* Volúmen:</span>
								<?php echo $this->form_complete->create_element(array('id'=>$coberturas_config['reciente']['id'].'_volumen', 'type'=>'text', 'value'=>((isset($publicacion_coberturas[$coberturas_config['reciente']['id']]->cp_vol)) ? $publicacion_coberturas[$coberturas_config['reciente']['id']]->cp_vol : ''), 'attributes'=>array('class'=>'form-control', 'maxlength'=>4, 'disabled'=>'disabled'))); ?>
							</div>
						</div>
						<div class="col-md-4 col-sm-4 col-xs-12">
							<div class="input-group input-group-sm">
								<span class="input-group-addon">* Número:</span>
								<?php echo $this->form_complete->create_element(array('id'=>$coberturas_config['reciente']['id'].'_numero', 'type'=>'text', 'value'=>((isset($publicacion_coberturas[$coberturas_config['reciente']['id']]->cp_num)) ? $publicacion_coberturas[$coberturas_config['reciente']['id']]->cp_num : ''), 'attributes'=>array('class'=>'form-control', 'maxlength'=>4, 'disabled'=>'disabled'))); ?>
							</div>
						</div>
					</div><!-- /col-md.6 -->
					<div class="col-md-6 col-sm-12 col-xs-12">
						<h4 class="panel-body">Fecha más antigua</h4>
						<div class="col-md-4 col-sm-4 col-xs-12">
							<div class="input-group input-group-sm">
								<span class="input-group-addon">* Año:</span>
								<?php echo $this->form_complete->create_element(array('id'=>$coberturas_config['antiguo']['id'].'_anio', 'type'=>'text', 'value'=>((isset($publicacion_coberturas[$coberturas_config['antiguo']['id']]->cp_anio)) ? $publicacion_coberturas[$coberturas_config['antiguo']['id']]->cp_anio : ''), 'attributes'=>array('class'=>'form-control', 'maxlength'=>4, 'disabled'=>'disabled'))); ?>
							</div>
						</div>
						<div class="col-md-4 col-sm-4 col-xs-12">
							<div class="input-group input-group-sm">
								<span class="input-group-addon">* Volúmen:</span>
								<?php echo $this->form_complete->create_element(array('id'=>$coberturas_config['antiguo']['id'].'_volumen', 'type'=>'text', 'value'=>((isset($publicacion_coberturas[$coberturas_config['antiguo']['id']]->cp_vol)) ? $publicacion_coberturas[$coberturas_config['antiguo']['id']]->cp_vol : ''), 'attributes'=>array('class'=>'form-control', 'maxlength'=>4, 'disabled'=>'disabled'))); ?>
							</div>
						</div>
						<div class="col-md-4 col-sm-4 col-xs-12">
							<div class="input-group input-group-sm">
								<span class="input-group-addon">* Número:</span>
								<?php echo $this->form_complete->create_element(array('id'=>$coberturas_config['antiguo']['id'].'_numero', 'type'=>'text', 'value'=>((isset($publicacion_coberturas[$coberturas_config['antiguo']['id']]->cp_num)) ? $publicacion_coberturas[$coberturas_config['antiguo']['id']]->cp_num : ''), 'attributes'=>array('class'=>'form-control', 'maxlength'=>4, 'disabled'=>'disabled'))); ?>
							</div>
						</div>
					</div><!-- /col-md.6 -->
				</div><!-- /Row --><br>
				<div class="row">
					<div class="col-md-12 col-sm-12 col-xs-12">
						<div class="panel-body"> 
							<!-- <h4 class="panel-body">Fuentes de texto completo</h4> -->
							<?php //echo htmlentities($publicacion->pub_fuentes);
								echo "<h4 class='panel-body'>Notas</h4>";
							 echo htmlentities($publicacion->pub_notas);
							?>
						</div>
					</div>
				</div><!-- /Row -->
			</div>  <!-- /panel-body-->
		</div> <!-- /panel panel-morado-->
	</div> <!-- /col 12-->
</div> <!-- row 12-->
<style type="text/css">
@media (min-width: 850px) {
  .modal-dialog {
    width: 960px;
  }
/*  .modal-sm {
    width: 300px;
  }*/
}
</style>