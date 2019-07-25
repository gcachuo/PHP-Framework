/**
 * Created by Memo on 21/feb/2017.
 */

$(function () {
    $("input").keypress(function (event) {
        if (event.which === 13) {
            event.preventDefault();
            btnIniciarSesion();
        }
    });
});

function btnIniciarSesion() {
    $.ajaxSetup({cache: false});
    $.get('getsession.php', function (data) {
        const session_id = data;
        request('iniciarSesion', {session_id}, 'login');
    });
}

function iniciarSesion(data) {
    if (data.cambiarPass) {
        aside("miperfil", "miperfil");
    } else
        navegar('inicio');
}