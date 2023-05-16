<?php
/**
 * Created by PhpStorm.
 * User: Cachu
 * Date: 06/abr/2017
 * Time: 05:16 PM
 */

namespace distribuidor;

use cbizcontrol;

class TablaUsuarios extends cbizcontrol
{
    public function create_table()
    {
        return <<<sql
CREATE TABLE e11_cbizcontrol._usuarios (
    id_usuario BIGINT(20) PRIMARY KEY NOT NULL AUTO_INCREMENT,
    id_perfil BIGINT(20),
    login_usuario VARCHAR(100),
    password_usuario VARCHAR(200),
    estado_usuario INT,
    nombre_usuario VARCHAR(100),
    correo_usuario VARCHAR(100),
    id_usuarioCreate BIGINT(20)
)
sql;

    }

    public function selectUsuario($login, $password)
    {
        $sql = /** @lang MySQL */
            <<<MySQL
SELECT id_usuario idUsuario
FROM e11_cbizcontrol._usuarios
WHERE
  login_usuario = '$login'
  AND password_usuario = '$password'
AND estado_usuario=1
MySQL;
        $consulta = $this->consulta($sql);
        $registro = $this->siguiente_registro($consulta);
        return $registro;
    }

    public function selectUsuarioFromLogin($login)
    {
        $sql = /** @lang MySQL */
            <<<MySQL
SELECT
  id_usuario        id,
  nombre_usuario    nombreUsuario,
  estado_usuario   estatus,
  correo_usuario    email,
  id_perfil         perfil
FROM e11_cbizcontrol._usuarios
WHERE login_usuario = '$login'
MySQL;

        $consulta = $this->consulta($sql);
        $registro = $this->siguiente_registro($consulta);
        return $registro;
    }

    public function insertUsuario($id_perfil, $nombre_usuario, $login_usuario, $password_usuario, $correo_usuario)
    {
        $sql = /** @lang MySQL */
            <<<MySQL
DELETE FROM e11_cbizcontrol.`_usuarios` WHERE login_usuario='$login_usuario';
MySQL;
        $this->consulta($sql);

        $sql = /** @lang MySQL */
            <<<MySQL
INSERT INTO e11_cbizcontrol.`_usuarios` (id_perfil, nombre_usuario, login_usuario, password_usuario, correo_usuario, id_usuarioCreate, estado_usuario) VALUES ('$id_perfil', '$nombre_usuario', '$login_usuario', '$password_usuario', '$correo_usuario', 1, 1);
MySQL;
        return $this->consulta($sql);
    }

    public function updateEstatusUsuario($login_usuario)
    {
        $sql = /** @lang MySQL */
            <<<MySQL
UPDATE e11_cbizcontrol.`_usuarios` SET 
estado_usuario=0
WHERE login_usuario='$login_usuario'
MySQL;
        $this->consulta($sql);
    }
}
