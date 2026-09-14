<?php
defined('BASEPATH') OR exit('No direct script access allowed');

require_once APPPATH . 'core/MY_Controller.php';

class Cocina extends MY_Controller
{
    public function __construct()
    {
        parent::__construct();
        $this->restringir_a(array('cocina', 'admin'));
        $this->load->model('Pedido_model');
    }

    public function index()
    {
        $data['titulo'] = 'Cocina';
        $data['pedidos'] = $this->Pedido_model->para_cocina();
        foreach ($data['pedidos'] as $p) {
            $p->detalles = $this->Pedido_model->obtener_detalles($p->id);
        }
        $this->load->view('templates/header', $data);
        $this->load->view('cocina/index', $data);
        $this->load->view('templates/footer');
    }

    // Avanza el estado: pendiente -> en preparacion -> listo -> entregado 
    public function avanzar_estado($pedido_id)
    {
        $pedido = $this->Pedido_model->obtener($pedido_id);
        if (!$pedido) {
            show_404();
            return;
        }

        $siguiente = array(
            'pendiente'      => 'en_preparacion',
            'en_preparacion' => 'listo',
            'listo'          => 'entregado',
        );

        if (isset($siguiente[$pedido->estado])) {
            $this->Pedido_model->cambiar_estado($pedido_id, $siguiente[$pedido->estado]);
        }

        redirect('cocina');
    }
}
