/**
 * Created by Memo on 23/feb/2017.
 */
let idioma;
$(async function () {
    idioma = await ajax('obtenerIdioma');
    cargarDatatable(idioma.datatable, null, null, -1);
});

function btnEliminarUsuario(idUser) {
    const usuarios = idioma.usuarios;
    if (confirm(usuarios.alertEliminar)) {
        ajax('eliminarUsuario', {idUsuario: idUser});
    }
}

function eliminarUsuario() {
    navegar('usuarios');
}
