<?php
/**
 * Created by PhpStorm.
 * User: Cachu
 * Date: 11/abr/2017
 * Time: 01:19 PM
 */

namespace distribuidor;

use cbizcontrol;

class TablaCbiz_Cliente extends cbizcontrol
{
    function insertarCbizCliente($id_cliente, $token_cbiz_cliente, $fecha_inicial_cbiz_cliente, $fecha_prox_pago_cbiz_cliente, $monto_cbiz_cliente, $fecha_ultimo_pago_cbiz_cliente, $fecha_cbiz_cliente, $id_usuario, $id_tipo_cbiz)
    {
        $sql = /** @lang MySQL */
            <<<MySQL
INSERT INTO e11_cbizcontrol.cbiz_cliente (id_cliente, id_tipo_cbiz ,token_cbiz_cliente, fecha_inicial_cbiz_cliente, fecha_prox_pago_cbiz_cliente, monto_cbiz_cliente, fecha_ultimo_pago_cbiz_cliente, fecha_cbiz_cliente, id_usuario,id_periodo)
VALUES
  ('$id_cliente','$id_tipo_cbiz', '$token_cbiz_cliente', '$fecha_inicial_cbiz_cliente', '$fecha_prox_pago_cbiz_cliente',
   '$monto_cbiz_cliente', '$fecha_ultimo_pago_cbiz_cliente', '$fecha_cbiz_cliente', '$id_usuario',1);
MySQL;
        $this->consulta($sql);
    }

    function selectTokenExistente($token_cbiz_cliente)
    {
        $sql = /** @lang MySQL */
            <<<MySQL
SELECT count(1) existe FROM e11_cbizcontrol.cbiz_cliente WHERE token_cbiz_cliente='$token_cbiz_cliente'
MySQL;
        return $this->siguiente_registro($this->consulta($sql))->existe;
    }

    function selectTokenFromUser($correo_contacto_cliente)
    {
        $sql = /** @lang MySQL */
            <<<MySQL
SELECT token_cbiz_cliente token
FROM e11_cbizcontrol.cbiz_cliente cbc
  INNER JOIN e11_cbizcontrol.contacto_cliente cc ON cbc.id_cliente = cc.id_cliente
  LEFT JOIN cliente_usuario cu ON cu.id_cliente = cbc.id_cliente
  LEFT JOIN `_usuarios` u ON u.id_usuario = cu.id_usuario
WHERE correo_contacto_cliente = '$correo_contacto_cliente' OR login_usuario = '$correo_contacto_cliente'
MySQL;
        return $this->siguiente_registro($this->consulta($sql))->token;
    }

    function selectTokenFromCliente($id_cliente)
    {
        $sql = <<<MySQL
select token_cbiz_cliente tokenCbiz from e11_cbizcontrol.cbiz_cliente where id_cliente='$id_cliente';
MySQL;
        $registro = $this->siguiente_registro($this->consulta($sql));
        return $registro->tokenCbiz;
    }

    function selectIdClienteFromToken($token_cbiz_cliente)
    {
        $sql = <<<MySQL
select id_cliente idCliente from e11_cbizcontrol.cbiz_cliente where token_cbiz_cliente='$token_cbiz_cliente';
MySQL;
        $registro = $this->siguiente_registro($this->consulta($sql));
        return $registro->idCliente;
    }

    /**
     * @param string $token_cbiz_cliente
     * @param bool $estatus_cbiz_cliente
     */
    function updateEstatusCbiz($token_cbiz_cliente, $estatus_cbiz_cliente)
    {
        $estatus_cbiz_cliente = $estatus_cbiz_cliente ? 1 : 0;
        $sql = <<<MySQL
update e11_cbizcontrol.cbiz_cliente set estatus_cbiz_cliente='$estatus_cbiz_cliente' where token_cbiz_cliente='$token_cbiz_cliente';
MySQL;
        $this->consulta($sql);
    }

    function selectFechaCreacion($id_cliente)
    {
        $sql = <<<MySQL
select fecha_inicial_cbiz_cliente fechaCreacion from e11_cbizcontrol.cbiz_cliente where id_cliente='$id_cliente'
MySQL;
        $registro = $this->siguiente_registro($this->consulta($sql));
        return $registro->fechaCreacion;
    }

    function selectTipoSistema($token_cbiz_cliente)
    {
        $sql = <<<sql
select 
 nombre_tipo_cbiz tipo
 from cbiz_cliente 
 inner join tipo_cbiz tc on cbiz_cliente.id_tipo_cbiz = tc.id_tipo_cbiz
 where token_cbiz_cliente=?;
sql;

        return $this->siguiente_registro($this->consulta($sql,['s',$token_cbiz_cliente]))->tipo;
    }

    function selectEstatusSistema($token_cbiz_cliente)
    {
        $sql = <<<sql
select 
 estatus_cbiz_cliente estatus
 from cbiz_cliente 
 inner join tipo_cbiz tc on cbiz_cliente.id_tipo_cbiz = tc.id_tipo_cbiz
 where token_cbiz_cliente=?;
sql;

        return $this->siguiente_registro($this->consulta($sql,['s',$token_cbiz_cliente]))->estatus;
    }
}