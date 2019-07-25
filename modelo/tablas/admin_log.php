<?php

class TablaAdmin_Log extends Tabla
{
    /**
     * @return string regresar el texto de la consulta de la creacion de la tabla
     */
    function create_table()
    {
        return <<<sql
create table if not exists _admin_log(
    id_admin_log bigint auto_increment primary key,
    id_usuario bigint not null,
	fecha timestamp default CURRENT_TIMESTAMP not null,
	mensaje longblob null
)
engine = InnoDB
collate utf8mb4_general_ci;
sql;
    }

    function insertLog($id_usuario, $mensaje)
    {
        $sql = <<<sql
insert into _admin_log(id_usuario,mensaje) values (?,?);
sql;
        $this->consulta($this->create_table());
        $this->consulta($sql, ['is', $id_usuario ?: 0, $mensaje]);
    }

    public function selectLog($filtros)
    {
        $limit = $filtros['limit'] ?: 1844674407;
        $page = $filtros['page'] ?: 0;

        $sql = <<<sql
select id_admin_log id,al.id_usuario,login_usuario login,nombre_usuario nombre,fecha,mensaje from _admin_log al inner join `_usuarios` u on u.id_usuario=al.id_usuario
order by fecha desc
limit ?,?;
sql;

        return $this->query2multiarray($this->consulta($sql, ['ii',
            $page,
            $limit]));
    }
}
