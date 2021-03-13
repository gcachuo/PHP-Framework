<?php
/**
 * Created by PhpStorm.
 * User: Cachu
 * Date: 19/abr/2017
 * Time: 11:58 AM
 */

namespace distribuidor;


use cbizcontrol;

class TablaContacto_Cliente extends cbizcontrol
{
    function create_table(): string
    {
        return <<<sql
CREATE TABLE e11_cbizcontrol.contacto_cliente(
    id_contacto_cliente BIGINT PRIMARY KEY AUTO_INCREMENT,
    id_cliente BIGINT,
    id_tipo_contacto BIGINT,
    id_usuario BIGINT,
    nombre_contacto_cliente VARCHAR(100),
    apellidoP_contacto_cliente VARCHAR(100),
    apellidoM_contacto_cliente VARCHAR(100),
    correo_contacto_cliente VARCHAR(100),
    telefono_contacto_cliente VARCHAR(100),
    puesto_contacto_cliente VARCHAR(100),
    fecha_contacto_cliente DATE,
    fecha_actualiza_contacto_cliente DATE,
    estatus_contacto_cliente INT
)
sql;

    }

    function selectIdClienteFromCorreo($correo_contacto_cliente)
    {
        $sql = <<<MySQL
select 
  id_cliente idCliente
from e11_cbizcontrol.contacto_cliente
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
FROM e11_cbizcontrol.contacto_cliente
WHERE correo_contacto_cliente = '$correo_contacto_cliente'
MySQL;
        return $this->siguiente_registro($this->consulta($sql))->count;
    }


    function insertContactoCliente($id_cliente, $nombre_contacto_cliente, $apellidoP_contacto_cliente, $apellidoM_contacto_cliente, $telefono_contacto_cliente, $correo_contacto_cliente)
    {
        $fecha_contacto_cliente = date('Y-m-d');
        $sql = /** @lang MySQL */
            <<<MySQL
INSERT INTO e11_cbizcontrol.contacto_cliente (id_tipo_contacto, id_cliente, nombre_contacto_cliente, apellidoP_contacto_cliente, apellidoM_contacto_cliente, telefono_contacto_cliente, correo_contacto_cliente, puesto_contacto_cliente, fecha_contacto_cliente, id_usuario, fecha_actualiza_contacto_cliente, estatus_contacto_cliente) VALUES (1, '$id_cliente', '$nombre_contacto_cliente', '$apellidoP_contacto_cliente', '$apellidoM_contacto_cliente', '$telefono_contacto_cliente', '$correo_contacto_cliente', '', '$fecha_contacto_cliente', 1, 'DEFAULT', DEFAULT);
MySQL;
        $this->consulta($sql);
    }
}
