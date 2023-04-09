<?php
/**
 * Created by PhpStorm.
 * User: Cachu
 * Date: 16/11/2017
 * Time: 10:32 AM
 */

namespace distribuidor;


use cbizcontrol;

class TablaUsoCFDI extends cbizcontrol
{
    public function create_table()
    {
        // TODO: Implement create_table() method.
    }

    public function selectUsosCFDI()
    {
        $sql = /** @lang MySQL */
            <<<MySQL
SELECT 
 id_usocfdi id,
 clave_usocfdi clave,
 descripcion_usocfdi nombre
 FROM usocfdi;
MySQL;
        return $this->consulta($sql);
    }

    public function selectUsoCFDI($id_usocfdi)
    {
        $sql = /** @lang MySQL */
            <<<MySQL
SELECT 
 id_usocfdi id,
 clave_usocfdi clave,
 descripcion_usocfdi nombre
 FROM usocfdi
 where id_usocfdi='$id_usocfdi';
MySQL;
        return $this->siguiente_registro($this->consulta($sql));
    }
}
