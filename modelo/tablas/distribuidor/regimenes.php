<?php

namespace distribuidor;

use cbizcontrol;

class TablaRegimenes extends cbizcontrol
{
    function create_table()
    {
        return <<<sql
create table regimen_fiscal
(
    id_regimen_fiscal          bigint auto_increment comment 'id regimen'
        primary key,
    clave_regimen_fiscal       varchar(3)            null comment 'clave regimen fiscal',
    descripcion_regimen_fiscal varchar(255)          null comment 'nombre regimen fiscal',
    fisica_regimen_fiscal      smallint(1) default 0 null,
    moral_regimen_fiscal       smallint(1) default 0 null
);
sql;
    }

    function selectRegimen($idRegimen)
    {
        $sql = /** @lang MySQL */
            <<<MySQL
SELECT
     clave_regimen_fiscal AS clave,
     descripcion_regimen_fiscal AS nombre,
     fisica_regimen_fiscal AS fisica,
     moral_regimen_fiscal AS moral
FROM regimen_fiscal
WHERE id_regimen_fiscal = '$idRegimen'
MySQL;

        return (array)$this->siguiente_registro($this->consulta($sql));
    }

    public function selectRegimenes()
    {
        $sql = /** @lang MySQL */
            <<<MySQL
SELECT
    id_regimen_fiscal AS id,
    clave_regimen_fiscal AS clave,
    descripcion_regimen_fiscal AS nombre,
    fisica_regimen_fiscal AS fisica,
    moral_regimen_fiscal AS moral
FROM regimen_fiscal;
MySQL;

        return $this->consulta($sql);
    }

    public function selectRegimenFromClave($claveRegimen)
    {
        $sql = /** @lang MySQL */
            <<<MySQL
SELECT
    id_regimen_fiscal AS id,
    clave_regimen_fiscal AS clave,
    descripcion_regimen_fiscal AS nombre,
    fisica_regimen_fiscal AS fisica,
    moral_regimen_fiscal AS moral
FROM regimen_fiscal
WHERE clave_regimen_fiscal = '$claveRegimen'
MySQL;

        return $this->siguiente_registro($this->consulta($sql));
    }
}
