<?php

/**
 * @property CareTrack\ModeloAdmin modelo
 */
class Admin extends \Control
{
    protected function cargarAside()
    {
        // TODO: Implement cargarAside() method.
    }

    protected function cargarPrincipal()
    {
        // TODO: Implement cargarPrincipal() method.
    }

    function getErrors()
    {
        $codes = [200];
        $draw = $_POST['draw'];
        $data = $this->modelo->errores->selectErrores($codes, ['length' => $_POST['length'], 'page' => $_POST['start'], 'search' => $_POST['search']['value']]);
        $recordsTotal = sizeof($this->modelo->errores->selectErrores($codes, ['search' => $_POST['search']['value']]));
        $recordsFiltered = $recordsTotal;

        return compact('draw', 'recordsTotal', 'recordsFiltered', 'data');
    }

    function getLog()
    {
        $draw = $_POST['draw'];

        $data = $this->modelo->admin_log->selectLog(['limit' => $_POST['length'], 'page' => $_POST['start'], 'search' => $_POST['search']['value']]);
        $recordsTotal = sizeof($this->modelo->admin_log->selectLog(['search' => $_POST['search']['value']]));
        $recordsFiltered = $recordsTotal;

        return compact('draw', 'recordsTotal', 'recordsFiltered', 'data');
    }

    function completeError()
    {
        $this->modelo->errores->clearErrores([$_POST['id_error']]);
        return true;
    }

    function detalleError()
    {
        $error = $this->modelo->errores->selectError($_POST['id_error']);
        return compact('error');
    }
}