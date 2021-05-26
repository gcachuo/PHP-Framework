/**
 * Created by Memo on 08/mar/2017.
 */

$(function () {
    $.ajaxSetup({
        beforeSend: function (jqXHR, settings) {
            settings.url = 'index.php/' + settings.url;
        }
    });
    ajax('obtenerIdioma');
});

function obtenerIdioma(idioma) {
    cargarDatatable(idioma.datatable);
}

function btnEliminar(id) {
    if (confirm("¿Esta seguro de eliminar este perfil?")) {
        ajax('eliminarPerfil', {idPerfil: id});
    }
}

function eliminarPerfil() {
    navegar('perfiles');
}
