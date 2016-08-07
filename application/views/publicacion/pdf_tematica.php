<?php
	//-------------titulo de documento
	if(isset($titulo_doc) ){
		$Titulo=$titulo_doc;
	}

	//-------------Tabla
	//Ancho de celda
	if(isset($CtllAnchoC) ){
		$ancho_encabezado=$CtllAnchoC;
	}
	//Alineación de celda
	if(isset($CtllAlineacionC) ){
		$alineacion=$CtllAlineacionC;
	}
	//Encabezado
	if(isset($CtllEncabezado) ){
		$tEncabezado=$CtllEncabezado;
	}
    //Datos de tabla
	if(isset($CtllCampos) ){
		$nombreEbzado=$CtllCampos;
	}
	//---Número de Registros
	if(isset($CtllNumRegistros) ){
	$num_filas=$CtllNumRegistros;
	}
	//---Número de Campos
	$tot_campos=count($tEncabezado);

	//Creación de Tabla
	for($j=0; $j <$tot_campos ; $j++)
	{
		$ancho_celda[$j] =$ancho_encabezado;
		$aling_celda[$j] =$alineacion;
		$cabecera[$j] =utf8_decode($tEncabezado[$j]);
	}


	if(isset($CtllDatosTabla) ){
	
		for($k=0; $k <$num_filas ; $k++)
		{
			for($m=0; $m <$tot_campos ; $m++)
			{
				$datosPersona[$k][$m] =utf8_decode($CtllDatosTabla[$k][($nombreEbzado[$m])]);
					//pr($datosPersona[$k][$m]);
	
			}
			//echo "---";
		}
		//exit();
	}	
	
	//Se crea un objeto de PDF
    //Para hacer uso de los metodos
	$this->pdfv->AddFont('Calibri','','calibri.php');
	$this->pdfv->AddFont('Calibrib','','calibrib.php');
	$this->pdfv->AddPage(); //horizontal, Carta
	
	//Margenes
	//izquierda, arriba y derecha
	$this->pdfv->SetMargins(12.7, 12.7, 12.7);
	//inferior:
	$this->pdfv->SetAutoPageBreak(true,12.7);
	
	//--Texto Titulo
	$this->pdfv->SetXY(12.7,40.7);
	$this->pdfv->SetFont('Calibrib','',15); 
	$this->pdfv->Cell(190.6,5,$Titulo,0,0,$alineacion);
	$this->pdfv->Ln(5);
	

	//Tabla Horizontal
	$this->pdfv->tablaHorizontal($cabecera, $datosPersona,$ancho_celda,$aling_celda, $ancho_encabezado);
	$this->pdfv->Close();
   // $this->pdfcdpi->Output('pdf.pdf','D'); //Salida al navegador del pdf
?>