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
    static function create_table()
    {
        $sql = <<<sql
create table if not exists cbiz_cliente
(
	id_cbiz_cliente bigint primary key auto_increment comment 'id cbiz cliente',
	id_cliente bigint null comment 'id de cliente',
	id_tipo_cbiz bigint null comment 'id tipo de cbiz',
	id_periodo bigint null comment 'id de periodo',
	numero_sessiones_cbiz_cliente int(10) null,
	token_cbiz_cliente varchar(10) null comment 'token de cbiz cliente',
	fecha_inicial_cbiz_cliente date null comment 'fecha inicial cliente cbiz',
	fecha_prox_pago_cbiz_cliente date null,
	monto_cbiz_cliente decimal(12,2) null comment 'monto cbiz cliente',
	observa_cbiz_cliente varchar(255) null comment 'observacion del cbiz cliente',
	fecha_ultimo_pago_cbiz_cliente date null comment 'fecha ultimo pago cbiz cliente',
	fecha_cbiz_cliente datetime null comment 'fecha cbiz cliente',
	id_usuario bigint null comment 'id de usuario',
	fecha_actualiza_cbiz_cliente timestamp default CURRENT_TIMESTAMP null on update CURRENT_TIMESTAMP comment 'fecha actualiza cbiz cliente',
	estatus_cbiz_cliente smallint(1) default 1 null comment 'estatus cbiz cliente'
);

create index FK_cbiz_cliente_id_cliente
	on cbiz_cliente (id_cliente);

create index FK_cbiz_cliente_id_tipo_cbiz
	on cbiz_cliente (id_tipo_cbiz);

create index FK_cbiz_cliente_id_usuario
	on cbiz_cliente (id_usuario);

create index Fk_cbiz_cliente_id_periodo
	on cbiz_cliente (id_periodo);

create unique index cbiz_cliente_token_cbiz_cliente_uindex
	on cbiz_cliente (token_cbiz_cliente);
sql;

        return $sql;
    }

    function insertarCbizCliente($id_cliente, $token_cbiz_cliente, $fecha_inicial_cbiz_cliente, $fecha_prox_pago_cbiz_cliente, $monto_cbiz_cliente, $fecha_ultimo_pago_cbiz_cliente, $fecha_cbiz_cliente, $id_usuario)
    {
        $sql = /** @lang MySQL */
            <<<MySQL
INSERT INTO cbiz_cliente (id_cliente, id_tipo_cbiz ,token_cbiz_cliente, fecha_inicial_cbiz_cliente, fecha_prox_pago_cbiz_cliente, monto_cbiz_cliente, fecha_ultimo_pago_cbiz_cliente, fecha_cbiz_cliente, id_usuario,id_periodo)
VALUES
  ('$id_cliente',2, '$token_cbiz_cliente', '$fecha_inicial_cbiz_cliente', '$fecha_prox_pago_cbiz_cliente',
   '$monto_cbiz_cliente', '$fecha_ultimo_pago_cbiz_cliente', '$fecha_cbiz_cliente', '$id_usuario',1);
MySQL;
        $this->consulta($sql);
    }

    function selectTokenExistente($token_cbiz_cliente)
    {
        $sql = /** @lang MySQL */
            <<<MySQL
SELECT count(1) existe FROM cbiz_cliente WHERE token_cbiz_cliente='$token_cbiz_cliente'
MySQL;
        return $this->siguiente_registro($this->consulta($sql))->existe;
    }

    function selectTokenFromUser($correo_contacto_cliente)
    {
        $sql = /** @lang MySQL */
            <<<MySQL
SELECT token_cbiz_cliente token
FROM cbiz_cliente cbc
  INNER JOIN contacto_cliente cc ON cbc.id_cliente = cc.id_cliente
  LEFT JOIN cliente_usuario cu ON cu.id_cliente = cbc.id_cliente
  LEFT JOIN `_usuarios` u ON u.id_usuario = cu.id_usuario
WHERE correo_contacto_cliente = '$correo_contacto_cliente' OR login_usuario = '$correo_contacto_cliente'
MySQL;
        return $this->siguiente_registro($this->consulta($sql))->token;
    }

    function selectTokenFromCliente($id_cliente)
    {
        $sql = <<<MySQL
select token_cbiz_cliente tokenCbiz from cbiz_cliente where id_cliente='$id_cliente';
MySQL;
        $registro = $this->siguiente_registro($this->consulta($sql));
        return $registro->tokenCbiz;
    }

    function selectIdClienteFromToken($token_cbiz_cliente)
    {
        $sql = <<<MySQL
select id_cliente idCliente from cbiz_cliente where token_cbiz_cliente='$token_cbiz_cliente';
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
update cbiz_cliente set estatus_cbiz_cliente='$estatus_cbiz_cliente' where token_cbiz_cliente='$token_cbiz_cliente';
MySQL;
        $this->consulta($sql);
    }

    function selectFechaCreacion($id_cliente)
    {
        $sql = <<<MySQL
select fecha_inicial_cbiz_cliente fechaCreacion from cbiz_cliente where id_cliente='$id_cliente'
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

        return $this->siguiente_registro($this->consulta($sql, ['s', $token_cbiz_cliente]))->tipo;
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

        return $this->siguiente_registro($this->consulta($sql, ['s', $token_cbiz_cliente]))->estatus;
    }
}