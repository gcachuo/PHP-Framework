<?php
/**
 * Created by PhpStorm.
 * User: Cachu
 * Date: 19/abr/2017
 * Time: 11:58 AM
 */

namespace distribuidor;


class TablaContacto_Cliente extends \cbizcontrol
{
    function selectIdClienteFromCorreo($correo_contacto_cliente)
    {
        $sql = <<<MySQL
select 
  id_cliente idCliente
from contacto_cliente
where correo_contacto_cliente = '$correo_contacto_cliente'
MySQL;
        $registro = $this->siguiente_registro($this->consulta($sql));
        $idCliente = $registro->idCliente;
        return $idCliente;
    }

    function selectCountCorreo($correo_contacto_cliente)
    {
        $sql = /** @lang MySQL */
            <<<MySQL
SELECT count(1) count
FROM contacto_cliente
WHERE correo_contacto_cliente = '$correo_contacto_cliente'
MySQL;
        return $this->siguiente_registro($this->consulta($sql))->count;
    }


    function insertContactoCliente($id_cliente, $nombre_contacto_cliente, $apellidoP_contacto_cliente, $apellidoM_contacto_cliente, $telefono_contacto_cliente, $correo_contacto_cliente)
    {
        $fecha_contacto_cliente = date('Y-m-d');
        $sql = /** @lang MySQL */
            <<<MySQL
INSERT INTO contacto_cliente (id_tipo_contacto, id_cliente, nombre_contacto_cliente, apellidoP_contacto_cliente, apellidoM_contacto_cliente, telefono_contacto_cliente, correo_contacto_cliente, puesto_contacto_cliente, fecha_contacto_cliente, id_usuario, fecha_actualiza_contacto_cliente, estatus_contacto_cliente) VALUES (1, '$id_cliente', '$nombre_contacto_cliente', '$apellidoP_contacto_cliente', '$apellidoM_contacto_cliente', '$telefono_contacto_cliente', '$correo_contacto_cliente', '', '$fecha_contacto_cliente', 1, 'DEFAULT', DEFAULT);
MySQL;
        $this->consulta($sql);
    }
}