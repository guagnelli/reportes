
<div class='col-sm-12 center table-responsive'>
	<table class="table table-striped table-bordered dataTable no-footer" cellspacing="0">
		<thead>
			<tr>
				<th>ISSN</th>
				<th>Título</th>
				<th>Idioma</th>
				<th>Base de datos</th>
                                <!--<th>URL publicación</th>-->
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
                                            $txt_url="";
                                            if(!empty($bd['pub_bd_url'])){
                                                $plus_url = $bd['pub_bd_url'];
                                                
                                                if(strpos($plus_url, "#") >=0){
                                                    $plus_url =  str_replace('#','',$plus_url);                                                     
                                                }
                                                $protocolo = strpos($plus_url, 'http');
                                                $txt_url=( !empty($protocolo) ? '<a href="'. $plus_url .'" target="_blank">Ver página web</a>' : '<a href="http://'. $plus_url .'" target="_blank">Ver página web</a>');
                                            }
						
						$html_base = '<td>'.$bd['bd_nombre'].'</td>';//<td>'. $txt_url .'</td>
                                                                                                
						if($key_bd>0){
							echo '<tr>'.$html_base.'</tr>';
						}else{
							echo '<tr>
								<td '.$rowspan.'>'.$publicacion['issn'].'</td>
				                <td '.$rowspan.'>'.$publicacion['titulo'].'</td>
				                <td '.$rowspan.'>'.$publicacion['idioma'].'</td><t></td>
				                
				                '.$html_base.'
							</tr>';
						}
					}
				}else{
                                    
                                    $txt_url="";
                                            if(!empty($publicacion['base_datos'][0]['pub_bd_url'])){
                                                $plus_url = $publicacion['base_datos'][0]['pub_bd_url'];
                                                
                                                if( strpos($plus_url, "#") >=0 ){
                                                    $plus_url =  str_replace('#','',$plus_url);                                                     
                                                }
                                                
                                                $protocolo = strpos($plus_url, 'http');
                                                $txt_url = ( !empty($protocolo) ? '<a href="'. $plus_url .'" target="_blank">Ver página web</a>' : '<a href="http://'. $plus_url .'" target="_blank">Ver página web</a>');
                                            }
                                          /*  
                                    $txt_url="";
                                            if(!empty($publicacion['base_datos'][0]['pub_bd_url'])){
                                                $txt_url=( (strpos($publicacion['base_datos'][0]['pub_bd_url'], "#") >=0) ? '<a href="'. str_replace('#','',$publicacion['base_datos'][0]['pub_bd_url']) .'" target="_blank">Ver página web</a>' : '<a href="'.$publicacion['base_datos'][0]['pub_bd_url'] .'" target="_blank">Ver página web</a>');
                                            }*/
					echo '<tr>
						<td>'.$publicacion['issn'].'</td>
		                <td>'.$publicacion['titulo'].'</td>
		                <td>'.$publicacion['idioma'].'</td>              
		                <td>'.$publicacion['base_datos'][0]['bd_nombre'].'</td>
                                
                                </tr>'; // <td>'. $txt_url .'</td>  //( (!empty($publicacion['base_datos'][0]['pub_bd_url'])) ? '<a href="'. str_replace("#","",$publicacion['base_datos'][0]['pub_bd_url']) .'" target="_blank">Ver página web</a>' : ''  )
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