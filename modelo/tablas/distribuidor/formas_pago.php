<?php
/**
 * Created by PhpStorm.
 * User: Cachu
 * Date: 18/12/2017
 * Time: 05:48 PM
 */

namespace distribuidor;


use cbizcontrol;

class TablaFormas_Pago extends cbizcontrol
{
    function create_table()
    {
        // TODO: Implement create_table() method.
    }

    public function selectFormasPago()
    {
        $sql = /** @lang MySQL */
            <<<MySQL
SELECT 
 id_forma_pago id,
 clave_forma_pago clave,
 descripcion_forma_pago nombre
FROM formas_pago;
MySQL;
        return $this->consulta($sql);
    }

    function selectFormaPago($id_forma_pago)
    {
        $sql = /** @lang MySQL */
            <<<MySQL
SELECT 
 id_forma_pago id,
 clave_forma_pago clave,
 descripcion_forma_pago nombre
 FROM formas_pago
 WHERE id_forma_pago='$id_forma_pago';
MySQL;
        return $this->siguiente_registro($this->consulta($sql));
    }
}
