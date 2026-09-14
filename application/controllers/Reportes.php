<?php
defined('BASEPATH') OR exit('No direct script access allowed');

require_once APPPATH . 'core/MY_Controller.php';

class Reportes extends MY_Controller
{
    public function __construct()
    {
        parent::__construct();
        $this->restringir_a(array('admin'));
        $this->load->model(array('Pedido_model', 'Menu_model'));
    }

    public function index()
    {
        $desde = $this->input->get('desde') ?: date('Y-m-d', strtotime('-7 days'));
        $hasta = $this->input->get('hasta') ?: date('Y-m-d');

        $data['titulo'] = 'Reportes';
        $data['desde'] = $desde;
        $data['hasta'] = $hasta;
        $data['ventas_por_dia'] = $this->Pedido_model->ventas_por_periodo($desde, $hasta);
        $data['ingresos_totales'] = $this->Pedido_model->ingresos_totales($desde, $hasta);
        $data['mas_vendidos'] = $this->Menu_model->mas_vendidos(5);

        $this->load->view('templates/header', $data);
        $this->load->view('reportes/index', $data);
        $this->load->view('templates/footer');
    }
}
