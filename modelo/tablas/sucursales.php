<?php
/**
 * Created by PhpStorm.
 * User: edgar
 * Date: 24/11/2017
 * Time: 09:47 AM
 */

class TablaSucursales extends Tabla
{
    function create_table()
    {
        $sql = /** @lang MySQL */
            <<<MySQL
CREATE TABLE sucursales
(
    id_sucursal BIGINT(20) PRIMARY KEY NOT NULL AUTO_INCREMENT,
    nombre_sucursal VARCHAR(100),
    estatus_sucursal BIT(1) DEFAULT b'1'
)
MySQL;
        return $sql;
    }

    function insert_sucursal($nombre_sucursal)
    {
        $sql = /** @lang MySQL */
            <<<MySQL
INSERT INTO sucursales (nombre_sucursal) VALUE ('$nombre_sucursal');
MySQL;
        return $this->consulta($sql);
    }

    function select_sucursales()
    {
        $sql = /** @lang MySQL */
            <<<MySQL
SELECT
id_sucursal as id,
nombre_sucursal as nombre
FROM
sucursales
WHERE
estatus_sucursal = 1
MySQL;
        return $this->query2multiarray($this->consulta($sql));

    }

    function selectSucursalesExistente($nombre)
    {
        $sql = /** @lang MySQL */
            <<<MySQL
        SELECT count(1) existe FROM sucursales WHERE id_sucursal = '$nombre' or nombre_sucursal = '$nombre';
MySQL;
        $registro = $this->siguiente_registro($this->consulta($sql));
        return (bool)$registro->existe;
    }

    function selectSucursalFromNombre($nombre_almacen)
    {
        $sql = /** @lang MySQL */
            <<<MySQL
SELECT id_sucursal id, nombre_sucursal nombre FROM sucursales WHERE nombre_sucursal = '$nombre_almacen';
MySQL;
        return $this->siguiente_registro($this->consulta($sql));
    }
}