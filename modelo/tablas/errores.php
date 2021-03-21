<?php

class TablaErrores extends Tabla
{

    /**
     * @return string regresar el texto de la consulta de la creacion de la tabla
     */
    function create_table()
    {
        return <<<sql
create table if not exists _errores
(
	id_error bigint auto_increment
		primary key,
	fecha timestamp default CURRENT_TIMESTAMP not null,
	mensaje longblob null,
	archivo varchar(255) null,
	linea int null,
	codigo int null,
	_post longblob null,
	_get longblob null,
	_server longblob null,
	_session longblob null,
	resuelto bit default b'0' null
)
engine = InnoDB
collate utf8mb4_general_ci
;
sql;

    }

    function insertError($mensaje, $archivo, $linea, $codigo)
    {
        $sql = <<<sql
insert into `_errores`(mensaje, archivo, linea, codigo,`_post`,`_get`,`_server`,`_session`) values (?,?,?,?,?,?,?,?);
sql;
        $this->consulta($this->create_table());
        return $this->consulta($sql, ['ssiissss', ($mensaje), $archivo, $linea, $codigo, json_encode($_POST), json_encode($_GET), json_encode($_SERVER), json_encode($_SESSION)]);
    }

    function selectErrores(array $exclude_codes = [200], $options = [])
    {
        $length = $options['length'] ?: -1;
        $page = $options['page'] ?: 0;
        $search = $options['search'];
        $sql = <<<sql
select id_error id,fecha,mensaje,archivo,linea,codigo,resuelto
from `_errores`
where resuelto = false
  and codigo not in (?)
 and (mensaje like '%$search%' or id_error like '%$search%')
order by fecha desc
limit ?,?;
sql;
        return $this->query2multiarray($this->consulta($sql, ['sii', join(',', $exclude_codes), $page, $length]), MYSQLI_ASSOC);
    }

    function clearErrores(array $errores = [])
    {
        $sql = <<<sql
update `_errores` set resuelto=true where id_error in (?);
sql;
        $this->consulta($sql, ['s', join(',', $errores)]);
    }

    public function selectError($id_error)
    {
        $sql = <<<sql
select * from `_errores` where id_error=?;
sql;
        return $this->siguiente_registro($this->consulta($sql, ['i', $id_error]));
    }
}