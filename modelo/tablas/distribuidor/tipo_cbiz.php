<?php
/**
 * Created by PhpStorm.
 * User: Cachu
 * Date: 13/mar/2021
 * Time: 03:35 PM
 */

namespace distribuidor;

use cbizcontrol;

class TablaTipo_Cbiz extends cbizcontrol
{
    function create_table(): string
    {
        return <<<sql
CREATE TABLE e11_cbizcontrol.tipo_cbiz (
    id_tipo_cbiz BIGINT,
    nombre_tipo_cbiz VARCHAR(100)
);
sql;

    }
}
