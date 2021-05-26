<?php

/**
 * Created by PhpStorm.
 * User: Cachu
 * Date: 27/feb/2017
 * Time: 05:10 PM
 */

/**
 * @property TablaEquipos equipos
 * @property TablaModulos modulos
 * @property TablaUsuarios usuarios
 * @property TablaPerfiles_Acciones perfiles_acciones
 * @property TablaAcciones acciones
 * @property TablaCiudades ciudades
 * @property TablaCliente cliente
 * @property TablaTipo_Cbiz tipo_cbiz
 * @property TablaDistribuidor distribuidor
 * @property TablaEstados estados
 * @property TablaPeriodo periodo
 */
class ModeloControl extends Modelo
{
    /**
     * @param $padre
     * @return object
     * @throws Exception
     */
    function obtenerModulos($padre)
    {
        $objectModulos = (object)array();
        $modulos = $this->modulos->selectModulos($_SESSION['usuario'], $padre);
        foreach ($modulos as $modulo) {
            $objectModulos->{$modulo["idModulo"]} = $modulo;
        }
        return $objectModulos;
    }

    /**
     * @return array
     */
    function obtenerPermisosModulo(): array
    {
        $perfil = $this->usuarios->selectPerfil($_SESSION['usuario'] ?? null);
        if ($perfil == 0) {
            $acciones = $this->acciones->selectAccionesModulo($_SESSION['modulo']);
        }
        else {
            $acciones = $this->perfiles_acciones->selectAccionesFromModuloAndPerfil($_SESSION['modulo'], $perfil);
        }

        $origlength = array_count_values($acciones);
        foreach ($acciones as $accion => $value) {
            $decoded = htmlentities($accion);
            $acciones[$decoded] = $acciones[$accion];
            if (array_count_values($acciones) > $origlength) {
                unset($acciones[$accion]);
            }
        }

        return $acciones;
    }

    function obtenerNombreModulo($idModulo)
    {
        return $this->modulos->selectNombreModuloFromId($idModulo);
    }

    function obtenerEstados()
    {
        return $this->estados->selectEstados();
    }

    function obtenerCiudades($idEstado)
    {
        return $this->ciudades->selectCiudadesFromEstado($idEstado);
    }

    function obtenerDiasRestantes($idUsuario)
    {
        $usuario = $this->usuarios->selectUsuarioFromId($idUsuario);
        Globales::setNamespace("distribuidor");
        $idCliente = $this->cbiz_cliente->selectIdClienteFromToken($_SESSION['token']);
        $estatus = (bool)$this->suscripciones->selectEstatusSuscripcion($idCliente);
        if ($estatus) {
            Globales::setNamespace("");
            return -1;
        }
        $fecha = $this->cbiz_cliente->selectFechaCreacion($idCliente);
        Globales::setNamespace("");

        $today = date_create(date("Y-m-d"));
        $start = date_create($fecha);
        date_add($start, date_interval_create_from_date_string('5 days'));
        $intervalo = date_diff($start, $today);
        $dias = $intervalo->days;
        if ($today > $start) {
            $dias = 0;
        }
        return $dias;
    }
}
