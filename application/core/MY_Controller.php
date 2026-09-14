<?php
defined('BASEPATH') OR exit('No direct script access allowed');

/**
 * FoodFlow - Controlador base
 * Todos los controladores protegidos extienden de aquí.
 * Verifica sesión activa y, opcionalmente, restringe por rol.
 */
class MY_Controller extends CI_Controller
{
    protected $usuario_id;
    protected $usuario_nombre;
    protected $usuario_rol;

    public function __construct()
    {
        parent::__construct();
        $this->load->library('session');

        if (!$this->session->userdata('logueado')) {
            redirect('login');
            return;
        }

        $this->usuario_id     = $this->session->userdata('id');
        $this->usuario_nombre = $this->session->userdata('nombre');
        $this->usuario_rol    = $this->session->userdata('rol');
    }

    /**
     * Restringe el acceso a un controlador/método solo a ciertos roles.
     * Uso: $this->restringir_a(array('admin'));
     */
    protected function restringir_a($roles_permitidos)
    {
        if (!in_array($this->usuario_rol, $roles_permitidos)) {
            show_error('No tienes permiso para acceder a esta sección.', 403, 'Acceso denegado');
        }
    }
}
