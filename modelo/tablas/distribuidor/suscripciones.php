<?php
/**
 * Created by PhpStorm.
 * User: Cachu
 * Date: 04/may/2017
 * Time: 11:10 AM
 */

namespace distribuidor;


class TablaSuscripciones extends \cbizcontrol
{
    function insertToken($id_paypal_token, $id_cliente)
    {
        $sql = <<<MySQL
update suscripciones
set id_paypal_token = '$id_paypal_token'
where id_cliente = '$id_cliente'
MySQL;
        $this->consulta($sql);
    }

    function insertSuscripcion($id_cliente, $id_plan)
    {
        $sql = <<<MySQL
replace into suscripciones(id_cliente, id_plan)
VALUES ('$id_cliente','$id_plan')
MySQL;
        $this->consulta($sql);
    }

    function updateEstatusSuscripcion($id_cliente, $estatus_suscripcion)
    {
        $estatus_suscripcion = $estatus_suscripcion ? 'true' : 'false';
        $sql = <<<MySQL
update suscripciones
set estatus_suscripcion=$estatus_suscripcion
where id_cliente='$id_cliente'
MySQL;
        $this->consulta($sql);
    }

    function selectEstatusSuscripcion($id_cliente)
    {
        $sql = <<<MySQL
select estatus_suscripcion estatus from suscripciones where id_cliente='$id_cliente'
MySQL;
        $registro = $this->siguiente_registro($this->consulta($sql));
        $estatus = $registro->estatus;
        return $estatus;
    }

    /**
     * @param int $id_cliente
     * @return string
     */
    function selectIdPaypalSuscripcion($id_cliente)
    {
        $sql = <<<MySQL
select id_paypal_token idPaypal from suscripciones where id_cliente='$id_cliente'
MySQL;
        $registro = $this->siguiente_registro($this->consulta($sql));
        $idPaypal = $registro->idPaypal;
        return $idPaypal;
    }
}