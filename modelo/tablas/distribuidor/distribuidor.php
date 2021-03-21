<?php
/**
 * Created by PhpStorm.
 * User: Cachu
 * Date: 08/may/2017
 * Time: 09:37 AM
 */

namespace distribuidor;

use cbizcontrol;

class TablaDistribuidor extends cbizcontrol
{
    function create_table(): string
    {
        return <<<sql
CREATE TABLE e11_cbizcontrol.distribuidor(
    id_distribuidor BIGINT PRIMARY KEY AUTO_INCREMENT NOT NULL,
    id_padre BIGINT,
    nombre_distribuidor VARCHAR(100),
    perfil_distribuidor INT,
    password_distribuidor VARCHAR(200),
    correo_distribuidor VARCHAR(100),
    token_distribuidor VARCHAR(200),
    estatus_distribuidor INT
);
sql;

    }

    /**
     * @param string $token_distribuidor
     * @param string $password_distribuidor
     * @return \stdClass
     */
    function selectDistribuidor($token_distribuidor, $password_distribuidor)
    {
        $sql = <<<MySQL
select
  id_distribuidor     idUsuario,
  nombre_distribuidor nombre,
  perfil_distribuidor perfil
from e11_cbizcontrol.distribuidor
where token_distribuidor = '$token_distribuidor' 
      and password_distribuidor = '$password_distribuidor'
MySQL;
        /**
         * @var \stdClass $usuario
         */
        $usuario = $this->siguiente_registro($this->consulta($sql));

        return $usuario;
    }

    function selectDistribuidorFromId($id_distribuidor)
    {
        $sql = <<<MySQL
select
  id_distribuidor     idUsuario,
  nombre_distribuidor nombre,
  correo_distribuidor correo,
  perfil_distribuidor perfil,
  id_padre            idPartner
from e11_cbizcontrol.distribuidor
where id_distribuidor='$id_distribuidor'
MySQL;
        return $this->siguiente_registro($this->consulta($sql));
    }

    /**
     * @param string $token_distribuidor
     * @return int
     */
    function selectIdDistribuidorFromToken($token_distribuidor)
    {
        $sql = <<<MySQL
select id_distribuidor idDistribuidor from e11_cbizcontrol.distribuidor where token_distribuidor='$token_distribuidor'
MySQL;
        $registro = $this->siguiente_registro($this->consulta($sql));
        $idDistribuidor = $registro->idDistribuidor;
        return $idDistribuidor;
    }

    function selectDistribuidores()
    {
        $sql = <<<MySQL
SELECT
  d1.id_distribuidor     idDistribuidor,
  d1.id_padre            idPartner,
  d1.token_distribuidor  tokenDistribuidor,
  d1.nombre_distribuidor nombreDistribuidor,
  d2.nombre_distribuidor nombrePartner,
  d1.correo_distribuidor correoDistribuidor
FROM e11_cbizcontrol.distribuidor d1
  INNER JOIN e11_cbizcontrol.distribuidor d2 ON d2.id_distribuidor = d1.id_padre
WHERE d1.estatus_distribuidor = 1;
MySQL;

        return $this->consulta($sql);
    }

    function selectPartners()
    {
        $sql = <<<MySQL
SELECT
  id_distribuidor     idDistribuidor,
  token_distribuidor  tokenDistribuidor,
  nombre_distribuidor nombreDistribuidor,
  correo_distribuidor correoDistribuidor
FROM e11_cbizcontrol.distribuidor
WHERE (estatus_distribuidor = 1 OR id_distribuidor=1)
      AND perfil_distribuidor < 2;
MySQL;

        return $this->consulta($sql);
    }

    function selectPartnerFromId($id_distribuidor)
    {
        $sql = <<<MySQL
SELECT
  id_distribuidor     idDistribuidor,
  token_distribuidor  tokenDistribuidor,
  nombre_distribuidor nombreDistribuidor,
  correo_distribuidor correoDistribuidor
FROM e11_cbizcontrol.distribuidor
WHERE (estatus_distribuidor = 1 OR id_distribuidor=1)
      AND perfil_distribuidor < 2
      and id_distribuidor='$id_distribuidor';
MySQL;

        return $this->consulta($sql);
    }

    function insertDistribuidor($id_padre, $token_distribuidor, $password_distribuidor, $nombre_distribuidor, $correo_distribuidor, $perfil_distribuidor)
    {
        $now = date('Y-m-d H:i:s');
        $sql = <<<MySQL
insert into e11_cbizcontrol.distribuidor(id_padre, token_distribuidor, password_distribuidor, nombre_distribuidor, correo_distribuidor, perfil_distribuidor,fecha_distribuidor,id_usuario) values('$id_padre', '$token_distribuidor', '$password_distribuidor', '$nombre_distribuidor', '$correo_distribuidor', '$perfil_distribuidor','$now',1); 
MySQL;
        $this->consulta($sql);
    }
}
