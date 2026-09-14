<?php
defined('BASEPATH') OR exit('No direct script access allowed');

require_once APPPATH . 'core/MY_Controller.php';

class Menu extends MY_Controller
{
    public function __construct()
    {
        parent::__construct();
        $this->restringir_a(array('admin'));
        $this->load->model('Menu_model');
        $this->load->library('form_validation');
    }

    public function index()
    {
        $data['titulo'] = 'Gestión del Menú';
        $data['items'] = $this->Menu_model->todos_con_categoria();
        $this->load->view('templates/header', $data);
        $this->load->view('menu/index', $data);
        $this->load->view('templates/footer');
    }

    public function nuevo()
    {
        $data['titulo'] = 'Nuevo producto';
        $data['categorias'] = $this->Menu_model->categorias();
        $data['item'] = null;

        $this->form_validation->set_rules('nombre', 'Nombre', 'required|trim|max_length[100]');
        $this->form_validation->set_rules('precio', 'Precio', 'required|numeric|greater_than[0]');
        $this->form_validation->set_rules('categoria_id', 'Categoría', 'required|integer');

        if ($this->form_validation->run() === FALSE) {
            $this->load->view('templates/header', $data);
            $this->load->view('menu/form', $data);
            $this->load->view('templates/footer');
            return;
        }

        $this->Menu_model->crear(array(
            'nombre'       => $this->input->post('nombre', TRUE),
            'descripcion'  => $this->input->post('descripcion', TRUE),
            'precio'       => $this->input->post('precio', TRUE),
            'categoria_id' => $this->input->post('categoria_id', TRUE),
            'disponible'   => $this->input->post('disponible') ? 1 : 0,
        ));

        $this->session->set_flashdata('exito', 'Producto agregado correctamente.');
        redirect('menu');
    }

    public function editar($id)
    {
        $item = $this->Menu_model->obtener($id);
        if (!$item) {
            show_404();
            return;
        }

        $data['titulo'] = 'Editar producto';
        $data['categorias'] = $this->Menu_model->categorias();
        $data['item'] = $item;

        $this->form_validation->set_rules('nombre', 'Nombre', 'required|trim|max_length[100]');
        $this->form_validation->set_rules('precio', 'Precio', 'required|numeric|greater_than[0]');
        $this->form_validation->set_rules('categoria_id', 'Categoría', 'required|integer');

        if ($this->form_validation->run() === FALSE) {
            $this->load->view('templates/header', $data);
            $this->load->view('menu/form', $data);
            $this->load->view('templates/footer');
            return;
        }

        $this->Menu_model->actualizar($id, array(
            'nombre'       => $this->input->post('nombre', TRUE),
            'descripcion'  => $this->input->post('descripcion', TRUE),
            'precio'       => $this->input->post('precio', TRUE),
            'categoria_id' => $this->input->post('categoria_id', TRUE),
            'disponible'   => $this->input->post('disponible') ? 1 : 0,
        ));

        $this->session->set_flashdata('exito', 'Producto actualizado.');
        redirect('menu');
    }

    public function eliminar($id)
    {
        $this->Menu_model->eliminar($id);
        $this->session->set_flashdata('exito', 'Producto eliminado.');
        redirect('menu');
    }
}
