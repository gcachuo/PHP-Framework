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
from distribuidor
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
from distribuidor
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
select id_distribuidor idDistribuidor from distribuidor where token_distribuidor='$token_distribuidor'
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
FROM distribuidor d1
  INNER JOIN distribuidor d2 ON d2.id_distribuidor = d1.id_padre
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
FROM distribuidor
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
FROM distribuidor
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
insert into distribuidor(id_padre, token_distribuidor, password_distribuidor, nombre_distribuidor, correo_distribuidor, perfil_distribuidor,fecha_distribuidor,id_usuario) values('$id_padre', '$token_distribuidor', '$password_distribuidor', '$nombre_distribuidor', '$correo_distribuidor', '$perfil_distribuidor','$now',1); 
MySQL;
        $this->consulta($sql);
    }
}