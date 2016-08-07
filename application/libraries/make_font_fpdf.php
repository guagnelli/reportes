<?php  if (!defined('BASEPATH')) exit('No direct script access allowed');
/**
 * CodeIgniter
 *
 * An open source application development framework for PHP 4.3.2 or newer
 *
 * @package		
 * @author		
 * @copyright	
 * @license		
 * @link		
 * @since		
 */

// ------------------------------------------------------------------------
class Make_font_fpdf{
	/**
	 * [crear_fuente description]
	 * @param  string $ruta Ruta física donde se almacenan los archivos a procesar
	 * @return [type]       [description]
	 */
	public function crear_fuente($ruta){
		require('makefont/makefont.php');
		$path = 'assets/files/makefont/';
		MakeFont($ruta, 'cp1252', true, $path);
		$zip = $this->create_zip($path);

		if($zip['resultado']==true){

		}
	}

	private function create_zip($path){
		$resultado = array('resultado'=>false, 'mensaje'=>null);
		
		$files = scandir($path); //Se escanea directorio buscando los archivos
		$ext_creados = array('php', 'ttf', 'z');
		$archivos_eliminar = array();
		$zip_path = $path.'font_'.date('Y-m-d').'.zip';

		$zip = new ZipArchive();
		if($zip->open($zip_path, ZipArchive::CREATE)===TRUE){ //Se crea archivo .zip
			foreach ($files as $key => $file) {
				if(in_array(pathinfo($file, PATHINFO_EXTENSION), $ext_creados)){ //Se verifica extensión
					$zip->addFile($path.$file, $file); //Se añade archivo al zip
					$archivos_eliminar[] = $path.$file;
				}
			}
			$zip->close();
			$this->eliminar_archivo($archivos_eliminar);
			$this->stream_zip($zip_path);
			echo "Archivo generado exitosamente.";
			$resultado['resultado'] = true;
		} else {
			$resultado['mensaje'] = 'No se logró crear al archivo. Ocurrió un error, inténtelo más tarde.';
		}
		return $resultado;
	}

	private function eliminar_archivo($archivos){
		if(is_array($archivos)){
			foreach ($archivos as $key => $archivo) {
				unlink($archivo); //Eliminar archivo una vez que ha sido comprimido
			}
		} else {
			unlink($archivos); //Eliminar archivo una vez que ha sido comprimido
		}
	}

	private function stream_zip($path){
		header("Content-Type: application/zip");
		header("Content-Lenth: ".filesize($path));
		header("Content-Disposition: attachment; filename=\"font.zip\"");
		readfile($path);

		unlink($path);
		header("Location: http://google.com");
	}

	/*private function confirmar_archivo($path){
		$ext_creados = array('php', 'ttf', 'z');
		$files = scandir($path);
		foreach ($files as $key => $file) {
			if(!in_array(pathinfo($file, PATHINFO_EXTENSION), $ext_creados)){

			}
			echo pathinfo($file, PATHINFO_FILENAME);
			echo "*";
			echo ;
			echo "-<br><br>";
		}
		pr($files);
	}*/
}