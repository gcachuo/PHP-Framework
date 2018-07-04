/**
 * Created by Memo on 06/mar/2017.
 */

$(function () {
    cargarNestable();
    $(".dark-white").click(function () {
        var input = $(this).siblings('input');
        var checked = (!input.prop('checked'));
        var clase = input.attr('class').replace("has-value", "").trim();
        if (clase !== "")
            $('input.' + clase).each(function () {
                if ($(this).hasClass('child'))
                    $(this).prop('checked', checked);
            });
    });
});

function btnGuardar(id) {
    ajax('guardarPerfil', {id: id});
}

function guardarPerfil() {
    navegar('perfiles');
}