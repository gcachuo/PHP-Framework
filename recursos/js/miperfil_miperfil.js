/**
 * Created by Memo on 27/abr/2017.
 */
$(function () {
    $("select[name=selectSucursal]").select2({
        tags: true,
        createTag: function (params) {
            return {
                id: params.term,
                text: params.term,
                newOption: true
            }
        }
    });
});
function btnGuardar() {
    if (validarFormulario($("#frmAside")))
        ajax("guardarCambios", undefined, "miperfil");
}

function guardarCambios() {
   navegar('inicio');
}