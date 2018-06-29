<?php
/**
 * Created by PhpStorm.
 * User: Cachu
 * Date: 14/11/2017
 * Time: 01:59 PM
 */

namespace distribuidor;

/**
 * Class TablaUnidades
 * CREATE TABLE unidades
    (
        id_unidad bigint(20) PRIMARY KEY NOT NULL AUTO_INCREMENT,
        clave_unidad varchar(255) NOT NULL,
        nombre_unidad varchar(255) NOT NULL
    );
 * CREATE UNIQUE INDEX unidades_clave_unidad_uindex ON unidades (clave_unidad);
 * CREATE UNIQUE INDEX unidades_nombre_unidad_uindex ON unidades (nombre_unidad);
 * @package distribuidor
 */
class TablaUnidades extends \cbizcontrol
{

    function selectUnidad($id_unidad)
    {
        $sql = /** @lang MySQL */
            <<<MySQL
SELECT 
 clave_unidad clave,
 nombre_unidad nombre
 FROM unidades WHERE id_unidad='$id_unidad'
MySQL;

        return (array)$this->siguiente_registro($this->consulta($sql));
    }

    public function selectUnidades()
    {
        $sql = /** @lang MySQL */
            <<<MySQL
SELECT 
  id_unidad id,
  clave_unidad clave,
  nombre_unidad nombre
 FROM unidades;
MySQL;
        return $this->consulta($sql);
    }

    public function selectUnidadFromClave($clave_unidad)
    {
        $sql = /** @lang MySQL */
            <<<MySQL
SELECT 
 id_unidad id,
 clave_unidad clave,
 nombre_unidad nombre
 FROM unidades
WHERE clave_unidad='$clave_unidad'
MySQL;
        return $this->siguiente_registro($this->consulta($sql));
    }
}