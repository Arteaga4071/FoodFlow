<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Menu_model extends CI_Model
{
    protected $tabla = 'menu_items';

    public function todos_con_categoria()
    {
        return $this->db->select('menu_items.*, categorias.nombre as categoria_nombre')
            ->from('menu_items')
            ->join('categorias', 'categorias.id = menu_items.categoria_id')
            ->order_by('categorias.id', 'ASC')
            ->order_by('menu_items.nombre', 'ASC')
            ->get()->result();
    }

    public function disponibles_agrupados()
    {
        $items = $this->db->select('menu_items.*, categorias.nombre as categoria_nombre')
            ->from('menu_items')
            ->join('categorias', 'categorias.id = menu_items.categoria_id')
            ->where('menu_items.disponible', 1)
            ->order_by('categorias.id', 'ASC')
            ->order_by('menu_items.nombre', 'ASC')
            ->get()->result();

        $agrupado = array();
        foreach ($items as $item) {
            $agrupado[$item->categoria_nombre][] = $item;
        }
        return $agrupado;
    }

    public function obtener($id)
    {
        return $this->db->get_where($this->tabla, array('id' => $id))->row();
    }

    public function crear($data)
    {
        return $this->db->insert($this->tabla, $data);
    }

    public function actualizar($id, $data)
    {
        return $this->db->update($this->tabla, $data, array('id' => $id));
    }

    public function eliminar($id)
    {
        return $this->db->delete($this->tabla, array('id' => $id));
    }

    public function categorias()
    {
        return $this->db->order_by('id', 'ASC')->get('categorias')->result();
    }

    public function mas_vendidos($limite = 5)
    {
        return $this->db->select('menu_items.nombre, SUM(pedido_detalles.cantidad) as total_vendido')
            ->from('pedido_detalles')
            ->join('menu_items', 'menu_items.id = pedido_detalles.menu_item_id')
            ->group_by('menu_items.id')
            ->order_by('total_vendido', 'DESC')
            ->limit($limite)
            ->get()->result();
    }
}
