<?php
/**
 * Created by PhpStorm.
 * User: gcach
 * Date: 05/11/2017
 * Time: 11:37 PM
 */

namespace distribuidor;

use cbizcontrol;

/**
 * Class TablaImpuestos
 * CREATE TABLE impuestos
    * (
        * id_impuesto bigint(20) PRIMARY KEY NOT NULL AUTO_INCREMENT,
        * id_impuesto_sat varchar(255),
        * nombre_impuesto varchar(100) NOT NULL,
        * valor_impuesto decimal(12,2) NOT NULL
    * );
 * CREATE UNIQUE INDEX impuestos_nombre_impuesto_uindex ON impuestos (nombre_impuesto);
 * @package distribuidor
 */
class TablaImpuestos extends cbizcontrol
{
    function create_table(): string
    {
        return <<<sql
CREATE TABLE e11_cbizcontrol.impuestos(
    id_impuesto BIGINT AUTO_INCREMENT PRIMARY KEY NOT NULL,
    id_impuesto_sat BIGINT,
    nombre_impuesto VARCHAR(100),
    valor_impuesto DECIMAL(12,2)
)
sql;

    }

    /**
     * @return int|\mysqli_result
     * @throws \Exception
     */
    function selectImpuestos()
    {
        $sql = /** @lang MySQL */
            <<<MySQL
SELECT id_impuesto id, nombre_impuesto nombre, valor_impuesto valor FROM impuestos;
MySQL;
        return ($this->consulta($sql));
    }

    function selectImpuesto($id_impuesto)
    {
        $sql = /** @lang MySQL */
            <<<MySQL
SELECT id_impuesto_sat tipo, valor_impuesto tasa FROM impuestos WHERE id_impuesto='$id_impuesto';
MySQL;

        return $this->siguiente_registro($this->consulta($sql));
    }
}
