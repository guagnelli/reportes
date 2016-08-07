<style type="text/css">
.page-header{
    color:#000; border-bottom: 1px solid #000; margin-top: 20px;
}
</style>
<div class="row">
    <div class="container">
        <div class="page-header">Mis publicaciones</div>
        <div class="col-lg-6 col-md-6 col-sm-6">
            <!-- <h3>Títulos de publicaciones</h3> -->
            <table class="table table-condensed">
                <tr>
                    <td>Total:</td>
                    <td><?php echo $mi_total; ?></td>
                </tr>
                <tr>
                    <td>Validados:</td>
                    <td><?php echo $mis_validados; ?></td>
                </tr>
                <tr>
                    <td>Sin validar:</td>
                    <td><?php echo $mis_sin_validar; ?></td>
                </tr>
            </table>
            
            <div class="col-sm-12 col-xs-12">
                <div class="progress">
                    <div class="progress-bar progress-bar-success" role="progressbar" aria-valuenow="100" aria-valuemin="0" aria-valuemax="100" style="width: 100%;">
                        Total: <?php echo $mi_total; ?>
                    </div>
                </div>
                <div class="progress">
                    <div class="progress-bar progress-bar-info" role="progressbar" aria-valuenow="<?php echo $mis_validados_porcentaje; ?>" aria-valuemin="0" aria-valuemax="100" style="width: <?php echo $mis_validados_porcentaje; ?>%;">
                        Validados: <?php echo $mis_validados; ?>
                    </div>
                </div>
                <div class="progress">
                    <div class="progress-bar progress-bar-warning pull-right" role="progressbar" aria-valuenow="<?php echo $mis_sin_validar_porcentaje; ?>" aria-valuemin="0" aria-valuemax="100" style="width: <?php echo $mis_sin_validar_porcentaje; ?>%">
                        Sin validar: <?php echo $mis_sin_validar; ?>
                    </div>
                </div>
            </div>
        </div>
        
        <div class="col-lg-6 col-md-6 col-sm-6">
            <h3>&Uacute;ltimo recurso actualizado</h3>
            <table class="table table-condensed">
                <tr>
                    <td>Fecha de actualizaci&oacute;n:</td>
                    <td><?php echo ($val_fecha!="-") ? get_fecha(3, new DateTime($val_fecha)) : "-"; ?></td>
                </tr>
                <tr>
                    <td>ISSN:</td>
                    <td><?php echo $pub_issn; ?></td>
                </tr>
                <tr>
                    <td>T&iacute;tulo:</td>
                    <td><?php echo $t_titulo; ?></td>
                </tr>
                <tr>
                    <td>Estado:</td>
                    <td><?php echo $est_nombre; ?></td>
                </tr>
                <tr>
                    <td>Forma de acceso:</td>
                    <td><?php echo $rev_tipo; ?></td>
                </tr>
            </table>
            
        </div>
    </div>
</div>
<div class="row">
    <div class="container">
        <div class="page-header">Total de publicaciones</div>
        <div class="col-lg-6 col-md-6 col-sm-6 col-xs-6">
            <table class="table table-condensed">
                <tr>
                    <td>Total:</td>
                    <td><?php echo $total; ?></td>
                </tr>
                <tr>
                    <td>Validados:</td>
                    <td><?php echo $validados; ?></td>
                </tr>
                <tr>
                    <td>Sin validar:</td>
                    <td><?php echo $sin_validar; ?></td>
                </tr>
            </table>
        </div>
        <div class="col-lg-6 col-md-6 col-sm-6 col-xs-6">
            <div class="progress">
                <div class="progress-bar progress-bar-success" role="progressbar" aria-valuenow="100" aria-valuemin="0" aria-valuemax="100" style="width: 100%;">
                    Total: <?php echo $total; ?>
                </div>
            </div>
            <div class="progress">
                <div class="progress-bar progress-bar-info" role="progressbar" aria-valuenow="<?php echo $validados_porcentaje; ?>" aria-valuemin="0" aria-valuemax="100" style="width: <?php echo $validados_porcentaje; ?>%;">
                    Validados: <?php echo $validados; ?>
                </div>
            </div>
            <div class="progress">
                <div class="progress-bar progress-bar-warning pull-right" role="progressbar" aria-valuenow="<?php echo $sin_validar_porcentaje; ?>" aria-valuemin="0" aria-valuemax="100" style="width: <?php echo $sin_validar_porcentaje; ?>%">
                    Sin validar: <?php echo $sin_validar; ?>
                </div>
            </div>
        </div>
    </div>
</div>

<script type="text/javascript">
$(document).ready(function(){
	$('[data-toggle="tooltip"]').tooltip();
});
</script>
