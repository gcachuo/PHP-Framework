<?php
/**
 * Created by PhpStorm.
 * User: Cachu
 * Date: 06/abr/2017
 * Time: 05:16 PM
 */

namespace distribuidor;

class TablaUsuarios extends \cbizcontrol
{
    function create_table()
    {
        return <<<sql
create table _usuarios
(
	id_usuario bigint auto_increment
		primary key,
	id_perfil bigint not null,
	nombre_usuario varchar(150) not null,
	login_usuario varchar(200) not null,
	password_usuario varchar(200) not null,
	correo_usuario varchar(255) null comment 'correo usuario',
	token_usuario varchar(200) null,
	fecha_creacion_usuario datetime null,
	id_usuarioCreate bigint null,
	fecha_actualizacion_usuario timestamp default CURRENT_TIMESTAMP null on update CURRENT_TIMESTAMP,
	estado_usuario smallint(6) default 1 not null
);

create index FK__usuarios__perfiles_id_perfil
	on _usuarios (id_perfil);

create index FK__usuarios__usuarios_id_usuario
	on _usuarios (id_usuarioCreate);

sql;

    }

    function selectUsuario($login, $password)
    {
        $sql = /** @lang MySQL */
            <<<MySQL
SELECT id_usuario idUsuario
FROM _usuarios
WHERE
  login_usuario = '$login'
  AND password_usuario = '$password'
AND estado_usuario=1
MySQL;
        $consulta = $this->consulta($sql);
        $registro = $this->siguiente_registro($consulta);
        return $registro;
    }

    function selectUsuarioFromLogin($login)
    {
        $sql = /** @lang MySQL */
            <<<MySQL
SELECT
  id_usuario        id,
  nombre_usuario    nombreUsuario,
  estado_usuario   estatus,
  correo_usuario    email,
  id_perfil         perfil
FROM _usuarios
WHERE login_usuario = '$login'
MySQL;

        $consulta = $this->consulta($sql);
        $registro = $this->siguiente_registro($consulta);
        return $registro;
    }

    function insertUsuario($id_perfil, $nombre_usuario, $login_usuario, $password_usuario, $correo_usuario)
    {
        $sql = /** @lang MySQL */
            <<<MySQL
DELETE FROM `_usuarios` WHERE login_usuario='$login_usuario';
MySQL;
        $this->consulta($sql);

        $sql = /** @lang MySQL */
            <<<MySQL
INSERT INTO `_usuarios` (id_perfil, nombre_usuario, login_usuario, password_usuario, correo_usuario, id_usuarioCreate, estado_usuario) VALUES ('$id_perfil', '$nombre_usuario', '$login_usuario', '$password_usuario', '$correo_usuario', 1, 1);
MySQL;
        return $this->consulta($sql);
    }

    function updateEstatusUsuario($login_usuario)
    {
        $sql = /** @lang MySQL */
            <<<MySQL
UPDATE `_usuarios` SET 
estado_usuario=0
WHERE login_usuario='$login_usuario'
MySQL;
        $this->consulta($sql);
    }
}