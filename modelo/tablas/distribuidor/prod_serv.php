<?php
/**
 * Created by PhpStorm.
 * User: Cachu
 * Date: 15/11/2017
 * Time: 11:18 AM
 */

namespace distribuidor;

use cbizcontrol;

/**
 * Class TablaProd_Serv
 * CREATE TABLE prod_serv
 * (
 * id_prod_serv bigint(20) NOT NULL AUTO_INCREMENT PRIMARY_KEY,
 * clave_prod_serv varchar(255) NOT NULL,
 * descripcion_prod_serv varchar(255) NOT NULL
 * );
 * CREATE UNIQUE INDEX prod_serv_clave_prod_serv_uindex ON prod_serv (clave_prod_serv);
 * CREATE UNIQUE INDEX prod_serv_descripcion_prod_serv_uindex ON prod_serv (descripcion_prod_serv);
 * @package distribuidor
 */
class TablaProd_Serv extends cbizcontrol
{

    function create_table(): string
    {
        return <<<sql
CREATE TABLE prod_serv(
    id_prod_serv BIGINT AUTO_INCREMENT PRIMARY KEY NOT NULL,
    clave_prod_serv VARCHAR(100),
    descripcion_prod_serv VARCHAR(200)
)
sql;

    }

    function selectClave($id_prod_serv)
    {
        $sql = /** @lang MySQL */
            <<<MySQL
SELECT 
 clave_prod_serv clave
 FROM prod_serv WHERE id_prod_serv='$id_prod_serv'
MySQL;
        $registro = $this->siguiente_registro($this->consulta($sql));
        $clave = $registro->clave;
        return $clave;
    }

    public function selectClaves()
    {
        $sql = /** @lang MySQL */
            <<<MySQL
SELECT 
 id_prod_serv id,
 clave_prod_serv clave,
 descripcion_prod_serv nombre
 FROM prod_serv;
MySQL;
        return $this->consulta($sql);
    }

    public function selectProdServFromClave($clave_prod_serv)
    {
        $sql = /** @lang MySQL */
            <<<MySQL
SELECT 
 id_prod_serv id,
 clave_prod_serv clave,
 descripcion_prod_serv descripcion
FROM prod_serv WHERE clave_prod_serv='$clave_prod_serv'
MySQL;
        return $this->siguiente_registro($this->consulta($sql));
    }
}
