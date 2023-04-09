<?php
/**
 * Created by PhpStorm.
 * User: Cachu
 * Date: 18/12/2017
 * Time: 05:50 PM
 */

namespace distribuidor;


use cbizcontrol;

class TablaMetodos_Pago extends cbizcontrol
{
    function create_table()
    {
        // TODO: Implement create_table() method.
    }

    public function selectMetodosPago()
    {
        $sql = /** @lang MySQL */
            <<<MySQL
SELECT 
 id_metodo_pago id,
 clave_metodo_pago clave,
 descripcion_metodo_pago nombre
FROM metodos_pago;
MySQL;
        return $this->consulta($sql);
    }

    function selectMetodoPago($id_metodo_pago)
    {
        $sql = /** @lang MySQL */
            <<<MySQL
SELECT 
 id_metodo_pago id,
 clave_metodo_pago clave,
 descripcion_metodo_pago nombre
 FROM metodos_pago
 WHERE id_metodo_pago='$id_metodo_pago';
MySQL;
        return $this->siguiente_registro($this->consulta($sql));
    }
}
