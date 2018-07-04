<?php

/**
 * Created by PhpStorm.
 * User: Cachu
 * Date: 28/feb/2017
 * Time: 01:34 PM
 */
class TablaPerfiles_Acciones extends Tabla
{
    function create_table()
    {
        $sql = /** @lang MySQL */
            <<<MySQL
CREATE TABLE `_perfiles_acciones`
(
    id_perfil_accion bigint(20) PRIMARY KEY NOT NULL AUTO_INCREMENT,
    id_perfil bigint(20) NOT NULL,
    id_accion bigint(20) NOT NULL
);
CREATE UNIQUE INDEX `_perfiles_acciones_id_perfil_id_accion_pk` ON `_perfiles_acciones` (id_perfil, id_accion);
INSERT INTO `_perfiles_acciones` (id_perfil_accion, id_perfil, id_accion) VALUES (1, 1, 1);
INSERT INTO `_perfiles_acciones` (id_perfil_accion, id_perfil, id_accion) VALUES (2, 1, 2);
INSERT INTO `_perfiles_acciones` (id_perfil_accion, id_perfil, id_accion) VALUES (3, 1, 3);
INSERT INTO `_perfiles_acciones` (id_perfil_accion, id_perfil, id_accion) VALUES (4, 1, 4);
INSERT INTO `_perfiles_acciones` (id_perfil_accion, id_perfil, id_accion) VALUES (5, 1, 5);
INSERT INTO `_perfiles_acciones` (id_perfil_accion, id_perfil, id_accion) VALUES (6, 1, 6);
INSERT INTO `_perfiles_acciones` (id_perfil_accion, id_perfil, id_accion) VALUES (7, 1, 7);
INSERT INTO `_perfiles_acciones` (id_perfil_accion, id_perfil, id_accion) VALUES (8, 1, 8);
INSERT INTO `_perfiles_acciones` (id_perfil_accion, id_perfil, id_accion) VALUES (9, 1, 9);
INSERT INTO `_perfiles_acciones` (id_perfil_accion, id_perfil, id_accion) VALUES (10, 1, 10);
INSERT INTO `_perfiles_acciones` (id_perfil_accion, id_perfil, id_accion) VALUES (11, 1, 11);
INSERT INTO `_perfiles_acciones` (id_perfil_accion, id_perfil, id_accion) VALUES (12, 1, 12);
INSERT INTO `_perfiles_acciones` (id_perfil_accion, id_perfil, id_accion) VALUES (13, 1, 13);
INSERT INTO `_perfiles_acciones` (id_perfil_accion, id_perfil, id_accion) VALUES (14, 1, 14);
INSERT INTO `_perfiles_acciones` (id_perfil_accion, id_perfil, id_accion) VALUES (15, 1, 15);
INSERT INTO `_perfiles_acciones` (id_perfil_accion, id_perfil, id_accion) VALUES (16, 1, 16);
INSERT INTO `_perfiles_acciones` (id_perfil_accion, id_perfil, id_accion) VALUES (17, 1, 17);
INSERT INTO `_perfiles_acciones` (id_perfil_accion, id_perfil, id_accion) VALUES (18, 1, 18);
INSERT INTO `_perfiles_acciones` (id_perfil_accion, id_perfil, id_accion) VALUES (19, 1, 19);
INSERT INTO `_perfiles_acciones` (id_perfil_accion, id_perfil, id_accion) VALUES (20, 1, 20);
INSERT INTO `_perfiles_acciones` (id_perfil_accion, id_perfil, id_accion) VALUES (21, 1, 21);
INSERT INTO `_perfiles_acciones` (id_perfil_accion, id_perfil, id_accion) VALUES (22, 1, 22);
INSERT INTO `_perfiles_acciones` (id_perfil_accion, id_perfil, id_accion) VALUES (23, 1, 23);
INSERT INTO `_perfiles_acciones` (id_perfil_accion, id_perfil, id_accion) VALUES (24, 1, 24);
INSERT INTO `_perfiles_acciones` (id_perfil_accion, id_perfil, id_accion) VALUES (25, 1, 25);
INSERT INTO `_perfiles_acciones` (id_perfil_accion, id_perfil, id_accion) VALUES (26, 1, 26);
INSERT INTO `_perfiles_acciones` (id_perfil_accion, id_perfil, id_accion) VALUES (27, 1, 27);
INSERT INTO `_perfiles_acciones` (id_perfil_accion, id_perfil, id_accion) VALUES (28, 1, 28);
INSERT INTO `_perfiles_acciones` (id_perfil_accion, id_perfil, id_accion) VALUES (29, 1, 29);
INSERT INTO `_perfiles_acciones` (id_perfil_accion, id_perfil, id_accion) VALUES (30, 1, 30);
INSERT INTO `_perfiles_acciones` (id_perfil_accion, id_perfil, id_accion) VALUES (31, 1, 31);
INSERT INTO `_perfiles_acciones` (id_perfil_accion, id_perfil, id_accion) VALUES (32, 1, 32);
INSERT INTO `_perfiles_acciones` (id_perfil_accion, id_perfil, id_accion) VALUES (33, 1, 33);
INSERT INTO `_perfiles_acciones` (id_perfil_accion, id_perfil, id_accion) VALUES (34, 1, 34);
INSERT INTO `_perfiles_acciones` (id_perfil_accion, id_perfil, id_accion) VALUES (35, 1, 35);
INSERT INTO `_perfiles_acciones` (id_perfil_accion, id_perfil, id_accion) VALUES (36, 1, 36);
INSERT INTO `_perfiles_acciones` (id_perfil_accion, id_perfil, id_accion) VALUES (37, 1, 37);
INSERT INTO `_perfiles_acciones` (id_perfil_accion, id_perfil, id_accion) VALUES (38, 1, 38);
INSERT INTO `_perfiles_acciones` (id_perfil_accion, id_perfil, id_accion) VALUES (39, 1, 39);
INSERT INTO `_perfiles_acciones` (id_perfil_accion, id_perfil, id_accion) VALUES (40, 1, 40);
INSERT INTO `_perfiles_acciones` (id_perfil_accion, id_perfil, id_accion) VALUES (41, 1, 41);
INSERT INTO `_perfiles_acciones` (id_perfil_accion, id_perfil, id_accion) VALUES (42, 1, 42);
INSERT INTO `_perfiles_acciones` (id_perfil_accion, id_perfil, id_accion) VALUES (43, 1, 43);
INSERT INTO `_perfiles_acciones` (id_perfil_accion, id_perfil, id_accion) VALUES (44, 1, 44);
INSERT INTO `_perfiles_acciones` (id_perfil_accion, id_perfil, id_accion) VALUES (45, 1, 45);
INSERT INTO `_perfiles_acciones` (id_perfil_accion, id_perfil, id_accion) VALUES (46, 1, 46);
INSERT INTO `_perfiles_acciones` (id_perfil_accion, id_perfil, id_accion) VALUES (47, 1, 47);
INSERT INTO `_perfiles_acciones` (id_perfil_accion, id_perfil, id_accion) VALUES (48, 1, 48);
INSERT INTO `_perfiles_acciones` (id_perfil_accion, id_perfil, id_accion) VALUES (49, 1, 49);
INSERT INTO `_perfiles_acciones` (id_perfil_accion, id_perfil, id_accion) VALUES (50, 1, 50);
INSERT INTO `_perfiles_acciones` (id_perfil_accion, id_perfil, id_accion) VALUES (51, 1, 51);
INSERT INTO `_perfiles_acciones` (id_perfil_accion, id_perfil, id_accion) VALUES (52, 1, 52);
INSERT INTO `_perfiles_acciones` (id_perfil_accion, id_perfil, id_accion) VALUES (53, 1, 53);
INSERT INTO `_perfiles_acciones` (id_perfil_accion, id_perfil, id_accion) VALUES (54, 1, 54);
INSERT INTO `_perfiles_acciones` (id_perfil_accion, id_perfil, id_accion) VALUES (55, 1, 55);
INSERT INTO `_perfiles_acciones` (id_perfil_accion, id_perfil, id_accion) VALUES (56, 1, 56);
INSERT INTO `_perfiles_acciones` (id_perfil_accion, id_perfil, id_accion) VALUES (57, 1, 57);
INSERT INTO `_perfiles_acciones` (id_perfil_accion, id_perfil, id_accion) VALUES (58, 1, 58);
MySQL;
        return $sql;
    }

    function selectAccionesFromModuloAndPerfil($id_modulo, $id_perfil)
    {
        $sql = /** @lang MySQL */
            <<<MySQL
SELECT
  nombre_accion id,
  1 accion
FROM `_perfiles_acciones` pa
  INNER JOIN `_acciones` a ON a.id_accion = pa.id_accion
  INNER JOIN `_modulos` m ON m.id_modulo = a.id_modulo
WHERE pa.id_perfil = $id_perfil AND navegar_modulo='$id_modulo';
MySQL;
        $consulta = $this->consulta($sql);
        $acciones = $this->query2array($consulta, "accion");
        return $acciones;
    }

    function selectAccionesFromPerfil($id_perfil)
    {
        $sql = /** @lang MySQL */
            <<<MySQL
SELECT
  pa.id_accion  id,
 concat(navegar_modulo,'-',nombre_accion) accion
FROM `_perfiles_acciones` pa
  INNER JOIN `_acciones` a ON a.id_accion = pa.id_accion
  INNER JOIN `_modulos` m ON m.id_modulo = a.id_modulo
WHERE pa.id_perfil = $id_perfil;
MySQL;
        $consulta = $this->consulta($sql);
        $acciones = $this->query2array($consulta, "accion");
        return $acciones;
    }

    function insertPerfilAccion($id_perfil, $id_accion)
    {
        $sql = /**@lang MySQL */
            <<<MySQL
REPLACE INTO _perfiles_acciones (id_perfil, id_accion) 
VALUES ($id_perfil, $id_accion);
MySQL;
        $this->consulta($sql);
    }

    function deleteAccionesPerfil($id_perfil)
    {
        $sql = /** @lang MySQL */
            <<<MySQL
DELETE FROM _perfiles_acciones WHERE id_perfil=$id_perfil;
MySQL;
        $this->consulta($sql);
    }
}