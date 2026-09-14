<?php
defined('BASEPATH') OR exit('No direct script access allowed');

require_once APPPATH . 'core/MY_Controller.php';

class Mesas extends MY_Controller
{
    public function __construct()
    {
        parent::__construct();
        $this->restringir_a(array('cliente', 'mesero', 'admin'));
        $this->load->model('Mesa_model');
        $this->load->library('form_validation');
    }

    public function index()
    {
        $data['titulo'] = 'Mesas';
        $data['mesas'] = $this->Mesa_model->todas();
        $this->load->view('templates/header', $data);
        $this->load->view('mesas/index', $data);
        $this->load->view('templates/footer');
    }

    public function crear()
    {
        $this->restringir_a(array('admin'));

        $this->form_validation->set_rules('numero', 'Número', 'required|integer');
        $this->form_validation->set_rules('capacidad', 'Capacidad', 'required|integer|greater_than[0]');

        if ($this->form_validation->run()) {
            $this->Mesa_model->crear(array(
                'numero'    => $this->input->post('numero', TRUE),
                'capacidad' => $this->input->post('capacidad', TRUE),
                'estado'    => 'libre',
            ));
            $this->session->set_flashdata('exito', 'Mesa creada.');
        } else {
            $this->session->set_flashdata('error', validation_errors());
        }
        redirect('mesas');
    }

    public function eliminar($id)
    {
        $this->restringir_a(array('admin'));

        $this->Mesa_model->eliminar($id);
        $this->session->set_flashdata('exito', 'Mesa eliminada.');
        redirect('mesas');
    }

    public function cambiar_estado($id)
    {
        if (!in_array($this->session->userdata('rol'), array('mesero', 'admin'), TRUE)) {
            show_error('Los clientes deben ocupar la mesa al confirmar un pedido.', 403, 'Acción no permitida');
            return;
        }

        $mesa = $this->Mesa_model->obtener($id);
        if (!$mesa) {
            show_404();
            return;
        }

        $estado = $this->input->post('estado', TRUE);
        if (!in_array($estado, array('libre', 'ocupada'), TRUE)) {
            $this->session->set_flashdata('error', 'Estado de mesa inválido.');
            redirect('mesas');
            return;
        }

        $this->Mesa_model->cambiar_estado($id, $estado);
        $mensaje = $estado === 'libre' ? 'Mesa desocupada.' : 'Mesa marcada como ocupada.';
        $this->session->set_flashdata('exito', $mensaje);
        redirect('mesas');
    }
}
