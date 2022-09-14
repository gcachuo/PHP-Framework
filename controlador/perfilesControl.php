<?php

/**
 * Created by PhpStorm.
 * User: Cachu
 * Date: 27/feb/2017
 * Time: 05:36 PM
 */

/**
 * @property ModeloPerfiles modelo
 */
class Perfiles extends Control
{
    protected
        $tablaPerfiles,
        $arbolPermisos,
        $modeloModulos,
        $modeloAcciones,
        $perfil;
    private $permisosPerfiles;

    /**
     * @throws Exception
     */
    function guardarPerfil()
    {
        $nombre = $_POST['nombre'];
        if (empty($nombre)) Globales::mensaje_error("Ingrese un nombre para el perfil.");

        $idPerfil = $this->modelo->perfiles->insertPerfil($nombre, $_SESSION["usuario"], $_POST['id'] ?: 'null');
        $this->modelo->guardarAcciones($idPerfil, $_POST['accion']);
    }

    /**
     * @deprecated usar funcion 'guardarPerfil'
     * @throws Exception
     */
    function editarPerfil()
    {
        /**
         * @var $modulos
         * @var $idPerfil
         * @var $nombre
         */
        extract($_POST);
        if ($nombre == "") Globales::mensaje_error("Ingrese un nombre para el perfil.");

        $this->modelo->editarNombrePerfil($idPerfil, $nombre);
        $this->modelo->eliminarAccionesPerfil($idPerfil);

        foreach ($modulos as $idModulo => $acciones) {
            foreach ($acciones as $idAccion => $value) {
                $this->modelo->guardarPerfilAccion($idPerfil, $idAccion, $idModulo);
            }
        }
    }

    function eliminarPerfil()
    {
        $this->modelo->eliminarPerfil($_POST["idPerfil"]);
    }

    protected function cargarPrincipal()
    {
        $this->generarTablaPerfiles();
    }

    function generarTablaPerfiles()
    {
        $idioma = $this->idioma;
        $permisos = $this->permisos->perfiles;

        $perfiles = $this->modelo->obtenerPerfiles();

        foreach ($perfiles as $perfil) {

            $btnEditar = <<<HTML
<a title="$idioma->btnEditar" class="btn btn-sm btn-default" onclick="navegar('perfiles', 'nuevo', {id: $perfil[idPerfil]});">
    <i class="material-icons">edit</i>
</a>
HTML;
            $btnEliminar = <<<HTML
<a title="$idioma->btnEliminar" class="btn btn-sm btn-default" onclick="btnEliminar($perfil[idPerfil]);">
    <i class="material-icons">delete</i>
</a>
HTML;

            $acciones = $btnEditar . $btnEliminar;

            $this->tablaPerfiles .= <<<HTML
<tr>
<td>$perfil[nombrePerfil]</td>
<td class="tdAcciones">$acciones</td>
</tr>
HTML;
        }
    }

    protected function cargarAside()
    {
        if (isset($_POST["id"])) {
            $this->permisosPerfiles = $this->modelo->perfiles_acciones->selectAccionesFromPerfil($_POST["id"]);
            $this->perfil = $this->modelo->obtenerPerfil($_POST["id"]);
        }
        $this->arbolPermisos = $this->generarArbolPermisos();
    }

    function generarArbolPermisos($padre = 0)
    {
        $arbol = "";
        $idioma = $this->idioma->modulos;

        $modulos = $this->control->modulos->selectModulosFromParent($padre);
        foreach ($modulos as $modulo) {
            $acciones = $accesar = "";
            $submodulos = $this->generarArbolPermisos($modulo['id']);
            $listaAcciones = $this->modelo->acciones->selectAccionesPerfilModulo($modulo['id']);
            if (!empty($modulo['navegarModulo'])) {
                foreach ($listaAcciones as $accion) {
                    $checked = !empty($this->permisosPerfiles[$accion['id']]) ? "checked" : "";
                    $clasePadreModulo = $modulo['padreModulo'] != 0 ? $modulo['padreModulo'] : '';
                    if ($accion['nombre'] == "accesar") {
                        $clasePadreModulo .= $modulo['padreModulo'] != 0 ? ' child' : '';
                        $accesar = <<<HTML
<span class="checkbox">
            <label class="ui-check">
                <input $checked name="accion[$accion[id]]" type="checkbox" class="$clasePadreModulo">
                <i class="dark-white"></i>
            </label>
        </span>
HTML;
                        continue;
                    }
                    $nombre = ucfirst($this->idioma->acciones->{mb_strtolower($accion['nombre'])});
                    $acciones .= <<<HTML
<li class="dd-item">
    <div class="form-control box">
        <span class="checkbox">
            <label class="ui-check">
                <input $checked name="accion[$accion[id]]" type="checkbox" class="$clasePadreModulo $modulo[id]">
                <i class="dark-white"></i>
            </label>
        </span>
        <div class="dd-nodrag">
          $nombre
        </div>
    </div>
</li>
HTML;
                }
            }
            $chkAll = "";
            if (!empty($submodulos))
                $chkAll = <<<HTML
<span class="checkbox">
            <label class="ui-check">
                <input type="checkbox" class="$modulo[id]">
                <i class="dark-white"></i>
            </label>
        </span>
HTML;

            $arbol .= <<<HTML
<li class="dd-item">
    <div class="form-control box">
        $accesar $chkAll
        <div class="dd-nodrag">
            {$idioma->{$modulo['id']}[0]}
        </div>
    </div>
    <ol class="dd-list">$acciones$submodulos</ol>
</li>
HTML;
        }
        return $arbol;
    }
}