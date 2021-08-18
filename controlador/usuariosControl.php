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
    protected $tablaUsuarios, $listaPerfiles, $usuario, $listaEspecialistas, $tutor;

    function registrarUsuario()
    {
        Globales::check_value_empty($_POST, ['nombre', 'usuario', 'password']);
        [
            'id' => $id,
            'nombre' => $nombre,
            'usuario' => $usuario,
            'password' => $password,
            'repass' => $repass,
        ] = $_POST;

        if (strpos($usuario, " ") !== false) {
            Globales::mensaje_error("El usuario no debe contener espacios");
        }
        if ($repass != $password) {
            Globales::mensaje_error("Las contraseñas no coinciden");
        }

        $password = Globales::crypt_blowfish_bydinvaders($password);
        $this->modelo->registrarUsuario($nombre, $usuario, $password, $_POST['email'], $_POST['perfil'], $id, $_POST['especialista']);
    }

    function eliminarUsuario()
    {
        $this->modelo->eliminarUsuario($_POST["idUsuario"]);
    }

    protected function cargarPrincipal()
    {
        unset($_SESSION['id']);
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
             * @var $perfil
             * @var $especialista
             */
            extract($usuario);

            $btnEditar = <<<HTML
<a title="{$this->idioma->btnEditar}" class="btn btn-sm btn-default" onclick="navegar('usuarios', 'nuevo', {idUsuario: $usuario[id]});">
    <i class="material-icons">edit</i>
</a>
HTML;
            $btnEliminar = <<<HTML
<a title="{$this->idioma->btnEliminar}" class="btn btn-sm btn-default" onclick="btnEliminarUsuario($usuario[id]);">
    <i class="material-icons">delete</i>
</a>
HTML;


            $acciones = $btnEditar . $btnEliminar;
            $tablaUsuarios .= <<<HTML
<tr>
    <td>$nombre</td>
    <td>$login</td>
    <td>$perfil</td>
    <td>$especialista</td>
    <td class="tdAcciones">$acciones</td>
</tr>
HTML;
        }
        $this->tablaUsuarios = $tablaUsuarios;
        return $tablaUsuarios;
    }

    protected function cargarAside()
    {
        if (isset($_POST['tutor'])) {
            $this->tutor = true;
        }
        if ($_POST['idUsuario'] ?? $_SESSION['id'] ?? null) {
            $_SESSION['id'] = $_POST['idUsuario'] ?? $_SESSION['id'];
            $this->usuario = $this->modelo->usuarios->selectUsuarioFromId($_SESSION["id"] ?? 'null');
        }

        $this->listaPerfiles .= $this->generarListaPerfiles();
        $this->generarListasEspecialistas();
    }

    function generarListasEspecialistas()
    {
        $especialistas = $this->modelo->especialistas->selectEspecialistas();
        foreach ($especialistas as $especialista) {
            $selected = $this->usuario->idEspecialista == $especialista['id'] ? "selected" : "";
            $this->listaEspecialistas .= <<<HTML
<option $selected  value="$especialista[id]">$especialista[nombre]</option>
HTML;

        }
    }

    function generarListaPerfiles()
    {
        $listaPerfiles = "";
        /*if ($_SESSION['perfil'] == 0) {
            $selected = $this->usuario->perfil == 0 ? "selected" : "";
            $listaPerfiles = <<<HTML
<option $selected value="0">{$this->idioma->lblSuper}</option>
HTML;
        }*/

        $perfiles = $this->modelo->perfiles->selectPerfiles();
        if (isset($_POST['tutor']))
            $this->usuario->perfil = 3;
        foreach ($perfiles as $perfil) {
            $selected = ($this->usuario->perfil ?? null) == $perfil['idPerfil'] ? "selected" : "";
            $listaPerfiles .= <<<HTML
<option $selected value="$perfil[idPerfil]">$perfil[nombrePerfil]</option>
HTML;
        }
        return $listaPerfiles;
    }
}
