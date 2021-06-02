/**
 * Created by Memo on 23/feb/2017.
 */
$(function () {
    $.ajaxSetup({
        beforeSend: function (jqXHR, settings) {
            settings.url = 'index.php/' + settings.url;
        }
    });
    ajax('obtenerIdioma');
});

function obtenerIdioma(idiom) {
    idioma = idiom;
    cargarDatatable(idioma.datatable, null, null, -1);
}

function btnEliminarUsuario(idUser) {
    var usuarios = idioma.usuarios;
    if (confirm(usuarios.alertEliminar)) {
        ajax('eliminarUsuario', {idUsuario: idUser});
    }
}

function eliminarUsuario() {
    navegar('usuarios');
}
