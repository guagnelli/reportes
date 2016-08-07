<table class="table table-bordered table-striped">
	<thead>
		<tr>
			<th width="80%">Evidencia</th>
			<th width="20%">Opciones</th>
		</tr>
	</thead>
	<tbody>
		<?php 
		if(exist_and_not_null($evidencias)){
			foreach ($evidencias as $key_ev => $evidencia) {
				if($evidencia!="." && $evidencia!=".."){
					echo '<tr>
							<td><a href="'.$evidencias_ruta.$evidencia.'" target="_blank">'.$evidencia.'</a></td>
							<td><a data-valor="'.$evidencia.'" data-confirm="¿Esta seguro de eliminar la evidencia \''.$evidencia.'\'?" style="cursor:pointer;">
								<span class="glyphicon glyphicon-trash turqueza" data-toggle="tooltip" data-placement="top" title="Eliminar"></span></a></td>
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
<script type="text/javascript">
$(document).ready(function() {
	$('a[data-confirm]').click(function(ev) {
		ev.preventDefault();
		var title = (typeof($(this).attr('title')) === "undefined") ? "Alerta" : $(this).attr('title');
		var valor = $(this).attr('data-valor');
		if($('#dataConfirmModal').length>0) {
			$('#dataConfirmModal').remove();
		}
		var html = "<div class='modal fade' id='dataConfirmModal' role='dialog' aria-labelledby='dataConfirmLabel'>"+
				"<div class='modal-dialog'>"+
					"<div class='modal-content'>"+
						"<div class='modal-header'>"+
							"<button type='button' class='close' data-dismiss='modal' aria-hidden='true'>&times;</button>"+
							"<h4 class='modal-title'>"+title+"</h4>"+
						"</div>"+
						"<div class='modal-body'>"+
							"<p>"+$(this).attr('data-confirm')+"</p>"+
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
			eliminar_archivo(valor);
		});
		$('#dataConfirmModal').modal({show:true});
		return false;
	});
});
</script>