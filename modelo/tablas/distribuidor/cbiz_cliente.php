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
    function create_table(): string
    {
        return <<<sql
CREATE TABLE e11_cbizcontrol.cbiz_cliente(
    id_cbiz_cliente BIGINT(20) PRIMARY KEY NOT NULL AUTO_INCREMENT,
    id_cliente BIGINT(20),
    id_tipo_cbiz BIGINT(20),
    id_periodo BIGINT(20),
    id_usuario BIGINT(20),
    token_cbiz_cliente VARCHAR(100),
    fecha_inicial_cbiz_cliente DATE,
    fecha_prox_pago_cbiz_cliente DATE,
    monto_cbiz_cliente DECIMAL(12,2),
    fecha_ultimo_pago_cbiz_cliente DATE,
    fecha_cbiz_cliente DATE,
    estatus_cbiz_cliente INT
)
sql;

    }

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
  LEFT JOIN e11_cbizcontrol.cliente_usuario cu ON cu.id_cliente = cbc.id_cliente
  LEFT JOIN e11_cbizcontrol.`_usuarios` u ON u.id_usuario = cu.id_usuario
WHERE correo_contacto_cliente = '$correo_contacto_cliente' OR login_usuario = '$correo_contacto_cliente'
MySQL;
        $registro = $this->siguiente_registro($this->consulta($sql)) ?: (object)['token' => ''];
        return $registro->token;
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
SELECT 
 nombre_tipo_cbiz tipo
 FROM e11_cbizcontrol.cbiz_cliente 
 INNER JOIN e11_cbizcontrol.tipo_cbiz tc ON cbiz_cliente.id_tipo_cbiz = tc.id_tipo_cbiz
 WHERE token_cbiz_cliente=?;
sql;

        $registro = $this->siguiente_registro($this->consulta($sql, ['s', $token_cbiz_cliente]));
        return $registro->tipo;
    }

    function selectEstatusSistema($token_cbiz_cliente)
    {
        $sql = <<<sql
SELECT 
 estatus_cbiz_cliente estatus
 FROM e11_cbizcontrol.cbiz_cliente 
 INNER JOIN e11_cbizcontrol.tipo_cbiz tc ON cbiz_cliente.id_tipo_cbiz = tc.id_tipo_cbiz
 WHERE token_cbiz_cliente=?;
sql;

        $registro = $this->siguiente_registro($this->consulta($sql, ['s', $token_cbiz_cliente])) ?: (object)['estatus' => null];
        return $registro->estatus;
    }
}
