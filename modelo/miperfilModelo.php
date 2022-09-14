<?php
/**
 * Created by PhpStorm.
 * User: Cachu
 * Date: 27/abr/2017
 * Time: 05:03 PM
 */

/**
 * @property TablaSucursales sucursales
 */
class ModeloMiPerfil extends Modelo
{
    function obtenerCorreo($idUsuario)
    {
        $usuario = $this->usuarios->selectUsuarioFromId($idUsuario);
        return $usuario->correo;
    }

    function obtenerPass($idUsuario)
    {
        $usuario = $this->usuarios->selectUsuarioFromId($idUsuario);
        return $usuario->pass;
    }

    function obtenerAgente($idUsuario)
    {
        $correo = $this->obtenerCorreo($idUsuario);
        Globales::setNamespace("distribuidor");
        $idCliente = $this->contacto_cliente->selectIdClienteFromCorreo($correo);
        $idDistribuidor = $this->cliente->selectIdDistribuidorFromId($idCliente);
        $agente = $this->distribuidor->selectDistribuidorFromId($idDistribuidor);
        Globales::setNamespace("");
        return $agente;
    }

    function editarUsuario($idUsuario, $email, $password, $sucursal)
    {
        $usuario = $this->usuarios->selectUsuarioFromId($idUsuario);
        $sucursal = $this->obteneridSucursales($sucursal);
        $this->usuarios->updateUsuario($idUsuario, $usuario->nombre, $usuario->login, $email, $usuario->perfil, $password, $sucursal);
    }

    function obteneridSucursales($idSucursal)
    {
        if($idSucursal == null)
            return null;
        $idSucursal = $this->sucursales->selectSucursalesExistente($idSucursal)
            ?($this->sucursales->selectSucursalFromNombre($idSucursal)->id ?: $idSucursal)
            : $this->sucursales->insert_sucursal($idSucursal);

        return $idSucursal;
    }
}