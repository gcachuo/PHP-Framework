<?php
/**
 * Created by PhpStorm.
 * User: Cachu
 * Date: 18/10/2017
 * Time: 10:27 AM
 */

namespace distribuidor;


class TablaCliente_Usuario extends \cbizcontrol
{
    function create_table(){
        return <<<sql
create table cliente_usuario
(
	id_cliente_usuario bigint auto_increment comment 'id cliente usuario'
		primary key,
	id_cliente bigint null comment 'id cliente',
	id_usuario bigint null comment 'id de usuario',
	constraint cliente_usuario_id_cliente_id_usuario_pk
		unique (id_cliente, id_usuario)
);

create index FK_cliente_usuario_id_cliente
	on cliente_usuario (id_cliente);

create index FK_cliente_usuario_id_usuario
	on cliente_usuario (id_usuario);

sql;

    }
    function insertClienteUsuario($id_cliente, $id_usuario)
    {
        $sql = /** @lang MySQL */
            <<<MySQL
INSERT cliente_usuario(id_cliente, id_usuario) VALUES (?,?)
MySQL;
        $this->consulta($sql,['ii',$id_cliente,$id_usuario]);
    }
}