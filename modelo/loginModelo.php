<?php

/**
 * Created by PhpStorm.
 * User: Cachu
 * Date: 23/feb/2017
 * Time: 10:45 AM
 */

/**
 * Class ModeloLogin
 * @property TablaUsuarios usuarios
 * @property \distribuidor\TablaCbiz_Cliente cbiz_cliente
 */
class ModeloLogin extends Modelo
{
    function obtenerToken(&$correo)
    {
        if (strpos($correo, "|")) {
            $explode = explode("|", $correo);
            $correo = $explode[0];
            $token = $explode[1];
        } else {
            $token = $this->cbiz_cliente->selectTokenFromUser($correo);
        }

        parent::setToken($token);
        return $token;
    }

    /**
     * @param string $token
     * @return mixed
     */
    function obtenerTipoSistema($token)
    {
        $tipo = $this->cbiz_cliente->selectTipoSistema($token);
        return strtolower($tipo);
    }

    function obtenerEstatusSistema($token)
    {
        $estatus = $this->cbiz_cliente->selectEstatusSistema($token);
        return strtolower($estatus);
    }

    function registrarCliente($nombre, $apellidoP, $apellidoM, $lada, $telefono, $correo, &$tokenCbiz, $idDistribuidor = null,$id_tipo_cbiz)
    {
        Globales::setNamespace("distribuidor");

        $idCliente = $this->cliente->insertCliente("$nombre $apellidoP $apellidoM", $lada, $telefono, date('Y-m-d'), 1, $idDistribuidor ?: 1);

        $this->contacto_cliente->insertContactoCliente($idCliente, $nombre, $apellidoP, $apellidoM, $lada . $telefono, $correo);

        $estatus = false;
        $tokenCbiz = null;
        $num = 1;
        while (!$estatus) {
            $tokenCbiz = $this->generarToken($nombre, $apellidoP, $apellidoM, $num, $estatus);
            $num++;
        }

        $this->cbiz_cliente->insertarCbizCliente($idCliente, $tokenCbiz, date('Y-m-d'), date('Y-m-d'), 0, date('Y-m-d'), date('Y-m-d'), 1,$id_tipo_cbiz);

    }

    function generarToken($nombre, $apellidoP, $apellidoM, $num, &$estatus)
    {
        $token = mb_strtoupper(substr($nombre, 0, 1) . substr($apellidoP, 0, 1) . substr($apellidoM, 0, 1)) . str_pad($num, 3, '0', STR_PAD_LEFT);
        $estatus = !boolval($this->cbiz_cliente->selectTokenExistente($token));
        return $token;
    }

    function registrarUsuario($token, $nombre, $email, $password, $reseller)
    {
        Globales::setNamespace("");
        parent::setToken($token);
        $_SESSION[usuario] = $this->usuarios->insertUsuario($nombre, $email, $password, $email, 1, $reseller);
    }

    function crearDatabase($token)
    {
        $ruta = HTTP_PATH_ROOT . "modelo/database/e11_cbizcontrol.php";
        include_once $ruta;
        $db = new distribuidor\DbCbizControl();
        $db->createNewDatabase($token);
    }

    function correoExistente($correo)
    {
        Globales::setNamespace("distribuidor");
        $existe = boolval($this->contacto_cliente->selectCountCorreo($correo));
        return $existe;
    }

    function editarUsuario($email, $password)
    {
        $usuario = $this->usuarios->selectUsuarioFromLogin($email);

        $this->usuarios->insertUsuario($usuario->nombre, $email, $password, $email, $usuario->perfil ?: 1, $usuario->id ?: 'null');
    }

    function solicitarCambioPass($email)
    {
        $usuario = $this->usuarios->selectUsuarioFromLogin($email);

        $this->usuarios->updateIdUserCreate($usuario->id, 1);
    }
}