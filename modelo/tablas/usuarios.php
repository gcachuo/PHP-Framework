<?php

/**
 * Created by PhpStorm.
 * User: Cachu
 * Date: 23/feb/2017
 * Time: 10:44 AM
 */
class TablaUsuarios extends Tabla
{
    function create_table()
    {
        $sql = /** @lang MySQL */
            <<<MySQL
CREATE TABLE `_usuarios`
(
    id_usuario BIGINT(20) PRIMARY KEY NOT NULL AUTO_INCREMENT,
    nombre_usuario VARCHAR(100),
    login_usuario VARCHAR(50) NOT NULL,
    id_sucursal BIGINT(20),
    password_usuario VARCHAR(255) NOT NULL,
    correo_usuario VARCHAR(255),
    estatus_usuario BIT(1) DEFAULT b'1' NOT NULL,
    perfil_usuario BIGINT(20) DEFAULT '1' NOT NULL,
    id_usuario_create BIGINT(20) NOT NULL COMMENT 'usuario que creo el registro'/*,
    CONSTRAINT _usuarios_sucursales_id_sucursal_fk
    FOREIGN KEY (id_sucursal)
    REFERENCES sucursales (id_sucursal)
    ON DELETE  SET NULL
    ON UPDATE CASCADE*/
);
CREATE UNIQUE INDEX usuarios_login_usuario_uindex ON `_usuarios` (login_usuario);
INSERT INTO `_usuarios` (nombre_usuario, login_usuario, password_usuario, correo_usuario, estatus_usuario, perfil_usuario, id_usuario_create) VALUES ('codeman', 'codeman', '$2s/9XD3TvHsY', '', TRUE, 0, 0);
MySQL;
        return $sql;
    }

    function selectUsuario($login)
    {
        $sql = /** @lang MySQL */
            <<<MySQL
SELECT 
id_usuario idUsuario,
perfil_usuario idPerfil,
id_sucursal idSucursal,
id_usuario_create idUserCreate,
password_usuario pass
FROM _usuarios
WHERE
  (login_usuario = '$login' OR correo_usuario='$login')
 AND estatus_usuario = TRUE 
MySQL;

        $consulta = $this->consulta($sql);
        $registro = $this->siguiente_registro($consulta);
        return $registro;
    }

    function selectUsuarioFromId($id_usuario)
    {
        $sql = /** @lang MySQL */
            <<<MySQL
SELECT 
      id_usuario     id,
      nombre_usuario nombre,
      login_usuario  login,
      correo_usuario correo,
      perfil_usuario perfil
FROM _usuarios
WHERE id_usuario=$id_usuario
MySQL;

        $consulta = $this->consulta($sql);
        $registro = $this->siguiente_registro($consulta);
        return $registro;
    }

    function selectUsuarioFromLogin($login_usuario)
    {
        $sql = /** @lang MySQL */
            <<<MySQL
SELECT
  id_usuario        id,
  nombre_usuario    nombreUsuario,
  id_usuario_create idUserCreate,
  estatus_usuario   estatus,
  correo_usuario    email,
  perfil_usuario    perfil
FROM _usuarios
WHERE login_usuario = '$login_usuario'
MySQL;

        $consulta = $this->consulta($sql);
        $registro = $this->siguiente_registro($consulta);
        return $registro;
    }

    function selectRegistrosUsuarios()
    {
        $sql = /** @lang MySQL */
            <<<MySQL
SELECT
  u.id_usuario   id,
  nombre_usuario nombre,
  login_usuario  login,
  nombre_perfil  perfil
FROM _usuarios u
  JOIN _perfiles ON id_perfil = perfil_usuario
WHERE estatus_usuario = TRUE
      AND if($_SESSION[perfil] = 0, 0 = 0, perfil_usuario > 0)
MySQL;
        return $this->consulta($sql);
    }

    function insertUsuario($nombre_usuario, $login_usuario, $password_usuario, $correo_usuario, $perfil_usuario, $id_usuario = 'null', $id_usuario_create = null)
    {
        if (is_null($id_usuario_create)) $id_usuario_create = $_SESSION["usuario"];
        $id_usuario = empty($id_usuario) ? 'null' : "'$id_usuario'";
        $sql = /** @lang MySQL */
            <<<MySQL
INSERT INTO
  _usuarios (id_usuario,nombre_usuario, login_usuario, password_usuario, correo_usuario, perfil_usuario,id_usuario_create)
VALUES ($id_usuario,'$nombre_usuario', '$login_usuario', '$password_usuario', '$correo_usuario', $perfil_usuario,'$id_usuario_create')
ON DUPLICATE KEY UPDATE 
login_usuario='$login_usuario',
nombre_usuario = '$nombre_usuario',
password_usuario='$password_usuario', 
correo_usuario='$correo_usuario', 
perfil_usuario=$perfil_usuario,
id_usuario_create='$id_usuario_create'
MySQL;
        $consulta = $this->consulta($sql);
        if ($id_usuario != 'null') $id = $consulta;
        else $id = $id_usuario;
        return $id;
    }

    function updateUsuario($id, $nombre, $login, $correo, $perfil, $password, $sucursal)
    {
        if ($password != "") {
            $sql = /**@lang MySQL */
                <<<MySQL
UPDATE _usuarios
SET 
  nombre_usuario = '$nombre',
  correo_usuario = '$correo',
  id_sucursal = '$sucursal',
  perfil_usuario = $perfil,
  password_usuario = '$password',
  id_usuario_create = 0
WHERE id_usuario = $id
MySQL;

        } else {
            $sql = /**@lang MySQL */
                <<<MySQL
UPDATE _usuarios
SET 
  nombre_usuario = '$nombre',
  correo_usuario = '$correo', 
  perfil_usuario = $perfil
WHERE id_usuario = $id
MySQL;
        }
        $this->consulta($sql);
    }

    function updateEstatusUsuario($id)
    {
        $sql = /**@lang MySQL */
            <<<MySQL
UPDATE _usuarios
SET estatus_usuario = FALSE
WHERE id_usuario = $id
MySQL;
        $this->consulta($sql);
    }

    function selectPerfil($usuario)
    {
        $sql = /** @lang MySQL */
            <<<MySQL
SELECT perfil_usuario perfil
FROM `_usuarios`
WHERE id_usuario = '$usuario'
MySQL;

        $consulta = $this->consulta($sql);
        $registro = $this->siguiente_registro($consulta);
        $perfil = $registro->perfil;
        return $perfil;
    }

    function updateIdUserCreate($id_usuario, $id_usuario_create)
    {
        $sql = <<<MySQL
update _usuarios set id_usuario_create='$id_usuario_create' where id_usuario='$id_usuario';
MySQL;
        $this->consulta($sql);
    }
}