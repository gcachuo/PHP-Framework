<?php
/**
 * Created by PhpStorm.
 * User: Cachu
 * Date: 11/abr/2017
 * Time: 01:20 PM
 */

namespace distribuidor;

use cbizcontrol;

class TablaCliente extends cbizcontrol
{
    function insertCliente($nombre_comercial_cliente, $lada_cliente, $telefono_cliente, $fecha_cliente, $id_usuario, $id_distribuidor = 1)
    {
        $sql = /** @lang MySQL */
            <<<MySQL
INSERT INTO e11_cbizcontrol.cliente (id_distribuidor, nombre_comercial_cliente, lada_cliente, telefono_cliente, fecha_cliente, id_usuario,id_ciudad)
VALUES ('$id_distribuidor', '$nombre_comercial_cliente', '$lada_cliente', '$telefono_cliente', '$fecha_cliente', '$id_usuario',462);
MySQL;

        $id = $this->consulta($sql);
        return $id;
    }

    function selectClientes($id_distribuidor)
    {
        $sql = <<<MySQL
SELECT
  fecha_inicial_cbiz_cliente                         fechaInicial,
  c.id_cliente                                       idCliente,
  c.id_distribuidor                                  idDistribuidor,
  nombre_comercial_cliente                           nombreCliente,
  d.nombre_distribuidor                              nombreDistribuidor,
  coalesce(d2.nombre_distribuidor, 'Sin propartner') nombrePropartner,
  correo_contacto_cliente                            emailCliente,
  token_cbiz_cliente                                 tokenCbiz,
  id_paypal_token                                    paypalToken,
  estatus_suscripcion                                estatusSuscripcion,
  estatus_cbiz_cliente                               estatusCbiz
FROM e11_cbizcontrol.cliente c
  INNER JOIN e11_cbizcontrol.cbiz_cliente cc ON c.id_cliente = cc.id_cliente
  LEFT JOIN e11_cbizcontrol.suscripciones s ON s.id_cliente = c.id_cliente
  INNER JOIN e11_cbizcontrol.contacto_cliente cont ON c.id_cliente = cont.id_cliente
  LEFT JOIN e11_cbizcontrol.distribuidor d ON c.id_distribuidor = d.id_distribuidor
  LEFT JOIN e11_cbizcontrol.distribuidor d2 ON d.id_padre = d2.id_distribuidor
WHERE
if('$id_distribuidor'=1,c.id_distribuidor<>0,
  (c.id_distribuidor='$id_distribuidor' or d.id_padre='$id_distribuidor'))
  AND estatus_cliente = 1
  AND id_tipo_cbiz = 4
  AND estatus_cbiz_cliente = 1
ORDER BY paypalToken DESC, estatusSuscripcion DESC,fechaInicial DESC,tokenCbiz DESC 
MySQL;
        return $this->consulta($sql);
    }

    function selectIdDistribuidorFromId($id_cliente)
    {
        $sql = <<<MySQL
select id_distribuidor idDistribuidor from e11_cbizcontrol.cliente WHERE id_cliente='$id_cliente';
MySQL;
        $registro = $this->siguiente_registro($this->consulta($sql));
        $idDistribuidor = $registro->idDistribuidor;
        return $idDistribuidor;
    }

    function selectClienteFromId($id_cliente)
    {
        $sql = <<<MySQL
select
  id_cliente               idCliente,
  nombre_comercial_cliente nombreCliente,
  rfc_cliente              rfcCliente,
  api_key_factucare        apiKey,
  estatus_cliente          estatusCliente
from e11_cbizcontrol.cliente
where id_cliente = '$id_cliente'
MySQL;
        return $this->siguiente_registro($this->consulta($sql));
    }

    function deleteCliente($id_cliente)
    {
        $sql = <<<MySQL
        SET @idCliente = NULL;
SET @db = NULL;
        
SELECT
  @idCliente := c.id_cliente,
  @db := concat('e11_', token_cbiz_cliente),
  nombre_comercial_cliente,
  correo_contacto_cliente
FROM e11_cbizcontrol.cliente c
  LEFT JOIN e11_cbizcontrol.cbiz_cliente cc ON c.id_cliente = cc.id_cliente
  LEFT JOIN e11_cbizcontrol.contacto_cliente cont ON c.id_cliente = cont.id_cliente
WHERE id_tipo_cbiz = 4 AND estatus_cbiz_cliente = 0 and c.id_cliente='$id_cliente'
LIMIT 1;

SELECT
  @idCliente cliente,
  @db db,
  '0' status;

SET @s = concat('drop database if exists ', @db);
PREPARE stmt_create FROM @s;
EXECUTE stmt_create;
DEALLOCATE PREPARE stmt_create;

SELECT
  @idCliente cliente,
  @db db,
  '20' status;

DELETE FROM e11_cbizcontrol.cbiz_cliente
WHERE id_cliente = @idCliente;

SELECT
  @idCliente cliente,
  @db db,
  '40' status;

DELETE FROM e11_cbizcontrol.contacto_cliente
WHERE id_cliente = @idCliente;

SELECT
  @idCliente cliente,
  @db db,
  '60' status;

DELETE FROM e11_cbizcontrol.cliente_usuario
WHERE id_cliente = @idCliente;

SELECT
  @idCliente cliente,
  @db db,
  '80' status;

DELETE FROM e11_cbizcontrol.cliente
WHERE id_cliente = @idCliente;

SELECT
  @idCliente cliente,
  @db db,
  '100' status;
MySQL;
        $consulta = $this->multiconsulta($sql);
    }

    public function updateRFC($id_cliente, $rfc_cliente)
    {
        $sql = /** @lang MySQL */
            <<<MySQL
UPDATE cliente SET rfc_cliente='$rfc_cliente' WHERE id_cliente='$id_cliente'
MySQL;
        $this->consulta($sql);
    }
}