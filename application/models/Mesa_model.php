<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Mesa_model extends CI_Model
{
    protected $tabla = 'mesas';

    public function todas()
    {
        return $this->db->order_by('numero', 'ASC')->get($this->tabla)->result();
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

    public function cambiar_estado($id, $estado)
    {
        return $this->db->update($this->tabla, array('estado' => $estado), array('id' => $id));
    }
}
