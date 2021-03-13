<?php
/**
 * Created by PhpStorm.
 * User: Cachu
 * Date: 18/10/2017
 * Time: 10:27 AM
 */

namespace distribuidor;

use cbizcontrol;

class TablaCliente_Usuario extends cbizcontrol
{
    function create_table(): string
    {
        return <<<sql
CREATE TABLE e11_cbizcontrol.cliente_usuario(
  id_cliente BIGINT,  
  id_usuario BIGINT  
);
sql;

    }

    function insertClienteUsuario($id_cliente, $id_usuario)
    {
        $sql = /** @lang MySQL */
            <<<MySQL
INSERT cliente_usuario(id_cliente, id_usuario) VALUES ('$id_cliente','$id_usuario')
MySQL;
        $this->consulta($sql);
    }

    public function deleteRegistro($id_cliente)
    {
        $sql = /** @lang MySQL */
            <<<MySQL
delete from cliente_usuario where id_cliente=$id_cliente;
MySQL;
        $this->consulta($sql);
    }
}
