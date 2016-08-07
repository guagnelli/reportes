<?php $publicacion_tipo_revision = $this->config->item('publicacion_tipo_revision');
$revision_estado = $this->config->item('revision_estado'); ?>
<div class='col-sm-12 center'>
	<table class="table table-striped table-bordered dataTable no-footer" cellspacing="0">
		<thead>
			<tr>
				<th>ISSN</th>
				<th>Título</th>
				<th>Responsable</th>
				<th>Idioma</th>
				<th>Estado de la publicación</th>
				<th>Base de datos</th>
				<th width="20px">Revisión</th>
			</tr>
		</thead>
		<tbody>
			<?php
			foreach ($data as $key => $publicacion) {
				$publicacion['titulo'] = "- ".str_replace(",", "<br /><span class='turqueza'>-</span> ", $publicacion['titulo']);
				//$base_datos = explode(",", $publicacion['base_datos']);
				if(count($publicacion['base_datos'])>1){
					$rowspan = ' rowspan='.count($publicacion['base_datos']);
					foreach ($publicacion['base_datos'] as $key_bd => $bd) {
						$interna = $remota = "rojo";
						if(exist_and_not_null_array($bd, 'revision')){
							foreach ($bd['revision'] as $key_rev => $revision) {
								//pr($revision);
								if($revision['rev_tipo']==$publicacion_tipo_revision['interna']['id'] && $revision['rev_estado']==$revision_estado['completa']){
									$interna = "verde";
								}
								if($revision['rev_tipo']==$publicacion_tipo_revision['remota']['id'] && $revision['rev_estado']==$revision_estado['completa']) {
									$remota = "verde";
								}
							}
						}
						$html_base = '<td>'.$bd['bd_nombre'].'</td><td class="text-center"><a href="'.site_url('publicacion/revision_interna/'.encrypt_base64($publicacion['pub_id'])).'/'.encrypt_base64($bd['bd_id']).'"><span class="glyphicon glyphicon-log-in '.$interna.'" data-toggle="tooltip" data-placement="top" title="Revisión Red Interna"></span></a>&nbsp;&nbsp;
							<a href="'.site_url('publicacion/revision_remota/'.encrypt_base64($publicacion['pub_id'])).'/'.encrypt_base64($bd['bd_id']).'"><span class="glyphicon glyphicon-new-window '.$remota.'" data-toggle="tooltip" data-placement="top" title="Revisión Red Externa"></span></a></td>';
						
						if($key_bd>0){
							echo '<tr>'.$html_base.'</tr>';
						} else {
							echo '<tr>
								<td '.$rowspan.'><a href="'.site_url('publicacion/edicion/'.encrypt_base64($publicacion['pub_id'])).'" data-toggle="tooltip" data-placement="top" title="Edición">'.$publicacion['issn'].'</a></td>
				                <td '.$rowspan.'>'.$publicacion['titulo'].'</td>
				                <td '.$rowspan.'>'.$publicacion['responsable'].'</td>
				                <td '.$rowspan.'>'.$publicacion['idioma'].'</td>
				                <td '.$rowspan.' class="text-center">'.$publicacion['estado_publicacion'].'</td>
				                '.$html_base.'
							</tr>';
						}
					}
				} else {
					$interna = $remota = "rojo";
					if(exist_and_not_null_array($publicacion['base_datos'][0], 'revision')){
						$interna = $remota = "rojo";
						foreach ($publicacion['base_datos'][0]['revision'] as $key_rev => $revision) {
							if($revision['rev_tipo']==$publicacion_tipo_revision['interna']['id'] && $revision['rev_estado']==$revision_estado['completa']){
								$interna = "verde";
							}
							if($revision['rev_tipo']==$publicacion_tipo_revision['remota']['id'] && $revision['rev_estado']==$revision_estado['completa']) {
								$remota = "verde";
							}
						}
					}
					echo '<tr>
						<td><a href="'.site_url('publicacion/edicion/'.encrypt_base64($publicacion['pub_id'])).'" data-toggle="tooltip" data-placement="top" title="Edición">'.$publicacion['issn'].'</a></td>
		                <td>'.$publicacion['titulo'].'</td>
		                <td>'.$publicacion['responsable'].'</td>
		                <td>'.$publicacion['idioma'].'</td>
		                <td>'.$publicacion['estado_publicacion'].'</td>	                
		                <td>'.$publicacion['base_datos'][0]['bd_nombre'].'</td>
						<td class="text-center"><a href="'.site_url('publicacion/revision_interna/'.encrypt_base64($publicacion['pub_id'])).'/'.encrypt_base64($publicacion['base_datos'][0]['bd_id']).'"><span class="glyphicon glyphicon-log-in '.$interna.'" data-toggle="tooltip" data-placement="top" title="Revisión Red Interna"></span></a>&nbsp;&nbsp;
		                	<a href="'.site_url('publicacion/revision_remota/'.encrypt_base64($publicacion['pub_id'])).'/'.encrypt_base64($publicacion['base_datos'][0]['bd_id']).'"><span class="glyphicon glyphicon-new-window '.$remota.'" data-toggle="tooltip" data-placement="top" title="Revisión Red Externa"></span></a></td>
					</tr>';
				}
			}
			?>
		</tbody>
	</table>
</div>
<script type="text/javascript">
$(document).ready(function(){
	$('[data-toggle="tooltip"]').tooltip();
});
</script>