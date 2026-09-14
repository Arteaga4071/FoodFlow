<?php
defined('BASEPATH') OR exit('No direct script access allowed');

require_once APPPATH . 'core/MY_Controller.php';

class Pedidos extends MY_Controller
{
    public function __construct()
    {
        parent::__construct();
        $this->restringir_a(array('cliente', 'mesero', 'admin'));
        $this->load->model(array('Pedido_model', 'Menu_model', 'Mesa_model'));
    }

    // Panel con pedidos activos (todas las mesas ocupadas) 
    public function index()
    {
        $this->restringir_a(array('cliente', 'mesero', 'admin'));

        $es_cliente = $this->session->userdata('rol') === 'cliente';
        $data['titulo'] = $es_cliente ? 'Mis pedidos' : 'Pedidos activos';
        $data['pedidos'] = $this->Pedido_model->activos($es_cliente ? $this->usuario_id : NULL);
        $this->load->view('templates/header', $data);
        $this->load->view('pedidos/index', $data);
        $this->load->view('templates/footer');
    }

    // Formulario para tomar/editar el pedido de una mesa 
    public function nuevo($mesa_id)
    {
        $mesa = $this->Mesa_model->obtener($mesa_id);
        if (!$mesa) {
            show_404();
            return;
        }

        if ($this->session->userdata('rol') === 'cliente' && $mesa->estado === 'ocupada') {
            $this->session->set_flashdata('error', 'Esa mesa ya está ocupada. Elige una mesa libre.');
            redirect('mesas');
            return;
        }

        $pedido_id = $this->Pedido_model->obtener_o_crear_pedido_abierto(
            $mesa_id,
            $this->usuario_id,
            $this->session->userdata('rol')
        );

        $data['titulo'] = 'Pedido - Mesa ' . $mesa->numero;
        $data['mesa'] = $mesa;
        $data['pedido'] = $this->Pedido_model->obtener($pedido_id);
        $data['detalles'] = $this->Pedido_model->obtener_detalles($pedido_id);
        $data['menu_agrupado'] = $this->Menu_model->disponibles_agrupados();

        $this->load->view('templates/header', $data);
        $this->load->view('pedidos/nuevo', $data);
        $this->load->view('templates/footer');
    }

    // AJAX: agrega un producto al pedido 
    public function agregar_item()
    {
        $pedido_id = $this->input->post('pedido_id');
        $menu_item_id = $this->input->post('menu_item_id');
        $cantidad = (int) $this->input->post('cantidad');
		$pedido = $this->Pedido_model->obtener($pedido_id);

        if (!$pedido_id || !$menu_item_id || $cantidad < 1 || !$this->_puede_editar_pedido($pedido)) {
            echo json_encode(array('ok' => FALSE, 'msg' => 'Datos inválidos.'));
            return;
        }

        $producto = $this->Menu_model->obtener($menu_item_id);
        if (!$producto) {
            echo json_encode(array('ok' => FALSE, 'msg' => 'Producto no encontrado.'));
            return;
        }

        $this->Pedido_model->agregar_detalle($pedido_id, $menu_item_id, $cantidad, $producto->precio);
        $pedido = $this->Pedido_model->obtener($pedido_id);

        echo json_encode(array(
            'ok' => TRUE,
            'total' => number_format($pedido->total, 0, ',', '.'),
            'detalles' => $this->Pedido_model->obtener_detalles($pedido_id),
            'csrf_hash' => $this->security->get_csrf_hash(),
        ));
    }

    // AJAX: quita un item del pedido 
    public function quitar_item()
    {
        $detalle_id = $this->input->post('detalle_id');
        $pedido_id = $this->input->post('pedido_id');
        $pedido = $this->Pedido_model->obtener($pedido_id);

        if (!$this->_puede_editar_pedido($pedido)) {
            echo json_encode(array('ok' => FALSE, 'msg' => 'No puedes modificar este pedido.'));
            return;
        }

        $this->Pedido_model->quitar_detalle($detalle_id);
        $pedido = $this->Pedido_model->obtener($pedido_id);

        echo json_encode(array(
            'ok' => TRUE,
            'total' => number_format($pedido->total, 0, ',', '.'),
            'detalles' => $this->Pedido_model->obtener_detalles($pedido_id),
            'csrf_hash' => $this->security->get_csrf_hash(),
        ));
    }

    // Confirma el pedido (pasa de "armando" a enviado a cocina)
    public function confirmar($pedido_id)
    {
        if (strtoupper($_SERVER['REQUEST_METHOD']) !== 'POST') {
            $this->session->set_flashdata('error', 'Confirma el pedido usando el botón del resumen.');
            redirect('mesas');
            return;
        }

        $pedido = $this->Pedido_model->obtener($pedido_id);
        $detalles = $this->Pedido_model->obtener_detalles($pedido_id);

        if (!$this->_puede_editar_pedido($pedido) || empty($detalles) || (float) $pedido->total <= 0) {
            $this->session->set_flashdata('error', 'Debes seleccionar al menos un producto antes de confirmar el pedido.');
            redirect('mesas');
            return;
        }

        $this->Pedido_model->cambiar_estado($pedido_id, 'pendiente');
        $this->Mesa_model->cambiar_estado($pedido->mesa_id, 'ocupada');
        $this->session->set_flashdata('exito', 'Pedido enviado a cocina.');
        redirect('mesas');
    }

    // Marca el pedido como pagado y libera la mesa 
    public function cerrar($pedido_id)
    {
        $this->restringir_a(array('mesero', 'admin'));

        $pedido = $this->Pedido_model->obtener($pedido_id);
        if (!$pedido) {
            show_404();
            return;
        }

        $this->Pedido_model->cambiar_estado($pedido_id, 'pagado');
        $this->Mesa_model->cambiar_estado($pedido->mesa_id, 'libre');

        $this->session->set_flashdata('exito', 'Pedido cerrado y mesa liberada.');
        redirect('pedidos');
    }

    public function historial()
    {
        $this->restringir_a(array('cliente', 'mesero', 'admin'));

        $fecha = $this->input->get('fecha');
        $mesa = $this->input->get('mesa');
        $es_cliente = $this->session->userdata('rol') === 'cliente';

        $data['titulo'] = $es_cliente ? 'Mi historial de pedidos' : 'Historial de pedidos';
        $data['pedidos'] = $this->Pedido_model->historial($fecha, $mesa, $es_cliente ? $this->usuario_id : NULL);
        $data['fecha'] = $fecha;
        $data['mesa'] = $mesa;

        $this->load->view('templates/header', $data);
        $this->load->view('pedidos/historial', $data);
        $this->load->view('templates/footer');
    }

    public function ver($pedido_id)
    {
        $this->restringir_a(array('cliente', 'mesero', 'admin'));

        $data['titulo'] = 'Detalle de pedido';
        $data['pedido'] = $this->Pedido_model->obtener($pedido_id);

        if (!$data['pedido'] || ($this->session->userdata('rol') === 'cliente'
            && (int) $data['pedido']->cliente_id !== (int) $this->usuario_id)) {
            show_error('No tienes permiso para ver este pedido.', 403, 'Acceso denegado');
            return;
        }

        $data['detalles'] = $this->Pedido_model->obtener_detalles($pedido_id);

        $this->load->view('templates/header', $data);
        $this->load->view('pedidos/ver', $data);
        $this->load->view('templates/footer');
    }

    private function _puede_editar_pedido($pedido)
    {
        if (!$pedido) {
            return FALSE;
        }

        if ($this->session->userdata('rol') === 'cliente') {
            return (int) $pedido->cliente_id === (int) $this->usuario_id
                && $pedido->estado === 'pendiente';
        }

        return TRUE;
    }
}
