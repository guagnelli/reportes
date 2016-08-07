<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
require_once('fpdf.php');
class Pdfh extends FPDF
{
	// Extend FPDF using this class
	// More at fpdf.org -> Tutorials
	function __construct($orientation='P', $unit='mm', $size='letter')
	{
	// Call parent constructor
	parent::__construct($orientation,$unit,$size);
    date_default_timezone_set('America/Mexico_City');
	}
	

	//-----------------------------------------------------------------Horizontal------------------------------------------
	/////Reporte de alumnos
    function Footer()
    {
       
		$this->SetTextColor(98, 98, 100);
		$this->AddFont('Calibrib','','calibrib.php');
        $this->SetFont('Calibri','',11);
		
		//Nombre de usuario
		$nombreUser =$_SESSION['nombre'].' '.$_SESSION['apaterno'].' '.$_SESSION['amaterno'];
		$this->SetXY(12.7,-15.6);
		$this->Cell(210,11.6,'Generado por: '.$nombreUser,0,0,'L');
		
		//Fecha
		$hoy = getdate();
		$Meses=array('1'=>'Enero', '2'=>'Febrero', '3'=>'Marzo','4'=>'Abril', '5'=>'Mayo', '6'=>'Junio','7'=>'Julio','8'=>'Agosto','9'=>'Septiembre','10'=>'Octubre','11'=>'Noviembre','12'=>'Diciembre');
        $this->SetXY(223,-15.6);
		$this->Cell(40,11.6,$hoy['mday'].'/'.$Meses[$hoy['mon']].'/'.$hoy['year'],0,0,'R');
    }
 
    function Header()
    {
		
		///------------------------------------>Fila1
		$porcentaje = 0.25;
		list($ancho, $alto) = getimagesize('assets/img/cabeceraVertical.jpg');
		$nuevo_ancho = $ancho * $porcentaje;
		$nuevo_alto = $alto * $porcentaje;
		$this->SetXY(0.5,12.7);
        $this->Cell(215,18,'',0,0,'C',$this->Image(substr($_SERVER['SCRIPT_FILENAME'],0,-9).'assets/img/cabeceraVertical.jpg', 5,12.7, $nuevo_ancho,$nuevo_alto));
	
		///------------------------------------>Fila2
		//Espacio
		$this->SetXY(0,30.7);
		$this->Cell(215.6,6.7,'',0,1,'R');

    }
 
    function ImprimirTexto($file)
    {
        $txt = file_get_contents($file);
        $this->SetFont('Arial','',12);
        $this->MultiCell(0,5,$txt);
 
    }
 
    function cabecera($cabecera)
    {
        $this->SetXY(50,115);
        $this->SetFont('Arial','B',11);
        foreach($cabecera as $columna)
        {
            $this->Cell(40,10,$columna,1, 2 , 'L' ) ;
        }
    }
	    function cabeceraH($cabecera,$ancho)
    {
		$this->medidaX = -22;
        $this->SetFont('Arial','B',11);
		foreach($cabecera as $fila)
        {
			$this->medidaX += $ancho;
			$this->SetXY($this->medidaX,90);
            $this->Cell($ancho,5,$fila,1, 2 , 'C' ) ;
        }
    }
	
		function datosH($datos,$ancho)
    {
		$this->medidaX = -22;
        $this->SetFont('Arial','',11);
        foreach($datos as $fila)
        {
			$this->medidaX += $ancho;
			$this->SetXY($this->medidaX,90);
            $this->Cell($ancho,5,$fila,1, 2 , 'L' ) ;
        }
    }
 
    function datos($datos)
    {
        $this->SetXY(90,115);
        $this->SetFont('Arial','',12);
            foreach ($datos as $columna)
            {
				$this->Cell(40,7,$columna,1, 2 , 'C' ) ;
            }
 
    }
 
    //El método tabla integra a los métodos cabecera y datos
    function tabla($cabecera,$datos)
    {
        $this->cabeceraH ($cabecera,32) ;
		$this->datosH ($datos,32) ;
    }
	
	//////////////////////////////////////////////////////////////////////////////////////////////
	/////////////////////////////Tablas con Ajuste de Texto////////////////////////////////////////////////
	function cabeceraHorizontal($cabecera,$ancho)
    {
        $this->SetXY(12.7, 60);
		//$this->AddFont('Calibrib','','calibrib.php');
        //$this->SetFont('Calibrib','',11);
        $this->SetFillColor(255,255,255);//Fondo verde de celda
        $this->SetTextColor(0, 0, 0); //Letra color blanco
        foreach($cabecera as $fila)
        {
 
            $this->CellFitSpace($ancho,7, $fila,1, 0 , 'C', true);
 
        }
    }
 
    function datosHorizontal($datos,$Wdatos,$Adatos)
    {
		//$this->AddFont('Calibrib','','calibrib.php');
        $this->SetFont('Calibri','',11);
        $this->SetXY(12.7,67);
        $this->SetFillColor(229, 229, 229); //Gris tenue de cada fila
        $this->SetTextColor(3, 3, 3); //Color del texto: Negro
        $bandera = false; //Para alternar el relleno

		foreach($datos as $fila)
        {
			//un arreglo con su medida  a lo ancho
			$this->SetWidths($Wdatos);
			//un arreglo con alineacion de cada celda
			$this->SetAligns($Adatos);
			$this->Row($fila);
			$this->Ln(0);//Salto de línea para generar otra fila
        }
    }
 
    function tablaHorizontal($cabeceraHorizontal, $datosHorizontal,$Wdatos,$Adatos,$Acelda)
    {
        $this->cabeceraHorizontal($cabeceraHorizontal,$Acelda);//50
        $this->datosHorizontal($datosHorizontal,$Wdatos,$Adatos);
    }
 
    //***** Aquí comienza código para ajustar texto *************
    //***********************************************************
    function CellFit($w, $h=0, $txt='', $border=0, $ln=0, $align='', $fill=false, $link='', $scale=false, $force=true)
	{
        //Get string width
		//Obtiene el ancho de la cadena
        $str_width=$this->GetStringWidth($txt);
 
        //Calculate ratio to fit cell
		//calcula la distancia para adaptarse con la celda
        if($w==0)
            $w = $this->w-$this->rMargin-$this->x;
			$ratio = ($w-$this->cMargin*2)/$str_width;
			$fit = ($ratio < 1 || ($ratio > 1 && $force));
			if ($fit)
			{
				if ($scale)
				{
					
					//Calculate horizontal scaling
					//Calcula la escala horizontal
					$horiz_scale=$ratio*100.0;
					//Set horizontal scaling
					//Ajusta la escala horizontal
					$this->_out(sprintf('BT %.2F Tz ET',$horiz_scale));
				}
				else
				{
					//Calculate character spacing in points
					//Calcular el espacio de caracteres entre los puntos
					$char_space=($w-$this->cMargin*2-$str_width)/max($this->MBGetStringLength($txt)-1,1)*$this->k;
					//Set character spacing
					//Ajusta el espacio entre caracteres
					$this->_out(sprintf('BT %.2F Tc ET',$char_space));
					
				}
				//Override user alignment (since text will fill up cell)
				//Invalida la alineación de usuario (ya que el texto llenara la celda
				$align='';
				
			}
			//Pass on to Cell method
			$this->Cell($w,$h,$txt,$border,$ln,$align,$fill,$link);
	 
			//Reset character spacing/horizontal scaling
			//Cambia el espacio entre caracteres / escala horizontal
			if ($fit)
				$this->_out('BT '.($scale ? '100 Tz' : '0 Tc').' ET');
    }
 
    function CellFitSpace($w, $h=0, $txt='', $border=0, $ln=0, $align='', $fill=false, $link='')
    {
        $this->CellFit($w,$h,$txt,$border,$ln,$align,$fill,$link,false,false);
    }
 
    //Patch to also work with CJK double-byte text
    function MBGetStringLength($s)
    {
        if($this->CurrentFont['type']=='Type0')
        {
            $len = 0;
            $nbbytes = strlen($s);
            for ($i = 0; $i < $nbbytes; $i++)
            {
                if (ord($s[$i])<128)
                    $len++;
                else
                {
                    $len++;
                    $i++;
                }
            }
            return $len;
        }
        else
            return strlen($s);
    }
//************** Fin del código para ajustar texto *****************
//******************************************************************
 
 /////////////////////////////////////////////////////////////////////////////////////////////////////
 /////////////////////////////////////AJUSTE CON SALTO DE LINEA///////////////////////////////////////
 ////////////////////////////////////////////////////////////////////////////////////////////////////
 var $widths; 
var $aligns; 

function SetWidths($w) 
{ 
    //Set the array of column widths 
    $this->widths=$w; 
} 

function SetAligns($a) 
{ 
    //Set the array of column alignments 
    $this->aligns=$a; 
} 

function fill($f)
{
	//juego de arreglos de relleno
	$this->fill=$f;
}
 function Row($data) 
{ 
    //Calculate the height of the row 
    $nb=0; 
    for($i=0;$i<count($data);$i++) 
        $nb=max($nb,$this->NbLines($this->widths[$i],$data[$i])); 
    $h=5*$nb; 
    //Issue a page break first if needed 
    $this->CheckPageBreak($h); 
    //Draw the cells of the row 
    for($i=0;$i<count($data);$i++) 
    { 
        $w=$this->widths[$i]; 
        $a=isset($this->aligns[$i]) ? $this->aligns[$i] : 'L'; 
        //Save the current position 
        $x=$this->GetX(); 
        $y=$this->GetY(); 
        //Draw the border 
        $this->Rect($x,$y,$w,$h,'S'); 
        //Print the text 
        $this->MultiCell($w,5,$data[$i],'LTR',$a,false); 
        //Put the position to the right of the cell 
        $this->SetXY($x+$w,$y); 
    } 
    //Go to the next line 
    $this->Ln($h); 
} 

function CheckPageBreak($h) 
{ 
    //If the height h would cause an overflow, add a new page immediately 
    if($this->GetY()+$h>$this->PageBreakTrigger) 
        $this->AddPage($this->CurOrientation); 
} 

function NbLines($w,$txt) 
{ 
    //Computes the number of lines a MultiCell of width w will take 
    $cw=&$this->CurrentFont['cw']; 
    if($w==0) 
        $w=$this->w-$this->rMargin-$this->x; 
    $wmax=($w-2*$this->cMargin)*1000/$this->FontSize; 
    $s=str_replace("\r",'',$txt); 
    $nb=strlen($s); 
    if($nb>0 and $s[$nb-1]=="\n") 
        $nb--; 
    $sep=-1; 
    $i=0; 
    $j=0; 
    $l=0; 
    $nl=1; 
    while($i<$nb) 
    { 
        $c=$s[$i]; 
        if($c=="\n") 
        { 
            $i++; 
            $sep=-1; 
            $j=$i; 
            $l=0; 
            $nl++; 
            continue; 
        } 
        if($c==' ') 
            $sep=$i; 
        $l+=$cw[$c]; 
        if($l>$wmax) 
        { 
            if($sep==-1) 
            { 
                if($i==$j) 
                    $i++; 
            } 
            else 
                $i=$sep+1; 
            $sep=-1; 
            $j=$i; 
            $l=0; 
            $nl++; 
        } 
        else 
            $i++; 
    } 
    return $nl; 
} 
}  
?>