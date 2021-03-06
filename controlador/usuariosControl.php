<?php

/**
 * Created by PhpStorm.
 * User: Cachu
 * Date: 23/feb/2017
 * Time: 02:17 PM
 */

/**
 * @property ModeloUsuarios modelo
 */
class Usuarios extends Control
{
    public $usuario;
    protected $tablaUsuarios, $listaPerfiles, $listaReps;

    function registrarUsuario()
    {
        $id = $_POST['id'];
        $nombre = $_POST['nombre'];
        $usuario = $_POST['usuario'];
        $password = $_POST['password'];
        $repass = $_POST['repass'];
        $representative = $_POST['reps'];

        if ($nombre == "") Globales::mensaje_error("Ingrese un nombre");
        if ($usuario == "") Globales::mensaje_error("Ingrese un usuario");
        if (strpos($usuario, " ") !== false) Globales::mensaje_error("El usuario no debe contener espacios");
        if ($password == "") Globales::mensaje_error("Ingrese una contraseña");
        if ($repass != $password) Globales::mensaje_error("Las contraseñas no coinciden");
        $password = Globales::crypt_blowfish_bydinvaders($password);
        $this->modelo->registrarUsuario($nombre, $usuario, $password, $_POST['email'], $_POST['perfil'], $id,$representative);
    }

    function eliminarUsuario()
    {
        $this->modelo->eliminarUsuario($_POST["idUsuario"]);
    }

    protected function cargarPrincipal()
    {
        $this->generarTablaUsuarios();
    }

    /**
     * @return string
     */
    function generarTablaUsuarios()
    {
        $tablaUsuarios = "";
        $usuarios = $this->modelo->usuarios->selectRegistrosUsuarios();

        foreach ($usuarios as $usuario) {
            /**
             * @var $id
             * @var $nombre
             * @var $login
             * @var $rep
             * @var $perfil
             */
            extract($usuario);

            if ($usuario['id'] != $_SESSION['usuario']) {
                $btnEditar = <<<HTML
<a title="{$this->idioma->btnEditar}" class="btn btn-sm btn-default" onclick="navegar('usuarios', 'nuevo', {idUsuario: $id});">
    <i class="material-icons">edit</i>
</a>
HTML;
                $btnEliminar = <<<HTML
<a title="{$this->idioma->btnEliminar}" class="btn btn-sm btn-default" onclick="btnEliminarUsuario($id);">
    <i class="material-icons">delete</i>
</a>
HTML;
            }

            $acciones = $btnEditar . $btnEliminar;
            $tablaUsuarios .= <<<HTML
<tr>
    <td>$nombre</td>
    <td>$login</td>
    <td>$rep</td>
    <td>$perfil</td>
    <td class="tdAcciones">$acciones</td>
</tr>
HTML;
        }
        $this->tablaUsuarios = $tablaUsuarios;
        return $tablaUsuarios;
    }

    protected function cargarAside()
    {
        $this->usuario = $this->modelo->usuarios->selectUsuarioFromId($_POST["idUsuario"] ?: 'null');
        $this->listaPerfiles .= $this->generarListaPerfiles();
        $this->generarListaReps();

    }

    function generarListaReps()
    {
        $reps = $this->modelo->representatives->selectRegistros();
        foreach($reps as $rep)
        {
            $selected = $this->usuario->rep == $rep['id'] ? "selected" : "";
            $this->listaReps .= <<<HTML
<option $selected value="$rep[id]">$rep[name]</option>
HTML;

        }
    }
    function generarListaPerfiles()
    {
        $listaPerfiles = "";
        if ($_SESSION['perfil'] == 0) {
            $selected = $this->usuario->perfil == 0 ? "selected" : "";
            $listaPerfiles = <<<HTML
<option $selected value="0">{$this->idioma->lblSuper}</option>
HTML;
        }
        $perfiles = $this->modelo->perfiles->selectPerfiles();
        foreach ($perfiles as $perfil) {
            $selected = $this->usuario->perfil == $perfil['idPerfil'] ? "selected" : "";
            $listaPerfiles .= <<<HTML
<option $selected value="$perfil[idPerfil]">$perfil[nombrePerfil]</option>
HTML;
        }
        return $listaPerfiles;
    }
}