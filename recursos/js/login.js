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
    $.ajaxSetup({
        beforeSend: function (jqXHR, settings) {
            if (settings.url.indexOf('http') === -1) {
                settings.url = 'index.php/' + settings.url;
            }
        }
    });
    ajax('iniciarSesion', undefined, 'login');
}

function iniciarSesion(result) {
    if (result.cambiarPass) {
        aside("miperfil", "miperfil");
    } else
        navegar('inicio');
}
