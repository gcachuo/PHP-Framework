/**
 * Created by gcach on 23/mar/2017.
 */

function btnGuardar(id) {
    ajax('registrarUsuario', {id: id});
}

function registrarUsuario() {
    navegar('usuarios');
}