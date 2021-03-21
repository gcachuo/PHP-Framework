<?php

/**
 * Created by PhpStorm.
 * User: Cachu
 * Date: 27/feb/2017
 * Time: 06:21 PM
 */

/**
 * @property TablaAcciones acciones
 * @property TablaPerfiles_Acciones perfiles_acciones
 * @property TablaPerfiles perfiles
 */
class ModeloPerfiles extends Modelo
{
    function obtenerPerfiles()
    {
        return $this->perfiles->selectPerfiles();
    }

    function obtenerPerfilesAcciones($idPerfil)
    {
        $acciones = $this->perfiles_acciones->selectAccionesFromPerfil($idPerfil);
        return Globales::query2twoLevelObject($acciones);
    }

    function guardarPerfilAccion($idPerfil, $idAccion, $idModulo)
    {
        $this->perfiles_acciones->insertPerfilAccion($idPerfil, $idAccion, $idModulo);
    }

    function obtenerPerfil($idPerfil)
    {
        $nombrePerfil = $this->perfiles->selectNombrePerfilFromId($idPerfil);
        $data = (object)array("idPerfil" => $idPerfil, "nombrePerfil" => $nombrePerfil);
        return $data;
    }

    function editarNombrePerfil($idPerfil, $nombrePerfil)
    {
        $this->perfiles->updateNombrePerfil($idPerfil, $nombrePerfil);
    }

    function eliminarAccionesPerfil($idPerfil)
    {
        $this->perfiles_acciones->deleteAccionesPerfil($idPerfil);
    }

    function eliminarPerfil($idPerfil)
    {
        $this->perfiles_acciones->deleteAccionesPerfil($idPerfil);
        $this->perfiles->deletePerfil($idPerfil);
    }

    public function guardarAcciones($idPerfil, $acciones)
    {
        $this->perfiles_acciones->deleteAccionesPerfil($idPerfil);
        foreach ($acciones as $idAccion => $value) {
            $this->perfiles_acciones->insertPerfilAccion($idPerfil, $idAccion);
        }
    }
}