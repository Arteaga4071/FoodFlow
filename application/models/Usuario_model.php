<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Usuario_model extends CI_Model
{
    protected $tabla = 'usuarios';

    public function obtener_por_usuario($usuario)
    {
        return $this->db->get_where($this->tabla, array('usuario' => $usuario, 'activo' => 1))->row();
    }

    public function obtener($id)
    {
        return $this->db->get_where($this->tabla, array('id' => $id))->row();
    }

    public function todos()
    {
        return $this->db->order_by('nombre', 'ASC')->get($this->tabla)->result();
    }
}
