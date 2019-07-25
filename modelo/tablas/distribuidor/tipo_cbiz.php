<?php
/**
 * Created by PhpStorm.
 * User: Cachu
 * Date: 13/feb/2019
 * Time: 11:10 AM
 */

namespace distribuidor;

use cbizcontrol;

class TablaTipo_Cbiz extends cbizcontrol
{
    static function create_table()
    {
        $sql = <<<sql
create table tipo_cbiz
(
	id_tipo_cbiz bigint auto_increment comment 'id tipo de cbiz'
		primary key,
	nombre_tipo_cbiz varchar(100) null comment 'nombre tipo cbiz',
	txt_placeholder_tipo_cbiz varchar(100) null comment 'texto para placeholder',
	numMin_tipo_cbiz int(10) null comment 'num minimo en tipo de cbiz',
	fecha_tipo_cbiz_tipo_cbiz datetime null comment 'fecha tipo cbiz',
	fecha_alta_archivo_tipo_cbiz datetime null comment 'fecha update alta archivo tipo cbiz',
	fecha_update_archivo_tipo_cbiz datetime null comment 'fecha update actualiza archivo tipo cbiz',
	url_alta_tipo_cbiz varchar(100) null comment 'url de alta tipo cbiz',
	url_actualiza_tipo_cbiz varchar(100) null comment 'url actualiza tipo cbiz',
	url_sistema_tipo_cbiz varchar(100) null comment 'url del sistema de tipo de cbiz',
	version_sistema_tipo_cbiz varchar(100) null comment 'version del sistema tipo cbiz',
	id_usuario bigint null comment 'id de usuario',
	fecha_actualiza_tipo_cbiz timestamp default CURRENT_TIMESTAMP null on update CURRENT_TIMESTAMP comment 'fecha actualiza tipo cbiz',
	estatus_tipo_cbiz smallint(1) default 1 null comment 'estatus tipo cbiz'
);
sql;
        return $sql;
    }
}