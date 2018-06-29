/**
 * Created by Memo on 20/feb/2017.
 */
var getVars;
$(function () {
    window.onbeforeunload = function (e) {
        $(".loader").show();
    };
    $("#loader").attr("disabled", false);
    $(".loader").hide();
    $("[ui-nav] a").click(function (e) {
        var $this = $(e.target), $active, $li;
        $this.is('a') || ($this = $this.closest('a'));

        $li = $this.parent();
        $active = $li.siblings(".active");
        $li.toggleClass('active');
        $active.removeClass('active');
    });
    cargarDropdown();
    Dropzone.autoDiscover = false;
    $("#selectEstado").change(function () {
        $("#selectCiudad").attr("disabled", true);
        ajax('buildListaCiudades');
    });
    if ($("#floatingMenu").length !== 0)
        if ($("#floatingMenu").html().trim() !== "") {
            $(".floatingButton").show();
        }
    $(".floatingButton").click(function () {
        $(this).toggleClass("open");
        $(".quickMenu").toggleClass('hidden').find(".btn-icon").toggleClass("open");
        $(".quickMenu").find(".label").toggleClass("open");
    });
    $(".quickMenu").find(".btn-icon").click(function () {
        $(".floatingButton").toggleClass("open");
        $(".quickMenu").toggleClass('hidden').find(".btn-icon").toggleClass("open");
        $(".quickMenu").find(".label").toggleClass("open");
    });
    cargarSwitchery();
    if (getVars)
        if (getVars.tryit) {
            aside("login", "registro");
        }
    $("#btnCerrarAside").click(function () {
        if ("1" === $("#txtGuardado").val()) {
            location.reload(true);
        }
    });
    $("select").change(function () {
        if ($(this).val() === "new") {
            new Function($(this).data('new'))();
            $(this).val(0);
        }
    });
    $("select.filtro").change(function () {
        if (typeof filtrarTabla === "function")
            filtrarTabla();
        else console.log("No existe la funcion 'filtrarTabla' para este modulo");
    });
});

function btnSearch(event, modulo) {
    if (event.which === 13) {
        event.preventDefault();
        navegar(modulo, null, {
            navSearch: $("#navSearch").val()
        });
    }
}

function cargarDropdown() {
    $(".dropdown > a").off('click').click(function (e) {
        e.preventDefault();
        e.stopPropagation();
        $(".dropdown").not($(this).parent()).removeClass('open');
        $(this).parent().toggleClass('open');
    });
    $(".dropdown > .dropdown-menu").off('click').click(function (e) {
        e.preventDefault();
        e.stopPropagation();
    });
    $(window).off('click').click(function () {
        $(".dropdown").removeClass('open');
    });
}

function cargarAcordeon() {
    $(".wizard > .box > a").click(function () {
        /*$(this).children("span").addClass("hidden");
        $(this).parent().next().find("a span").removeClass("hidden");*/
        $(this).siblings(".collapse").collapse('show')
            .parent().siblings().children(".collapse").collapse('hide');
    });
}

function showMessage(message, color) {
    $("#messages").show();
    $("#messages").find("li").html(message);
    $("#messages").addClass("fadeInDown").addClass(color);
    var interval = setInterval(function () {
        $("#messages").removeClass("fadeInDown");
        $("#messages").addClass("fadeOutUp");
        clearInterval(interval);
        interval = setInterval(function () {
            $("#messages").removeClass("fadeOutUp");
            $("#messages").hide();
            clearInterval(interval);
        }, 1000);
    }, 2000);
}

function btnCopiarTexto(inputId) {
    /* Get the text field */
    var copyText = document.getElementById(inputId);

    /* Select the text field */
    copyText.select();

    /* Copy the text inside the text field */
    document.execCommand("Copy");
}


function ajax(fn, post, modulo) {
    var formValido = false;
    if ($("#txtAside").val() === 0) formValido = validarFormulario($('#frmSistema'));
    else formValido = validarFormulario($('#frmAside'));
    if (formValido) {
        $("a.btn").addClass("disabled");
        $.post("index.php",
            {
                fn: fn,
                form: $("#frmSistema").serialize(),
                aside: $("#frmAside").serialize(),
                post: post,
                modulo: modulo
            },
            function () {
            }
            , 'json'
        ).done(function (result) {
                if (typeof result !== 'string') {
                    if (typeof window[fn] !== 'undefined' && typeof window[fn] === 'function')
                        window[fn](result);
                }
                else {
                    alert(result);
                    console.error(result);
                }
            }
        ).fail(function (result) {
                alert(result.responseText);
                console.error(result.responseText);
            }
        ).always(function (result) {
            $("a.btn").removeClass("disabled");
            $(".loader").hide();
        });
    }
}

function navegar_externo(modulo, accion, post) {

    var form = document.createElement("form");
    form.setAttribute("method", "post");

    var field = document.createElement("input");
    field.setAttribute("type", "text");
    field.setAttribute("name", "post");
    form.setAttribute("target", 'view');
    form.setAttribute("action", location.href + 'vista/' + modulo + '/' + accion + '.php');
    field.setAttribute("value", JSON.stringify(post));
    form.appendChild(field);
    document.body.appendChild(form);
    window.open('', 'view', '_blank', 'height=700,width=800,scrollTo,resizable=1,scrollbars=1,location=0');
    //window.open(location.href+'vista/'+modulo+'/'+accion+'.php','_blank','height=700,width=800,scrollTo,resizable=1,scrollbars=1,location=0');


    form.submit();
}

function navegar(modulo, accion, post) {
    $(".loader").show();
    if (accion != null) accion = modulo + "/" + accion;
    $.post(
        "index.php",
        {
            vista: modulo,
            accion: accion,
            post: post
        },
        function () {
            location.reload(true);
        }
    );
}

function aside(modulo, accion, post) {
    $("#txtAside").val(1);
    $("#rightBar").modal();
    $("#rightBarContent").html("<div class='loading'></div>");
    $.post(
        "index.php?aside=1",
        {
            asideModulo: modulo,
            asideAccion: accion,
            form: $("#frmSistema").serialize(),
            post: post
        },
        function (result) {
            $("#rightBarContent").html(result);
            var fn = "aside" + modulo + accion;
            if (typeof window[fn] === "function") {
                window[fn]();
            }
        }
    );
}

function cerrarAside() {
    $("#txtAside").val(0);
    $("#rightBar").modal('hide');
    $("#rightBarContent").html("");
}

function cargarDatatableChilds(table, fn) {
    $("table").off('click', 'tr').on('click', 'tr', function () {
        //debugger;
        //var tr = $(this).closest('tr');
        var id = $(this).attr('id');
        var row = table.row(this);

        if (row.child.isShown()) {
            // This row is already open - close it
            row.child.hide();
        } else {
            // Open this row
            row.child('<div class="table-responsive"><table class="table table-striped"><tbody id="child-' + id + '"><tr><td>Loading...</td></tr></tbody></table></div>').show();
            ajax(fn, {id: id});
        }
    });
}

function cargarDatatable(idioma, columnDefs, buttons, order, orderby, element) {
    try {
        var dropdown = $("td .dropdown-menu");
        if (dropdown.length !== 0) {
            $.each(dropdown, function (index, value) {
                if ($(value).html().trim() === "") {
                    $(value).parent().html("");
                }
            });
        }
        var btns = buttons ? buttons : [];
        var bSort = order !== -1;
        order = (order && order !== -1) ? order : 0;
        orderby = (orderby && order !== -1) ? orderby : 0;
        var options = {
            "bPaginate": true,
            "bDestroy": true,
            "iDisplayLength": -1,
            "bLengthChange": false,
            "bSort": bSort,
            "order": [[order, orderby]],
            "responsive": {
                details: {
                    type: 'column',
                    target: 'tr',
                    renderer: function (api, rowIdx, columns) {
                        var data = $.map(columns, function (col, i) {
                            return col.hidden ?
                                '<div class="row child"><div class="col-xs-12"><div class="form-group">' + col.data + '</div></div></div>' :
                                '';
                        }).join('');

                        return data ?
                            $('<table/>').append(data) :
                            false;
                    }
                }
            },
            "columnDefs": columnDefs,
            "dom": "<'row'<'col-xs-2'B><'col-xs-10'f>><'row'<'col-xs-12't>><'row'<'col-xs-12'p>>" /*'fBrtip'*/,
            "buttons": btns,
            "oClasses": {
                "sFilterInput": "form-control form-control-sm p-x b-a",
                "sPageButton": "btn btn-sm btn-default",
                "sLengthSelect": "btn white btn-sm dropdown-toggle"
            },
            "language": {
                "paginate": {
                    "next": idioma.next,
                    "previous": idioma.previous
                },
                "search": "",
                "sSearchPlaceholder": idioma.sSearchPlaceholder,
                "sLengthMenu": idioma.sLengthMenu,
                "sInfoEmpty": idioma.sInfoEmpty,
                "sInfo": idioma.sInfo,
                "emptyTable": idioma.sEmptyTable
            },
            initComplete: function () {
                this.api().columns('.select-filter').every(function () {
                    var column = this;
                    var select = $('<select style="width:80px;"><option value=""></option></select>')
                        .appendTo($(column.footer()).empty())
                        .on('change', function () {
                            var val = $.fn.dataTable.util.escapeRegex(
                                $(this).val()
                            );
                            column
                                .search(val ? '^' + val + '$' : '', true, false)
                                .draw();
                        });
                    column.data().unique().sort().each(function (d, j) {
                        select.append('<option value="' + d + '">' + d + '</option>')
                    });
                })
            }
        };
        return loadDT(options, element);
    }
    catch (e) {
        console.error(e);
    }
}

function loadDT(options, element) {
    if (element === undefined)
        element = $("table");

    return element
        .on('destroyDT', function (event) {
            $(this).DataTable().destroy();
        })
        .on('reloadDT', function (event) {
            cargarDropdown();
            loadDT(options, $(this));
        }).css("width", "100%").DataTable(options);
}

function btnLimpiarFiltros() {
    //$("form")[0].reset();
    $(".header select").val(0).trigger('change');
}

function cargarDropzone(idioma, modulo, nombre) {
    try {
        Dropzone.autoDiscover = false;
        var url = "index.php?file=true&modulo=" + modulo + "&nombre=" + nombre;
        $(".dropzone").dropzone(
            {
                dictDefaultMessage: idioma.dictDefaultMessage,
                dictRemoveFile: idioma.dictRemoveFile,
                url: url,
                /*addRemoveLinks: true,*/
                acceptedFiles: 'image/*',
                uploadMultiple: false,
                maxFiles: 1,
                autoProcessQueue: false,
                init: function () {
                    var myDropzone = this;
                    var editedFile;
                    this.on("addedfile", function (file) {
                        try {
                            var reader = new FileReader();

                            reader.addEventListener("load", function (event) {

                                var origImg = new Image();
                                origImg.src = event.target.result;

                                origImg.addEventListener("load", function (event) {
                                    try {
                                        comp = jic.compress(origImg, 30, "jpg");
                                        editedFile = dataURItoBlob(comp.src);
                                        editedFile.lastModifiedDate = new Date();
                                        editedFile.name = file.name;
                                        editedFile.status = Dropzone.ADDED;
                                        editedFile.accepted = true;

                                        /*var origFileIndex = myDropzone.files.indexOf(file);
                                         myDropzone.files[origFileIndex] = editedFile;*/

                                        myDropzone.files.push(editedFile);
                                        myDropzone.emit('addedFile', editedFile);
                                        myDropzone.createThumbnailFromUrl(editedFile);
                                        myDropzone.emit('complete', editedFile);

                                        console.log(myDropzone.files);
                                        console.log(file);
                                        console.log(editedFile);

                                        myDropzone.enqueueFile(editedFile);

                                        file.status = Dropzone.SUCCESS;
                                        file.upload.progress = 100;
                                        file.upload.bytesSent = file.upload.total;
                                        myDropzone.emit("success", file);

                                        myDropzone.processQueue();

                                        myDropzone.emit("complete", file);
                                    }
                                    catch (e) {
                                        console.log(e);
                                        myDropzone.emit("reset");
                                    }
                                });
                            });
                            reader.readAsDataURL(file);
                        }
                        catch (e) {
                            console.log(e);
                        }
                    });
                    this.on("success", function (file, responseText) {
                        $("#txtDropzoneFile").val(responseText);
                    });
                    this.on("error", function (file, responseText) {
                        console.error(responseText);
                        alert(responseText);
                        myDropzone.emit("reset");
                        myDropzone.removeAllFiles();
                        console.log(myDropzone.files);
                    })
                    this.on("removedFile", function (file) {
                        console.log(file);
                        console.log(response);
                        console.log($("#txtDropzoneFile").val());
                    });
                }
            }
        );
    }
    catch (result) {
        if (result.responseText !== undefined) {
            alert(result.responseText);
            console.error(result.responseText);
        }
        console.log(result);
    }
}

function dataURItoBlob(dataURI) {
    // convert base64/URLEncoded data component to raw binary data held in a string
    var byteString;
    if (dataURI.split(',')[0].indexOf('base64') >= 0)
        byteString = atob(dataURI.split(',')[1]);
    else
        byteString = unescape(dataURI.split(',')[1]);

    // separate out the mime component
    var mimeString = dataURI.split(',')[0].split(':')[1].split(';')[0];

    // write the bytes of the string to a typed array
    var ia = new Uint8Array(byteString.length);
    for (var i = 0; i < byteString.length; i++) {
        ia[i] = byteString.charCodeAt(i);
    }

    return new Blob([ia], {type: mimeString});
}

function cargarNestable() {
    $("ol:empty").remove();
    $(".nestable").nestable();
    $(".dd-nodrag").on("mousedown", function (event) { // mousedown prevent nestable click
        event.preventDefault();
        return false;
    }).on("click", function (event) { // click event
        event.preventDefault();
        return false;
    });
}

function cargarSwitchery() {
    var elems = Array.prototype.slice.call(document.querySelectorAll('.js-switch'));

    elems.forEach(function (html) {
        var switchery = new Switchery(html, {size: 'small'});
    });
}

function cargarDatePicker(elemento, eStartDate, eEndDate, idioma, fn, range) {
    var startDate = moment(eStartDate.val());
    var endDate = moment(eEndDate.val());
    var date = new Date();
    var sibling = elemento.siblings(".input-group-addon").eq(0);
    var datepicker = elemento;
    var ranges = range ? false : {};

    if (sibling.length == 0) {
        if (eStartDate.val() === "") {
            startDate = moment([date.getFullYear(), date.getMonth()]);
            eStartDate.val(startDate.format('YYYY-MM-DD'));
        }
        if (eEndDate.val() === "") {
            endDate = moment(moment([date.getFullYear(), date.getMonth()])).endOf('month');
            eEndDate.val(endDate.format('YYYY-MM-DD'));
        }
    }
    else {
        datepicker = sibling;
        var frmt = (eStartDate.val() !== "" && eStartDate.val() !== "0000-00-00") ? startDate.format(idioma.format) + " - " + endDate.format(idioma.format) : "";
        elemento.val(frmt).change(function () {
            eStartDate.val("");
            eEndDate.val("");
            if (elemento.val() != "") {
                const regex = /^\d?\d\/\d?\d\/\d?\d?\d\d - \d?\d\/\d?\d\/\d?\d?\d\d$/gm;
                const str = elemento.val();
                let m;
                var found = false;
                while ((m = regex.exec(str)) !== null) {
                    // This is necessary to avoid infinite loops with zero-width matches
                    if (m.index === regex.lastIndex) {
                        regex.lastIndex++;
                    }

                    // The result can be accessed through the `m`-variable.
                    m.forEach((match, groupIndex) => {
                        found = true;
                    });
                }
                if (!found) {
                    elemento.val("");
                    alert('Formato para fecha no valido. Debe ser ' + idioma.format + ' - ' + idioma.format)
                }
                if (elemento.val().indexOf(" - ") != -1) {
                    var split = elemento.val().split(" - ");
                    eStartDate.val(moment(split[0]).format('YYYY-MM-DD'));
                    eEndDate.val(moment(split[1]).format('YYYY-MM-DD'));
                }
            }
        });
    }
    ranges[idioma.ranges.Hoy] = [moment(), moment()];
    ranges[idioma.ranges.Ayer] = [moment().subtract(1, 'days'), moment().subtract(1, 'days')];
    ranges[idioma.ranges.Siete_dias] = [moment().subtract(6, 'days'), moment()];
    ranges[idioma.ranges.Treinta_dias] = [moment().subtract(29, 'days'), moment()];
    ranges[idioma.ranges.Este_mes] = [moment([date.getFullYear(), date.getMonth()]), moment(moment([date.getFullYear(), date.getMonth()])).endOf('month')];
    ranges[idioma.ranges.Mes_pasado] = [moment([date.getFullYear(), date.getMonth()]).subtract(1, 'month'), moment().subtract(1, 'month').endOf('month')];
    ranges[idioma.ranges.Este_año] = [moment([date.getFullYear()]), moment(moment([date.getFullYear()])).endOf('year')];
    ranges[idioma.ranges.Año_pasado] = [moment([date.getFullYear() - 1]), moment(moment([date.getFullYear() - 1])).endOf('year')];
    ranges[idioma.ranges.Todos] = [moment().subtract(7, 'years').startOf('year'),
        moment().endOf('year')];

    datepicker.daterangepicker({
        ranges: ranges,
        linkedCalendars: false,
        autoApply: true,
        locale: {
            format: idioma.format,
            customRangeLabel: idioma.customRangeLabel
        }
    }, function (start, end, label) {
        eStartDate.val(start.format('YYYY-MM-DD'));
        eEndDate.val(end.format('YYYY-MM-DD'));
        if (sibling.length != 0) {
            elemento.val(start.format(idioma.format) + " - " + end.format(idioma.format));
        }
        if (elemento.prop("tagName") != "input") {
            elemento.html(label);
        }
        if (fn) window[fn]();
    }).on('cancel.daterangepicker', function (ev, picker) {
        $(this).val('');
    });
    ;
}

/**
 * @param elemento
 * @param eDate
 * @param idioma
 * @param time
 */
function cargarSingleDatePicker(elemento, eDate, idioma, time) {
    var format = idioma.format;
    var sibling = elemento.siblings(".input-group-addon");
    var datepicker = elemento;
    var fecha = eDate.val() === "" ? moment() : moment(eDate.val());
    if (time === undefined) time = false;
    if (time) format += " h:mm A";

    if (sibling.length != 0) {
        datepicker = sibling;

        fecha = eDate.val() === "" ? "" : moment(eDate.val());
        var frmt = (eDate.val() !== "" && eDate.val() !== "0000-00-00") ? fecha.format(format) : "";
        elemento.val(frmt).change(function () {
            eDate.val("");
            if (elemento.val() != "") {
                const regex = time
                    ? /^\d?\d\/\d?\d\/\d?\d?\d\d\s\d?\d:\d\d\s(A|P)M$/gm
                    : /^\d?\d\/\d?\d\/\d?\d?\d\d$/gm;
                const str = elemento.val();
                let m;
                var found = false;
                while ((m = regex.exec(str)) !== null) {
                    // This is necessary to avoid infinite loops with zero-width matches
                    if (m.index === regex.lastIndex) {
                        regex.lastIndex++;
                    }

                    // The result can be accessed through the `m`-variable.
                    m.forEach((match, groupIndex) => {
                        found = true;
                    });
                }
                if (!found) {
                    elemento.val("");
                    alert('Formato para fecha no valido. Debe ser ' + format)
                }
                eDate.val(moment(elemento.val()).format('YYYY-MM-DD HH:mm:ss'));
            }
        });
    }
    else {
        fecha = (eDate.val() !== "" && eDate.val() !== "0000-00-00 00:00:00") ? fecha : moment();
        datepicker.val(fecha.format(format));
    }

    datepicker.daterangepicker({
        locale: {
            format: format,
            daysOfWeek: idioma.daysOfWeek,
            monthNames: idioma.monthNames
        },
        showDropdowns: true,
        singleDatePicker: true,
        timePicker: time,
        timePickerIncrement: 15
    }).on('cancel.daterangepicker', function (ev, picker) {
        $(this).val('');
    });
    datepicker.on('apply.daterangepicker', function (ev, picker) {
        if (picker === undefined)
            picker = datepicker.data('daterangepicker');

        eDate.val(picker.startDate.format('YYYY-MM-DD HH:mm:ss'));
        if (sibling.length != 0) {
            elemento.val(picker.startDate.format(format));
        }
    });
    if (sibling.length == 0) datepicker.trigger('apply.daterangepicker');
}

function cargarDoughnut(id, data, names, color) {
    var myChart = echarts.init(document.getElementById(id));
    myChart.setOption({
        tooltip: {
            transitionDuration: 0,
            showDelay: 0,
            hideDelay: 0,
            position: [0, 0],
            trigger: 'item',
            formatter: '{a} <br/>{b} : {c} ({d}%)'
        },
        legend: {
            orient: 'vertical',
            x: 'left',
            textStyle: {
                color: 'auto'
            },
            data: names
        },
        calculable: true,
        series: [
            {
                name: id,
                itemStyle: {
                    normal: {
                        label: {
                            show: false,
                            textStyle: {
                                color: 'rgba(165,165,165,1)'
                            }
                        },
                        labelLine: {
                            show: false,
                            lineStyle: {
                                color: 'rgba(165,165,165,1)'
                            }
                        },
                        color: function (params) {
                            var red = color.red + (params.dataIndex * 33);
                            var green = color.green + (params.dataIndex * 33);
                            var blue = color.blue + (params.dataIndex * 33);
                            return 'rgba(' + red + ',' + green + ',' + blue + ',1)';

                        }
                    }
                },
                type: 'pie',
                radius: ['50%', '70%'],
                data: data
            }
        ]
    });
    return myChart;
}

function cargarDoughnut2(id, data, color) {
    var myChart = echarts.init(document.getElementById(id));
    myChart.setOption({
        title: {
            x: 'center',
            y: 'center',
            itemGap: 20,
            textStyle: {
                color: 'rgba(30,144,255,0.8)',
                fontSize: 20,
                fontWeight: 'bolder'
            }
        },
        tooltip: {
            transitionDuration: 0,
            showDelay: 0,
            hideDelay: 0,
            position: [0, 0],
            show: true,
            formatter: '{a} <br/>{b} : {c} ({d}%)'
        },
        legend: {
            orient: 'vertical',
            x: $('#pie').width() / 2 + 10,
            y: 20,
            itemGap: 12,
            textStyle: {
                color: 'auto'
            },
            data: [data.name1, data.name2]
        },
        series: [
            {
                name: data.name1,
                type: 'pie',
                clockWise: false,
                radius: [50, 70],
                itemStyle: {
                    normal: {
                        label: {show: false},
                        labelLine: {show: false},
                        color: 'rgba(' + color[0].red + ',' + color[0].green + ',' + color[0].blue + ',1)'
                    }
                },
                data: [
                    {
                        value: data.value1,
                        name: data.name1
                    },
                    {
                        value: data.value2,
                        name: 'invisible',
                        itemStyle: {
                            normal: {
                                color: 'rgba(0,0,0,0)',
                                label: {show: false},
                                labelLine: {show: false}
                            },
                            emphasis: {
                                color: 'rgba(0,0,0,0)'
                            }
                        }
                    }
                ]
            },
            {
                name: data.name2,
                type: 'pie',
                clockWise: false,
                radius: [30, 50],
                itemStyle: {
                    normal: {
                        label: {show: false},
                        labelLine: {show: false},
                        color: 'rgba(' + color[1].red + ',' + color[1].green + ',' + color[1].blue + ',1)'
                    }
                },
                data: [
                    {
                        value: data.value2,
                        name: data.name2
                    },
                    {
                        value: data.value1,
                        name: 'invisible',
                        itemStyle: {
                            normal: {
                                color: 'rgba(0,0,0,0)',
                                label: {show: false},
                                labelLine: {show: false}
                            },
                            emphasis: {
                                color: 'rgba(0,0,0,0)'
                            }
                        }
                    }
                ]
            }
        ]
    });
    return myChart;
}

function cargarAutocomplete(obj, input) {
    var source = Object.keys(obj).map(function (key) {
        return obj[key];
    });
    input.autocomplete({
        source: source
    });
}

function cerrarSesion() {
    navegar('login');
}

function btnMiPerfil() {
    aside("miperfil", "miperfil");
}

function btnCuenta() {
    aside("miperfil", "cuenta");
}

function buildListaCiudades(result) {
    $("#selectCiudad").html(result.listaCiudades).attr("disabled", false);
}

function asidetransaccionesnuevo() {
    var tipo = $("#selectTipo").val();
    if (tipo !== "" && tipo !== 3 && $("input[name=idTransaccion]").val() == "") {
        ajax('buildListaCategorias', {tipo: $("#selectTipo").val()}, 'transacciones');
    }
}

function validarFormulario(form) {
    var $myForm = $(form),
        valid = $myForm[0].checkValidity();
    if (!valid) {
        $('<input type="submit">').hide().appendTo($myForm).click().remove();
    }
    return valid;
}

function compress(source_img_obj, quality, maxWidth, output_format) {
    var mime_type = "image/jpeg";
    if (typeof output_format !== "undefined" && output_format == "png") {
        mime_type = "image/png";
    }

    maxWidth = maxWidth || 1000;
    var natW = source_img_obj.naturalWidth;
    var natH = source_img_obj.naturalHeight;
    var ratio = natH / natW;
    if (natW > maxWidth) {
        natW = maxWidth;
        natH = ratio * maxWidth;
    }

    var cvs = document.createElement('canvas');
    cvs.width = natW;
    cvs.height = natH;

    var ctx = cvs.getContext("2d").drawImage(source_img_obj, 0, 0, natW, natH);
    var newImageData = cvs.toDataURL(mime_type, quality / 100);
    var result_image_obj = new Image();
    result_image_obj.src = newImageData;
    return result_image_obj;
}

function madePdf(idVenta) {
    ajax('crearCotizacionPdf', {id: idVenta}, 'cotizaciones');
}

function crearCotizacionPdf() {

}