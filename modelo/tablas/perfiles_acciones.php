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
INSERT INTO `_perfiles_acciones` (id_perfil_accion, id_perfil, id_accion) VALUES (59, 1, 59);
INSERT INTO `_perfiles_acciones` (id_perfil_accion, id_perfil, id_accion) VALUES (60, 1, 60);
INSERT INTO `_perfiles_acciones` (id_perfil_accion, id_perfil, id_accion) VALUES (61, 1, 61);
INSERT INTO `_perfiles_acciones` (id_perfil_accion, id_perfil, id_accion) VALUES (62, 1, 62);
INSERT INTO `_perfiles_acciones` (id_perfil_accion, id_perfil, id_accion) VALUES (63, 1, 63);
INSERT INTO `_perfiles_acciones` (id_perfil_accion, id_perfil, id_accion) VALUES (64, 1, 64);
INSERT INTO `_perfiles_acciones` (id_perfil_accion, id_perfil, id_accion) VALUES (65, 1, 65);
INSERT INTO `_perfiles_acciones` (id_perfil_accion, id_perfil, id_accion) VALUES (66, 1, 66);
INSERT INTO `_perfiles_acciones` (id_perfil_accion, id_perfil, id_accion) VALUES (67, 1, 67);
INSERT INTO `_perfiles_acciones` (id_perfil_accion, id_perfil, id_accion) VALUES (68, 1, 68);
INSERT INTO `_perfiles_acciones` (id_perfil_accion, id_perfil, id_accion) VALUES (69, 1, 69);
INSERT INTO `_perfiles_acciones` (id_perfil_accion, id_perfil, id_accion) VALUES (70, 1, 70);
INSERT INTO `_perfiles_acciones` (id_perfil_accion, id_perfil, id_accion) VALUES (71, 1, 71);
INSERT INTO `_perfiles_acciones` (id_perfil_accion, id_perfil, id_accion) VALUES (72, 1, 72);
INSERT INTO `_perfiles_acciones` (id_perfil_accion, id_perfil, id_accion) VALUES (73, 1, 73);
INSERT INTO `_perfiles_acciones` (id_perfil_accion, id_perfil, id_accion) VALUES (74, 1, 74);
INSERT INTO `_perfiles_acciones` (id_perfil_accion, id_perfil, id_accion) VALUES (75, 1, 75);
INSERT INTO `_perfiles_acciones` (id_perfil_accion, id_perfil, id_accion) VALUES (76, 1, 76);
INSERT INTO `_perfiles_acciones` (id_perfil_accion, id_perfil, id_accion) VALUES (77, 1, 77);
INSERT INTO `_perfiles_acciones` (id_perfil_accion, id_perfil, id_accion) VALUES (78, 1, 78);
INSERT INTO `_perfiles_acciones` (id_perfil_accion, id_perfil, id_accion) VALUES (79, 1, 79);
INSERT INTO `_perfiles_acciones` (id_perfil_accion, id_perfil, id_accion) VALUES (80, 1, 80);
INSERT INTO `_perfiles_acciones` (id_perfil_accion, id_perfil, id_accion) VALUES (81, 1, 81);
INSERT INTO `_perfiles_acciones` (id_perfil_accion, id_perfil, id_accion) VALUES (82, 1, 82);
INSERT INTO `_perfiles_acciones` (id_perfil_accion, id_perfil, id_accion) VALUES (83, 1, 83);
INSERT INTO `_perfiles_acciones` (id_perfil_accion, id_perfil, id_accion) VALUES (84, 1, 84);
INSERT INTO `_perfiles_acciones` (id_perfil_accion, id_perfil, id_accion) VALUES (85, 1, 85);
INSERT INTO `_perfiles_acciones` (id_perfil_accion, id_perfil, id_accion) VALUES (86, 1, 86);
INSERT INTO `_perfiles_acciones` (id_perfil_accion, id_perfil, id_accion) VALUES (87, 1, 87);
INSERT INTO `_perfiles_acciones` (id_perfil_accion, id_perfil, id_accion) VALUES (88, 1, 88);
INSERT INTO `_perfiles_acciones` (id_perfil_accion, id_perfil, id_accion) VALUES (89, 1, 89);
INSERT INTO `_perfiles_acciones` (id_perfil_accion, id_perfil, id_accion) VALUES (90, 1, 90);
INSERT INTO `_perfiles_acciones` (id_perfil_accion, id_perfil, id_accion) VALUES (91, 1, 91);
INSERT INTO `_perfiles_acciones` (id_perfil_accion, id_perfil, id_accion) VALUES (92, 1, 92);
INSERT INTO `_perfiles_acciones` (id_perfil_accion, id_perfil, id_accion) VALUES (93, 1, 93);
INSERT INTO `_perfiles_acciones` (id_perfil_accion, id_perfil, id_accion) VALUES (94, 1, 94);
INSERT INTO `_perfiles_acciones` (id_perfil_accion, id_perfil, id_accion) VALUES (95, 1, 95);
INSERT INTO `_perfiles_acciones` (id_perfil_accion, id_perfil, id_accion) VALUES (96, 1, 96);
INSERT INTO `_perfiles_acciones` (id_perfil_accion, id_perfil, id_accion) VALUES (97, 1, 97);
INSERT INTO `_perfiles_acciones` (id_perfil_accion, id_perfil, id_accion) VALUES (98, 1, 98);
INSERT INTO `_perfiles_acciones` (id_perfil_accion, id_perfil, id_accion) VALUES (99, 1, 99);
INSERT INTO `_perfiles_acciones` (id_perfil_accion, id_perfil, id_accion) VALUES (100, 1, 100);
INSERT INTO `_perfiles_acciones` (id_perfil_accion, id_perfil, id_accion) VALUES (101, 1, 101);
INSERT INTO `_perfiles_acciones` (id_perfil_accion, id_perfil, id_accion) VALUES (102, 1, 102);
INSERT INTO `_perfiles_acciones` (id_perfil_accion, id_perfil, id_accion) VALUES (103, 1, 103);
INSERT INTO `_perfiles_acciones` (id_perfil_accion, id_perfil, id_accion) VALUES (104, 1, 104);
INSERT INTO `_perfiles_acciones` (id_perfil_accion, id_perfil, id_accion) VALUES (105, 1, 105);
INSERT INTO `_perfiles_acciones` (id_perfil_accion, id_perfil, id_accion) VALUES (106, 1, 106);
INSERT INTO `_perfiles_acciones` (id_perfil_accion, id_perfil, id_accion) VALUES (107, 1, 107);
INSERT INTO `_perfiles_acciones` (id_perfil_accion, id_perfil, id_accion) VALUES (108, 1, 108);
INSERT INTO `_perfiles_acciones` (id_perfil_accion, id_perfil, id_accion) VALUES (109, 1, 109);
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