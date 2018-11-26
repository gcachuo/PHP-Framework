<?php
/**
 * Created by PhpStorm.
 * User: Cachu
 * Date: 21/feb/2017
 * Time: 04:27 PM
 */

/**
 * @property ModeloLogin $modelo
 * @property string title enviarCorreo()
 * @property string message enviarCorreo()
 * @property string part_one
 * @property string part_two
 * @property string part_three
 * @property string part_four
 * @property string help
 */
class Login extends Control
{
    private $password;

    function registrarNuevoCliente()
    {
        /**
         * @var $nombre
         * @var $apellidoP
         * @var $apellidoM
         * @var $telefono
         * @var $usuario
         * @var $password
         * @var $repassword
         * @var $reseller
         */
        extract($_POST);
        if ($password != $repassword) Globales::mensaje_error("Las contraseñas no coinciden");
        $dist = false;
        if (empty($password)) {
            $dist = true;
            $password = bin2hex(openssl_random_pseudo_bytes(4));
            $this->password = $password;
        };
        $password = Globales::crypt_blowfish_bydinvaders($password);
        unset($repassword);

        $exTel = explode(")", $telefono);
        $tel = trim($exTel[0], "(");
        #$tel = str_replace("-", "", $exTel[1]);
        $reseller = $reseller == "" ? null : $reseller;

        if ($this->modelo->correoExistente($usuario))
            Globales::mensaje_error("El correo ya esta registrado. Ingrese otro.");

        if (!is_null($reseller)) {
            $idDistribuidor = $this->modelo->distribuidor->selectIdDistribuidorFromToken($reseller);
            if (is_null($idDistribuidor))
                Globales::mensaje_error("Codigo Promocional Invalido. Ingrese Otro.");
        }
        $this->modelo->registrarCliente($nombre, $apellidoP, $apellidoM, "", $tel, $usuario, $token, $idDistribuidor);

        $this->modelo->crearDatabase($token);

        $this->modelo->registrarUsuario($token, "$nombre $apellidoP", $usuario, $password, (int)$dist);

        $this->enviarCorreoBienvenida();

        unset($this->password);
        if ($dist) unset($_SESSION[usuario]);
    }

    /**
     *
     */
    function enviarCorreoBienvenida()
    {
        if ($this->password != "")
            $password = $this->password;
        else {
            $password = "<i>oculta</i>";
        }

        $to = $_POST[usuario];
        $name = $_POST[nombre] . " " . $_POST[apellidoP];
        $subject = "Bienvenida";

        $extra = array("password" => $password);

        $send = new mail("bienvenida", $to, $name, $subject, $extra);
        $send->send_mail($errorInfo);

        if (!$send) {
            Globales::mensaje_error("No Enviado. " . $errorInfo);
        }
    }

    function enviarPassword()
    {
        if (empty($_POST['email']))
            Globales::mensaje_error("El campo de correo no puede estar vacio");

        Globales::setNamespace("distribuidor");
        $_SESSION['token'] = $this->modelo->cbiz_cliente->selectTokenFromUser($_POST['email']);
        Globales::setNamespace("");
        $usuario = $this->modelo->usuarios->selectUsuarioFromLogin($_POST['email']);
        $password = bin2hex(openssl_random_pseudo_bytes(4));
        $extra = array("password" => $password);

        $password = Globales::crypt_blowfish_bydinvaders($password);
        $this->modelo->editarUsuario($_POST[email], $password);
        $this->modelo->solicitarCambioPass($_POST[email]);

        $send = new mail("password", $_POST[email], $usuario->nombreUsuario, 'Nueva Contraseña', $extra);
        $send->send_mail($errorInfo);

        if (!$send) {
            Globales::mensaje_error("No Enviado. " . $errorInfo);
        }
    }

    protected function cargarPrincipal()
    {
        if (isset($_POST["app"]) and isset($_POST["usuario"]) and isset($_POST["password"])) {
            try {
                $result = $this->iniciarSesion();
                if ($result) {
                    $_SESSION["modulo"] = "inicio";
                    $_SESSION["app"] = true;
                    header('Location: ' . strtok($_SERVER['REQUEST_URI'], '?'));
                }
            } catch (Exception $ex) {
                return "False";
            }
        } else {
            #Cierra la sesión del usuario si carga la pantalla de Login
            $this->cerrarSesion();
        }
    }

    function iniciarSesion()
    {
        /**
         * @var $usuario
         * @var $password
         */
        extract($_POST);
            if ($usuario == "" or $password == "") Globales::mensaje_error("Ingrese usuario o contraseña");

        if (strpos($usuario, ':') != false) {
            $explode = explode(':', $usuario);
            $usuario = $explode[1];
            $internal = $explode[0];
        }

        unset($_SESSION['token']);
        Globales::setNamespace("distribuidor");
        #obtiene el token del usuario
        switch (TYPE_SYSTEM) {
            case "admin":
            case "oro":
            case "clinicas":
            case "belleza":
                $token = $this->modelo->obtenerToken($usuario);
                break;
            default:
                $token = "";
                break;
        }

        $tipo = $this->modelo->obtenerTipoSistema($token);
        $estatus = (bool)$this->modelo->obtenerEstatusSistema($token);
        if($token != "") {
            if (TYPE_SYSTEM != $tipo)
                Globales::mensaje_error('Los datos son incorrectos. Verifique la información.');
            if (!$estatus)
                Globales::mensaje_error('Sistema deshabilitado. Consulte al administrador.');
        }

        Globales::setNamespace("");
        $usuario = isset($internal) ? $internal : $usuario;
        #Obtiene el registro del usuario con la funcion en el modelo
        $usuario = $this->modelo->usuarios->selectUsuario($usuario);

        #Encripta la contraseña antes de mandarla al modelo
        $password = password_verify($_POST["password"], $usuario->pass);
        unset($_POST['password']);
        unset($_REQUEST);

        $cambiarPass = (bool)$usuario->idUserCreate;

        #Si el usuario existe llena la variable de usuario en sesión con el id del usuario
        if ($password) {
            $_SESSION['usuario'] = $usuario->idUsuario;
            $_SESSION['perfil'] = $usuario->idPerfil;
            $_SESSION['sucursal'] = $usuario->idSucursal;
        } else Globales::mensaje_error("Los datos son incorrectos. Verifique la información.");

        return compact("cambiarPass", "usuario", "token");
    }

    function cerrarSesion()
    {
        $sesion = $_SESSION;
        unset($_SESSION['usuario']);
        unset($_SESSION['perfil']);
        unset($_SESSION['conexion']);
        unset($_SESSION['app']);
        Modelo::clearToken();
        return compact("sesion");
    }

    protected function cargarAside()
    {

    }
}