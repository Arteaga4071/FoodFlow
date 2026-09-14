<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Auth extends CI_Controller
{
    public function __construct()
    {
        parent::__construct();
        $this->load->library(array('session', 'form_validation'));
        $this->load->model('Usuario_model');
        $this->load->helper(array('url', 'form'));
    }

    public function index()
    {
        // Si ya está logueado, manda a su panel
        if ($this->session->userdata('logueado')) {
            redirect($this->_ruta_por_rol($this->session->userdata('rol')));
            return;
        }

        $data['titulo'] = 'Iniciar sesión - FoodFlow';

        $this->form_validation->set_rules('usuario', 'Usuario', 'required|trim');
        $this->form_validation->set_rules('password', 'Contraseña', 'required|trim');

        if ($this->form_validation->run() === FALSE) {
            $this->load->view('auth/login', $data);
            return;
        }

        $usuario = $this->input->post('usuario', TRUE);
        $password = $this->input->post('password', TRUE);

        $fila = $this->Usuario_model->obtener_por_usuario($usuario);

        if (!$fila || !password_verify($password, $fila->password)) {
            $data['error'] = 'Usuario o contraseña incorrectos.';
            $this->load->view('auth/login', $data);
            return;
        }

        $this->session->set_userdata(array(
            'logueado' => TRUE,
            'id'       => $fila->id,
            'nombre'   => $fila->nombre,
            'rol'      => $fila->rol,
        ));

        redirect($this->_ruta_por_rol($fila->rol));
    }

    public function logout()
    {
        $this->session->sess_destroy();
        redirect('login');
    }

    private function _ruta_por_rol($rol)
    {
        switch ($rol) {
            case 'admin':
                return 'reportes';
            case 'cocina':
                return 'cocina';
            case 'cliente':
                return 'mesas';
            case 'mesero':
            default:
                return 'mesas';
        }
    }
}
