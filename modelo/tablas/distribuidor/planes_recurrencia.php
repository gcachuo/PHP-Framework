<?php
/**
 * Created by PhpStorm.
 * User: Cachu
 * Date: 03/may/2017
 * Time: 04:30 PM
 */

namespace distribuidor;

class TablaPlanes_Recurrencia extends \cbizcontrol
{
    function selectPlanes()
    {
        $sql = <<<MySQL
SELECT
  id_plan                 idPlan,
  id_paypal_billing       idPaypalBilling,
  nombre_plan             nombre,
  descripcion_plan        descripcion,
  tipo_plan               tipo,
  nombre_detalle_plan     nombreDetalle,
  tipo_detalle_plan       tipoDetalle,
  intervalo_detalle_plan  intervalo,
  frecuencia_detalle_plan frecuencia,
  ciclos_detalle_plan     ciclos,
  moneda_plan             moneda,
  monto_plan              monto,
  max_errores             maxFailedAttempts,
  modo_paypal             modo,
  paypal_cancelurl        cancelURL,
  paypal_returnurl        returnURL
FROM e11_cbizcontrol.planes_recurrencia
MySQL;
        return $this->consulta($sql);
    }

    function selectIdFromPaypal($id_paypal_billing)
    {
        $sql = <<<MySQL
select id_plan idPlan from e11_cbizcontrol.planes_recurrencia where id_paypal_billing='$id_paypal_billing'
MySQL;
        $registro = $this->siguiente_registro($this->consulta($sql));
        $idPlan = $registro->idPlan;
        return $idPlan;
    }

    function selectPlanFromId($id_plan)
    {
        $sql = <<<MySQL
select id_paypal_billing idPaypal from e11_cbizcontrol.planes_recurrencia where id_plan='$id_plan'
MySQL;
        return $this->siguiente_registro($this->consulta($sql))->idPaypal;
    }

    function updateIdPaypalBilling($id_plan, $id_paypal_billing)
    {
        $sql = <<<MySQL
update e11_cbizcontrol.planes_recurrencia set id_paypal_billing='$id_paypal_billing'
where id_plan='$id_plan'
MySQL;
        $this->consulta($sql);
    }
}