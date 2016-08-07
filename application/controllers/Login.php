<?php
defined('BASEPATH') OR exit('No direct script access allowed');

/**
 * Clase que gestiona el login
 * @version 	: 1.0.0
 * @autor 		: Jesús Díaz P. & Pablo José
 */

class Login extends CI_Controller {
    /**
     * * Carga de clases para el acceso a base de datos y para la creación de elementos del formulario
     * * @access 		: public
     * * @modified 	: 
     */
    public function __construct() {
        parent::__construct();        
        
        $this->load->database();
        $this->load->helper(array('form','captcha'));
        $this->load->library('form_complete');
        $this->load->library('form_validation');
        $this->load->library('Bitacora');
        $this->load->model('Login_model', 'lm');
    }
    
    public function index(){
        $this->config->load('form_validation');
        $error = "";
        if($this->input->post()){
            $token_html = $this->input->post('token', true); //Obtenemos token oculto en html y se aplica filtro XSS
            $token_session = $this->session->userdata('token'); //Obtenemos token almacenado en sesión

            $validations = $this->config->item('inicio_sesion'); //Obtener validaciones almacenadas en archivo
            $this->form_validation->set_rules($validations);
            
            if(($this->form_validation->run()==true) && ($token_html==$token_session)){ //Aplicamos validaciones a la matrícula, contraseña, captcha; además se verifica que el token obtenido por el formulario sea el mismo que el que se encuentra en sesión
                $matricula = $this->input->post('matricula', true);
                $passwd = $this->input->post('passwd', true);
                
                //if($this->input->post('btn_login', true) == "Login"){
                    $mat = $this->matricula_formato($matricula); //Encriptar matricula
                    $pass = $this->contrasenia_formato($matricula, $passwd); ///Encriptar contraseña

                    $check_user = $this->lm->check_Usuario($mat); ///Verificar contra base de datos

                    if($check_user['result'] == TRUE){ ///Usuario existe en base de datos
                        if(!$this->checkbrute($matricula)){ //Verificamos que no exista ataque de fuerza bruta
                            if($check_user['data']->usr_passwd==$pass){
                                $datosSession = array(
                                    'usuario_logeado'=>TRUE,
                                    'matricula' => $check_user['data']->usr_matricula,
                                    'nombre' => $check_user['data']->usr_nombre,
                                    'apaterno' => $check_user['data']->usr_paterno,
                                    'amaterno' => $check_user['data']->usr_materno,
                                    'activo' => $check_user['data']->usr_activo
                                );
                                
                                $this->session->set_userdata($datosSession); ///Si es correcto iniciamos sesión
                                $this->session->unset_userdata('token'); //Eliminar token
                                
                                $this->bitacora->bitacora_login_insertar($check_user['data']->usr_matricula); //Registrar inicio de sesión correcto
                                redirect('dashboard');
                                exit();
                            } else { ///Insertar intento fallido
                                $this->lm->intento_fallido($matricula);
                                $error = "Datos incorrectos .";
                            }
                        } else { ///Cuenta bloqueda por el periodo de tiempo especificado en método checkbrute
                            $error = "Datos incorrectos.";
                        }
                    } else {
                        $error = "No se encontró registro del usuario.";
                    }
                
            } else {
                $this->session->unset_userdata('token'); //Eliminar token
            }
        }
        $this->token(); //Crear un token cada vez que se ingresa al formulario de inicio sesión
        $datos['main_content']= $this->formulario($error);
        $this->template->template_conricyt($datos);
    }

    private function checkbrute($matricula) {
        $ahora = time(); ///Tiempo actual
     
        $lapso_intentos = $this->config->item('tiempo_fuerza_bruta');
        $intentos_default_fuerza_bruta = $this->config->item('intentos_fuerza_bruta');
        
        $numero_intentos_usuario = $this->lm->check_brute_attempts($matricula, $lapso_intentos);
        if($numero_intentos_usuario > $intentos_default_fuerza_bruta){
            return true;
        } else {
            return false;
        }
    }
    
    private function matricula_formato($matricula){
        return hash('sha512', $matricula);
    }

    private function contrasenia_formato($matricula, $contrasenia){
        return hash('sha512', $contrasenia . $matricula);
    }
    
    private function formulario($error=""){
        $vals = array(
           'img_path'      => './captcha/',
           'img_url'       =>  base_url().'captcha/',
           'img_width'     => 220,
           'img_height'    => 75,
           'word_length'   => 5,
           'font_size'     => array(32, true),
           'pool'          => '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ',
           'colors'        => array(
               'background' => array(255, 255, 255),
               'border' => array(255, 255, 255),
               'text' => array(0, 0, 0),
               'grid' => array(101, 158, 76)
            )
        );
        $data['error'] = $error;
        $data['captcha'] = create_captcha($vals);
        $this->session->set_userdata('captchaWord', $data['captcha']['word']);

        //echo $data['token'] = $this->session->userdata('token'); //Se envia token al formulario
        
        $form_login = $this->load->view('login/formulario', $data, TRUE);
        return $form_login;
    }
        
    public function check_captcha($str){
        $word = $this->session->userdata('captchaWord');
        if(strcmp(strtoupper($str), strtoupper($word))==0){
            return true;
        } else {
            $this->form_validation->set_message('check_captcha','Por favor introduce correctamente los caracteres');
            return false;
        }
    }
          
    private function token(){
        $token = md5(uniqid(rand(),TRUE));
        $this->session->set_userdata('token',$token);
        return;
    }
        
    public function cerrar_session(){
        //session_destroy();
        $this->session->sess_destroy();
        redirect('login');
        exit();
    }

    public function cerrar_session_ajax(){
        echo "<script>alert('Sesión finalizada'); document.location.href='".site_url("login")."';</script>";
    }
}