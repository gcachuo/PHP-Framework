<?php

/**
 * Created by PhpStorm.
 * User: Cachu
 * Date: 23/feb/2017
 * Time: 06:21 PM
 */

/**
 * @property TablaUsuarios usuarios
 * @property distribuidor\TablaUsuarios _usuarios
 * @property distribuidor\TablaCbiz_Cliente cbiz_cliente
 * @property distribuidor\TablaCliente_Usuario cliente_usuario
 * @property TablaPerfiles perfiles
 */
class ModeloUsuarios extends Modelo
{
    function registrarUsuario($nombre, $login, $password, $correo, $perfil, $id)
    {
        if (empty($id)) {
            $usuario = $this->usuarios->selectUsuarioFromLogin($login);

            Globales::setNamespace("distribuidor");
            $distUsuario = $this->_usuarios->selectUsuarioFromLogin($login);
            Globales::setNamespace("");

            if ($usuario->estatus == true or $distUsuario->estatus == true)
                Globales::mensaje_error("El usuario ya existe");
        }
        $this->usuarios->insertUsuario($nombre, $login, $password, $correo, $perfil, $id,1);

        Globales::setNamespace("distribuidor");

        $idUser = $this->_usuarios->insertUsuario(4, $nombre, $login, $password, $correo);
        $idCliente = $this->cbiz_cliente->selectIdClienteFromToken($_SESSION['token']);
        $this->cliente_usuario->insertClienteUsuario($idCliente, $idUser);
        Globales::setNamespace("");
    }

    /**
     * @param $id
     */
    function eliminarUsuario($id)
    {
        $this->usuarios->updateEstatusUsuario($id);
        $usuario = $this->usuarios->selectUsuarioFromId($id);
        Globales::setNamespace("distribuidor");
        $this->_usuarios->updateEstatusUsuario($usuario->login);
        Globales::setNamespace("");
    }
}