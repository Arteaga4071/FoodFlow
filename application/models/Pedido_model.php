<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Pedido_model extends CI_Model
{
    protected $tabla = 'pedidos';
    protected $tabla_detalle = 'pedido_detalles';

    // Busca un pedido abierto (no pagado) para una mesa, o crea uno nuevo
    public function obtener_o_crear_pedido_abierto($mesa_id, $usuario_id, $rol = 'mesero')
    {
        $columna_usuario = $rol === 'cliente' ? 'cliente_id' : 'mesero_id';
        $pedido = $this->db->where('mesa_id', $mesa_id)
            ->where($columna_usuario, $usuario_id)
            ->where_not_in('estado', array('pagado'))
            ->order_by('id', 'DESC')
            ->limit(1)
            ->get($this->tabla)->row();

        if ($pedido) {
            return $pedido->id;
        }

        $datos = array(
            'mesa_id'   => $mesa_id,
            'estado'    => 'pendiente',
            'total'     => 0,
        );
        $datos[$columna_usuario] = $usuario_id;
        $this->db->insert($this->tabla, $datos);
        return $this->db->insert_id();
    }

    public function agregar_detalle($pedido_id, $menu_item_id, $cantidad, $precio_unitario)
    {
        // Si el ítem ya está en el pedido, suma cantidad
        $existente = $this->db->where('pedido_id', $pedido_id)
            ->where('menu_item_id', $menu_item_id)
            ->get($this->tabla_detalle)->row();

        if ($existente) {
            $nueva_cantidad = $existente->cantidad + $cantidad;
            $this->db->update($this->tabla_detalle, array(
                'cantidad' => $nueva_cantidad,
                'subtotal' => $nueva_cantidad * $precio_unitario,
            ), array('id' => $existente->id));
        } else {
            $this->db->insert($this->tabla_detalle, array(
                'pedido_id'       => $pedido_id,
                'menu_item_id'    => $menu_item_id,
                'cantidad'        => $cantidad,
                'precio_unitario' => $precio_unitario,
                'subtotal'        => $cantidad * $precio_unitario,
            ));
        }

        $this->recalcular_total($pedido_id);
    }

    public function quitar_detalle($detalle_id)
    {
        $detalle = $this->db->get_where($this->tabla_detalle, array('id' => $detalle_id))->row();
        if ($detalle) {
            $this->db->delete($this->tabla_detalle, array('id' => $detalle_id));
            $this->recalcular_total($detalle->pedido_id);
        }
    }

    public function recalcular_total($pedido_id)
    {
        $total = $this->db->select_sum('subtotal')
            ->where('pedido_id', $pedido_id)
            ->get($this->tabla_detalle)->row()->subtotal;

        $this->db->update($this->tabla, array('total' => $total ? $total : 0), array('id' => $pedido_id));
    }

    public function obtener($id)
    {
        return $this->db->select('pedidos.*, mesas.numero as mesa_numero, COALESCE(meseros.nombre, clientes.nombre) as mesero_nombre', FALSE)
            ->from('pedidos')
            ->join('mesas', 'mesas.id = pedidos.mesa_id')
            ->join('usuarios meseros', 'meseros.id = pedidos.mesero_id', 'left')
            ->join('usuarios clientes', 'clientes.id = pedidos.cliente_id', 'left')
            ->where('pedidos.id', $id)
            ->get()->row();
    }

    public function obtener_detalles($pedido_id)
    {
        return $this->db->select('pedido_detalles.*, menu_items.nombre as producto_nombre')
            ->from('pedido_detalles')
            ->join('menu_items', 'menu_items.id = pedido_detalles.menu_item_id')
            ->where('pedido_id', $pedido_id)
            ->get()->result();
    }

    // Pedidos activos (no pagados) para el panel de meseros 
    public function activos($cliente_id = NULL)
    {
        $this->db->select('pedidos.*, mesas.numero as mesa_numero')
            ->from('pedidos')
            ->join('mesas', 'mesas.id = pedidos.mesa_id')
            ->where_not_in('pedidos.estado', array('pagado'));

        if ($cliente_id !== NULL) {
            $this->db->where('pedidos.cliente_id', $cliente_id);
        }

        return $this->db->order_by('pedidos.creado_en', 'DESC')->get()->result();
    }

    // Pedidos pendientes / en preparación para cocina 
    public function para_cocina()
    {
        return $this->db->select('pedidos.*, mesas.numero as mesa_numero')
            ->from('pedidos')
            ->join('mesas', 'mesas.id = pedidos.mesa_id')
            ->where_in('pedidos.estado', array('pendiente', 'en_preparacion', 'listo'))
            ->order_by('pedidos.creado_en', 'ASC')
            ->get()->result();
    }

    public function cambiar_estado($id, $estado)
    {
        return $this->db->update($this->tabla, array('estado' => $estado), array('id' => $id));
    }

    public function historial($fecha = null, $numero_mesa = null, $cliente_id = NULL)
    {
        $this->db->select('pedidos.*, mesas.numero as mesa_numero, COALESCE(usuarios.nombre, clientes.nombre) as mesero_nombre', FALSE)
            ->from('pedidos')
            ->join('mesas', 'mesas.id = pedidos.mesa_id')
            ->join('usuarios', 'usuarios.id = pedidos.mesero_id', 'left')
            ->join('usuarios clientes', 'clientes.id = pedidos.cliente_id', 'left')
            ->where_in('pedidos.estado', array('entregado', 'pagado'));

        if ($cliente_id !== NULL) {
            $this->db->where('pedidos.cliente_id', $cliente_id);
        }

        if (!empty($fecha)) {
            $this->db->where('DATE(pedidos.creado_en)', $fecha);
        }
        if (!empty($numero_mesa)) {
            $this->db->where('mesas.numero', $numero_mesa);
        }

        return $this->db->order_by('pedidos.creado_en', 'DESC')->get()->result();
    }

    // REPORTES

    public function ventas_por_periodo($desde, $hasta)
    {
        return $this->db->select('DATE(creado_en) as dia, SUM(total) as total_dia')
            ->from($this->tabla)
            ->where('estado', 'pagado')
            ->where('creado_en >=', $desde . ' 00:00:00')
            ->where('creado_en <=', $hasta . ' 23:59:59')
            ->group_by('DATE(creado_en)')
            ->order_by('dia', 'ASC')
            ->get()->result();
    }

    public function ingresos_totales($desde, $hasta)
    {
        $row = $this->db->select_sum('total')
            ->where('estado', 'pagado')
            ->where('creado_en >=', $desde . ' 00:00:00')
            ->where('creado_en <=', $hasta . ' 23:59:59')
            ->get($this->tabla)->row();
        return $row->total ? $row->total : 0;
    }
}
