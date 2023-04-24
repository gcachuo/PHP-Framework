/**
 * Created by Memo on 20/feb/2017.
 */
var getVars;
var marker;
var funcionTable = "";
$(function () {
  window.onbeforeunload = function (e) {
    $(".loader").show();
  };
  $("#loader").attr("disabled", false);
  $(".loader").hide();
  $("[ui-nav] a").click(function (e) {
    var $this = $(e.target),
      $active,
      $li;
    $this.is("a") || ($this = $this.closest("a"));

    $li = $this.parent();
    $active = $li.siblings(".active");
    $li.toggleClass("active");
    $active.removeClass("active");
  });
  cargarDropdown();
  Dropzone.autoDiscover = false;
  $("#selectEstado").change(function () {
    $("#selectCiudad").attr("disabled", true);
    ajax("buildListaCiudades");
  });
  if ($("#floatingMenu").length !== 0)
    if ($("#floatingMenu").html().trim() !== "") {
      $(".floatingButton").show();
    }
  $(".floatingButton").click(function () {
    $(this).toggleClass("open");
    $(".quickMenu").toggleClass("hidden").find(".btn-icon").toggleClass("open");
    $(".quickMenu").find(".label").toggleClass("open");
  });
  $(".quickMenu")
    .find(".btn-icon")
    .click(function () {
      $(".floatingButton").toggleClass("open");
      $(".quickMenu")
        .toggleClass("hidden")
        .find(".btn-icon")
        .toggleClass("open");
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
      new Function($(this).data("new"))();
      $(this).val(0);
    }
  });
  $("select.filtro").change(function () {
    if (typeof filtrarTabla === "function") filtrarTabla();
    else console.log("No existe la funcion 'filtrarTabla' para este modulo");
  });
});

function btnSearch(event, modulo) {
  if (event.which === 13) {
    event.preventDefault();
    navegar(modulo, null, {
      navSearch: $("#navSearch").val(),
    });
  }
}

function cargarDropdown() {
  $(".dropdown > a")
    .off("click")
    .click(function (e) {
      e.preventDefault();
      e.stopPropagation();
      $(".dropdown").not($(this).parent()).removeClass("open");
      $(this).parent().toggleClass("open");
    });
  $(".dropdown > .dropdown-menu")
    .off("click")
    .click(function (e) {
      e.preventDefault();
      e.stopPropagation();
    });
  $(window)
    .off("click")
    .click(function () {
      $(".dropdown").removeClass("open");
    });
}

function cargarAcordeon() {
  $(".wizard > .box > a").click(function () {
    /*$(this).children("span").addClass("hidden");
                                $(this).parent().next().find("a span").removeClass("hidden");*/
    $(this)
      .siblings(".collapse")
      .collapse("show")
      .parent()
      .siblings()
      .children(".collapse")
      .collapse("hide");
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
  $("a.btn").addClass("disabled");
  return $.post(
    `index.php/${modulo || 0}/${fn}`,
    {
      form: $("form:not(#frmAside)").serialize(),
      aside: $("#frmAside").serialize(),
      post,
      fn,
      modulo,
    },
    function () {},
    "json"
  )
    .done(function (result) {
      if (typeof result !== "string") {
        if (
          typeof window[fn] !== "undefined" &&
          typeof window[fn] === "function"
        )
          window[fn](result);
      } else {
        console.error(result);
      }
    })
    .fail(function ({ responseJSON: data }) {
      switch (data?.code) {
        case 400:
          console.warn(data.message, data);
          toastr.warning(data.message);
          break;
        case 500:
          console.error(data.message, data);
          toastr.error("Ocurrió un error. Contacte al desarrollador.");
          break;
      }
    })
    .always(function (result) {
      $("a.btn").removeClass("disabled");
      $(".loader").hide();
    });
}

function ajaxOutSite(fn, post, modulo) {
  var formValido = false;
  if ($("#txtAside").val() === 0)
    formValido = validarFormulario($("#frmSistema"));
  else formValido = validarFormulario($("#frmAside"));

  if (formValido) {
    $("a.btn").addClass("disabled");
    $.post(
      "index.php",
      {
        fn: fn,
        form: $("#frmSistema").serialize(),
        aside: $("#frmAside").serialize(),
        post: post,
        modulo: modulo,
      },
      function () {},
      "json"
    )
      .done(function (result) {
        if (typeof result !== "string") {
          if (
            typeof window[fn] !== "undefined" &&
            typeof window[fn] === "function"
          )
            window[fn](result);
        } else {
          alert(result);
          console.error(result);
        }
      })
      .fail(function (result) {
        alert(result.responseText);
        console.error(result.responseText);
      })
      .always(function (result) {
        $("a.btn").removeClass("disabled");
        $(".loader").hide();
      });
  }
}

function uploadFiles(modulo, fn) {
  var formData = new FormData();
  var inputs = $("input[type=file]");
  $.each(inputs, function (obj, v) {
    $("a.btn").addClass("disabled");
    formData.append("file", v.files[0]);
    $.ajax({
      url: `index.php?file=true&modulo=${modulo}&folder=archivos`,
      data: formData,
      type: "POST",
      contentType: false, // NEEDED, DON'T OMIT THIS (requires jQuery 1.6+)
      processData: false, // NEEDED, DON'T OMIT THIS
    })
      .done(function (result) {
        $(".loader").show();
        ajax(fn, { filename: result }, modulo);
      })
      .fail(function (result) {
        alert(result.responseText);
        console.error(result.responseText);
      })
      .always(function (result) {
        $("a.btn").removeClass("disabled");
      });
  });
}

function uploadOneFiles(modulo, fn, file) {
  var formData = new FormData();
  var inputs = $("#" + file);
  formData.append("file", inputs.files[0]);
  $.ajax({
    url: `index.php?file=true&modulo=${modulo}&folder=archivos`,
    data: formData,
    type: "POST",
    contentType: false, // NEEDED, DON'T OMIT THIS (requires jQuery 1.6+)
    processData: false, // NEEDED, DON'T OMIT THIS
  })
    .done(function (result) {
      ajax(fn, { filename: result }, modulo);
    })
    .fail(function (result) {
      alert(result.responseText);
      console.error(result.responseText);
    })
    .always(function (result) {});
}

function navegar_externo(modulo, accion, post) {
  var form = document.createElement("form");
  form.setAttribute("method", "post");

  var field = document.createElement("input");
  field.setAttribute("type", "hidden");
  field.setAttribute("name", "post");
  form.setAttribute("target", "view");
  form.setAttribute(
    "action",
    location.href + "vista/" + modulo + "/" + accion + ".php"
  );
  field.setAttribute("value", JSON.stringify(post));
  form.appendChild(field);
  document.body.appendChild(form);
  window.open(
    "",
    "view",
    "_blank",
    "height=700,width=800,scrollTo,resizable=1,scrollbars=1,location=0"
  );
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
      post: post,
    },
    function () {
      location.reload(true);
    }
  );
}

function btnDownload(path) {
  window.open(path, "_blank");
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
      post: post,
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
  $("#rightBar").modal("hide");
  $("#rightBarContent").html("");
}

function cargarDatatableChilds(table, fn) {
  $("table")
    .off("click", "tr")
    .on("click", "tr", function () {
      //var tr = $(this).closest('tr');
      var id = $(this).attr("id");
      var row = table.row(this);

      if (row.child.isShown()) {
        // This row is already open - close it
        row.child.hide();
      } else {
        // Open this row
        row
          .child(
            '<div class="table-responsive"><table class="table table-striped"><tbody id="child-' +
              id +
              '"><tr><td>Loading...</td></tr></tbody></table></div>'
          )
          .show();
        ajax(fn, { id: id });
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
    order = order && order !== -1 ? order : 0;
    orderby = orderby && order !== -1 ? orderby : 0;
    var options = {
      bPaginate: true,
      bDestroy: true,
      iDisplayLength: -1,
      bLengthChange: false,
      bSort: bSort,
      order: [[order, orderby]],
      responsive: {
        details: {
          type: "column",
          target: "tr",
          renderer: function (api, rowIdx, columns) {
            var data = $.map(columns, function (col, i) {
              return col.hidden
                ? '<div class="row child"><div class="col-xs-12"><div class="form-group">' +
                    col.data +
                    "</div></div></div>"
                : "";
            }).join("");

            return data ? $("<table/>").append(data) : false;
          },
        },
      },
      columnDefs: columnDefs,
      dom: "<'row'<'col-xs-4'B><'col-xs-8'f>><'row'<'col-xs-12't>><'row'<'col-xs-12'p>>" /*'fBrtip'*/,
      buttons: btns,
      oClasses: {
        sFilterInput: "form-control form-control-sm p-x b-a",
        sPageButton: "btn btn-sm btn-default",
        sLengthSelect: "btn white btn-sm dropdown-toggle",
      },
      language: {
        paginate: {
          next: idioma.next,
          previous: idioma.previous,
        },
        search: "",
        sSearchPlaceholder: idioma.sSearchPlaceholder,
        sLengthMenu: idioma.sLengthMenu,
        sInfoEmpty: idioma.sInfoEmpty,
        sInfo: idioma.sInfo,
        emptyTable: idioma.sEmptyTable,
      },
      initComplete: function () {
        this.api()
          .columns(".select-filter")
          .every(function () {
            var column = this;
            var select = $(
              '<select style="width:80px;"><option value=""></option></select>'
            )
              .appendTo($(column.footer()).empty())
              .on("change", function () {
                var val = $.fn.dataTable.util.escapeRegex($(this).val());
                column.search(val ? "^" + val + "$" : "", true, false).draw();
              });
            column
              .data()
              .unique()
              .sort()
              .each(function (d, j) {
                select.append('<option value="' + d + '">' + d + "</option>");
              });
          });
      },
    };

    return loadDT(options, element);
  } catch (e) {
    console.error(e);
  }
}

function loadDT(options, element) {
  if (element === undefined) element = $("table");

  return element
    .on("destroyDT", function (event) {
      $(this).DataTable().destroy();
    })
    .on("reloadDT", function (event) {
      cargarDropdown();
      loadDT(options, $(this));
      if (funcionTable !== "") window[funcionTable]();
    })
    .css("width", "100%")
    .DataTable(options);
}

function btnLimpiarFiltros() {
  //$("form")[0].reset();
  $(".header select").val(0).trigger("change");
}

function reloadDropzone() {
  Dropzone.forElement(".dropzone").removeAllFiles(true);
}

function cargarDropzone(idioma, numFiles, modulo, nombre, folder) {
  numFiles = typeof numFiles !== "undefined" ? numFiles : 1;

  try {
    Dropzone.autoDiscover = false;
    var url =
      "index.php?file=true&modulo=" +
      modulo +
      "&nombre=" +
      nombre +
      (!!folder ? "&folder=" + folder : "");
    $(".dropzone").dropzone({
      dictDefaultMessage: idioma.dictDefaultMessage,
      dictRemoveFile: idioma.dictRemoveFile,
      url: url,
      /*addRemoveLinks: true,*/
      acceptedFiles: "image/*",
      uploadMultiple: false,
      maxFiles: numFiles,
      autoProcessQueue: false,
      init: function () {
        var myDropzone = this;
        var iddp = "";
        if (typeof $($(this)[0].element).attr("rel") !== "undefined")
          iddp = $($(this)[0].element).attr("rel");

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
                  myDropzone.emit("addedFile", editedFile);
                  myDropzone.createThumbnailFromUrl(editedFile);
                  myDropzone.emit("complete", editedFile);

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
                } catch (e) {
                  console.log(e);
                  myDropzone.emit("reset");
                }
              });
            });
            reader.readAsDataURL(file);
          } catch (e) {
            console.log(e);
          }
        });

        this.on("success", function (file, responseText) {
          $("#txtDropzoneFile" + iddp).val(responseText);
        });

        this.on("error", function (file, responseText) {
          console.error(responseText);
          alert(responseText);
          myDropzone.emit("reset");
          myDropzone.removeAllFiles();
          console.log(myDropzone.files);
        });

        this.on("removedFile", function (file) {
          console.log(file);
          console.log(response);
          console.log($("#txtDropzoneFile" + iddp).val());
        });
      },
    });
  } catch (result) {
    if (result.responseText !== undefined) {
      alert(result.responseText);
      console.error(result.responseText);
    }
    console.log(result);
  }
}

function initMap() {
  var simpleMapId = "map_canvas";
  if ($("#" + simpleMapId).length > 0) {
    var mapElement = $(document.getElementById(simpleMapId));
    var mapDefaultZoom = parseInt(mapElement.attr("data-ts-map-zoom"), 10);
    var centerLatitude = parseFloat(
      mapElement.attr("data-ts-map-center-latitude")
    );
    var centerLongitude = parseFloat(
      mapElement.attr("data-ts-map-center-longitude")
    );
    var zoomPosition = mapElement.attr("data-ts-map-zoom-position");
    var controls = parseInt(mapElement.attr("data-ts-map-controls"), 10);
    controls === 0 ? (controls = true) : (controls = false);
    var locale = mapElement.attr("data-ts-locale");
    var currency = mapElement.attr("data-ts-currency");
    var unit = mapElement.attr("data-ts-unit");
    var scrollWheel = mapElement.attr("data-ts-map-scroll-wheel");
    scrollWheel === 1 ? (scrollWheel = true) : (scrollWheel = false);
    var mapStyle = mapElement.attr("data-ts-google-map-style");
    var markerDrag = parseInt(mapElement.attr("data-ts-map-marker-drag"), 10);
    markerDrag === 1 ? (markerDrag = true) : (markerDrag = false);

    if (!mapDefaultZoom) {
      mapDefaultZoom = 14;
    }

    var mapCenter = new google.maps.LatLng(centerLatitude, centerLongitude);
    var mapOptions = {
      zoom: mapDefaultZoom,
      center: mapCenter,
      disableDefaultUI: controls,
      scrollwheel: scrollWheel,
      styles: mapStyle,
    };
    var element = document.getElementById(simpleMapId);
    map = new google.maps.Map(element, mapOptions);
    var geocoder = new google.maps.Geocoder();
    marker = new google.maps.Marker({
      position: mapCenter,
      map: map,
      icon: "../assets/img/marker-small.png",
      draggable: markerDrag,
    });

    google.maps.event.addListener(marker, "dragend", function (event) {
      geocoder.geocode(
        {
          latLng: marker.getPosition(),
        },
        function (responses) {
          var place = "";
          if (responses && responses.length > 0) {
            marker.formatted_address = responses[0].formatted_address;
            var place = responses[0].address_components;
          } else {
            marker.formatted_address =
              "No se puede determinar la dirección en esta ubicación.";
          }

          $("#searchMapInput").val(marker.formatted_address);
          infowindow.setContent(
            "<div><strong>" + marker.formatted_address + "</strong></div>"
          );
          infowindow.open(map, marker);

          for (var i = 0; i < place.length; i++) {
            if (place[i].types[0] == "route") {
              $("#calleCliente").val(place[i].long_name);
            }
            if (place[i].types[0] == "street_number") {
              $("#numExtCliente").val(place[i].long_name);
            }
            if (place[i].types[0] == "political") {
              $("#coloniaCliente").val(place[i].long_name);
            }
            if (place[i].types[0] == "postal_code") {
              $("#cpCliente").val(place[i].long_name);
            }
            /*if(place[i].types[0] == 'administrative_area_level_1')
                                                                                                 {
                                                                                                 //ESTADO
                                                                                                 var txtEstado = place[i].long_name;
                                                                                                 }
                                                                                                 if(place[i].types[0] == 'locality')
                                                                                                 {
                                                                                                 //CIUDAD
                                                                                                 var txtCiudad = place[i].long_name;
                                                                                                 }
                                                                                                 if(place[i].types[0] == 'sublocality_level_1')
                                                                                                 {
                                                                                                 //COLONIA
                                                                                                 var txtColonia = place[i].long_name;
                                                                                                 }
                                                                                                 if(place[i].types[0] == 'country')
                                                                                                 {
                                                                                                 //document.getElementById('country').innerHTML =      long_name;
                                                                                                 }*/
          }

          $("#latMap").val(marker.getPosition().lat());
          $("#longMap").val(marker.getPosition().lng());
        }
      );
    });

    var input = document.getElementById("searchMapInput");
    map.controls[google.maps.ControlPosition.TOP_LEFT].push(input);
    var autocomplete = new google.maps.places.Autocomplete(input);
    autocomplete.bindTo("bounds", map);

    var infowindow = new google.maps.InfoWindow();
    autocomplete.addListener("place_changed", function () {
      infowindow.close();
      marker.setVisible(false);
      var place = autocomplete.getPlace();
      if (!place.geometry) {
        window.alert("Autocomplete's returned place contains no geometry");
        return;
      }

      /* If the place has a geometry, then present it on a map. */
      if (place.geometry.viewport) {
        map.fitBounds(place.geometry.viewport);
      } else {
        map.setCenter(place.geometry.location);
        map.setZoom(17);
      }

      marker.setPosition(place.geometry.location);
      marker.setVisible(true);
      var address = "";
      if (place.address_components) {
        address = [
          (place.address_components[0] &&
            place.address_components[0].short_name) ||
            "",
          (place.address_components[1] &&
            place.address_components[1].short_name) ||
            "",
          (place.address_components[2] &&
            place.address_components[2].short_name) ||
            "",
        ].join(" ");
      }

      infowindow.setContent(
        "<div><strong>" + place.name + "</strong><br>" + address
      );
      infowindow.open(map, marker);

      //Location details
      for (var i = 0; i < place.address_components.length; i++) {
        if (place.address_components[i].types[0] == "route") {
          $("#calleCliente").val(place.address_components[i].long_name);
        }
        if (place.address_components[i].types[0] == "street_number") {
          $("#numExtCliente").val(place.address_components[i].long_name);
        }
        if (place.address_components[i].types[0] == "sublocality_level_1") {
          $("#coloniaCliente").val(place.address_components[i].long_name);
        }
        if (place.address_components[i].types[0] == "postal_code") {
          $("#cpCliente").val(place.address_components[i].long_name);
        }
        /*if(place.address_components[i].types[0] == 'administrative_area_level_1')
                                                                 {
                                                                 //ESTADO
                                                                 var txtEstado = place.address_components[i].long_name;
                                                                 }
                                                                 if(place.address_components[i].types[0] == 'locality')
                                                                 {
                                                                 //CIUDAD
                                                                 var txtCiudad = place.address_components[i].long_name;
                                                                 }
                                                                 if(place.address_components[i].types[0] == 'sublocality_level_1')
                                                                 {
                                                                 //COLONIA
                                                                 var txtColonia = place.address_components[i].long_name;
                                                                 }
                                                                 if(place.address_components[i].types[0] == 'country')
                                                                 {
                                                                 //document.getElementById('country').innerHTML = place.address_components[i].long_name;
                                                                 }*/
      }

      /* Location details */
      $("#latMap").val(place.geometry.location.lat());
      $("#longMap").val(place.geometry.location.lng());
    });
  }
}

function cargarMultiDropzone(idioma, numFiles, modulo, nombre, folder) {
  numFiles = typeof numFiles !== "undefined" ? numFiles : 1;

  try {
    Dropzone.autoDiscover = false;
    var url =
      "index.php?file=true&modulo=" +
      modulo +
      "&nombre=" +
      nombre +
      (!!folder ? "&folder=" + folder : "");
    $(".dropzone").dropzone({
      dictDefaultMessage: idioma.dictDefaultMessage,
      dictRemoveFile: idioma.dictRemoveFile,
      url: url,
      /*addRemoveLinks: true,*/
      acceptedFiles: "image/*",
      uploadMultiple: true,
      maxFiles: numFiles,
      autoProcessQueue: true,
      parallelUploads: 10,
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
                  myDropzone.emit("addedFile", editedFile);
                  myDropzone.createThumbnailFromUrl(editedFile);
                  myDropzone.emit("complete", editedFile);

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
                } catch (e) {
                  console.log(e);
                  myDropzone.emit("reset");
                }
              });
            });
            reader.readAsDataURL(file);
          } catch (e) {
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
        });

        this.on("removedFile", function (file) {
          console.log(file);
          console.log(response);
          console.log($("#txtDropzoneFile").val());
        });
      },
    });
  } catch (result) {
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
  if (dataURI.split(",")[0].indexOf("base64") >= 0)
    byteString = atob(dataURI.split(",")[1]);
  else byteString = unescape(dataURI.split(",")[1]);

  // separate out the mime component
  var mimeString = dataURI.split(",")[0].split(":")[1].split(";")[0];

  // write the bytes of the string to a typed array
  var ia = new Uint8Array(byteString.length);
  for (var i = 0; i < byteString.length; i++) {
    ia[i] = byteString.charCodeAt(i);
  }

  return new Blob([ia], { type: mimeString });
}

function cargarNestable() {
  $("ol:empty").remove();
  $(".nestable").nestable();
  $(".dd-nodrag")
    .on("mousedown", function (event) {
      // mousedown prevent nestable click
      event.preventDefault();
      return false;
    })
    .on("click", function (event) {
      // click event
      event.preventDefault();
      return false;
    });
}

function cargarSwitchery() {
  var elems = Array.prototype.slice.call(
    document.querySelectorAll(".js-switch")
  );

  elems.forEach(function (html) {
    var switchery = new Switchery(html, { size: "small" });
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
      eStartDate.val(startDate.format("YYYY-MM-DD"));
    }

    if (eEndDate.val() === "") {
      endDate = moment(moment([date.getFullYear(), date.getMonth()])).endOf(
        "month"
      );
      eEndDate.val(endDate.format("YYYY-MM-DD"));
    }
  } else {
    datepicker = sibling;
    var frmt =
      eStartDate.val() !== "" && eStartDate.val() !== "0000-00-00"
        ? startDate.format(idioma.format) +
          " - " +
          endDate.format(idioma.format)
        : "";
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
          alert(
            "Formato para fecha no valido. Debe ser " +
              idioma.format +
              " - " +
              idioma.format
          );
        }

        if (elemento.val().indexOf(" - ") != -1) {
          var split = elemento.val().split(" - ");
          eStartDate.val(moment(split[0]).format("YYYY-MM-DD"));
          eEndDate.val(moment(split[1]).format("YYYY-MM-DD"));
        }
      }
    });
  }

  ranges[idioma.ranges.Hoy] = [moment(), moment()];
  ranges[idioma.ranges.Ayer] = [
    moment().subtract(1, "days"),
    moment().subtract(1, "days"),
  ];
  ranges[idioma.ranges.Siete_dias] = [moment().subtract(6, "days"), moment()];
  ranges[idioma.ranges.Treinta_dias] = [
    moment().subtract(29, "days"),
    moment(),
  ];
  ranges[idioma.ranges.Este_mes] = [
    moment([date.getFullYear(), date.getMonth()]),
    moment(moment([date.getFullYear(), date.getMonth()])).endOf("month"),
  ];
  ranges[idioma.ranges.Mes_pasado] = [
    moment([date.getFullYear(), date.getMonth()]).subtract(1, "month"),
    moment().subtract(1, "month").endOf("month"),
  ];
  ranges[idioma.ranges.Este_año] = [
    moment([date.getFullYear()]),
    moment(moment([date.getFullYear()])).endOf("year"),
  ];
  ranges[idioma.ranges.Año_pasado] = [
    moment([date.getFullYear() - 1]),
    moment(moment([date.getFullYear() - 1])).endOf("year"),
  ];
  ranges[idioma.ranges.Todos] = [
    moment().subtract(7, "years").startOf("year"),
    moment().endOf("year"),
  ];

  datepicker
    .daterangepicker(
      {
        ranges: ranges,
        linkedCalendars: false,
        autoApply: true,
        locale: {
          format: idioma.format,
          customRangeLabel: idioma.customRangeLabel,
        },
      },
      function (start, end, label) {
        eStartDate.val(start.format("YYYY-MM-DD"));
        eEndDate.val(end.format("YYYY-MM-DD"));
        if (sibling.length != 0)
          elemento.val(
            start.format(idioma.format) + " - " + end.format(idioma.format)
          );

        if (elemento.prop("tagName") != "input") elemento.html(label);

        if (fn) window[fn]();
      }
    )
    .on("cancel.daterangepicker", function (ev, picker) {
      $(this).val("");
    });
}

/**
 * @param elemento
 * @param eDate
 * @param idioma
 * @param time
 */
function cargarSingleDatePicker(elemento, eDate, idioma, time, options = {}) {
  var format = idioma.format;
  var sibling = elemento.siblings(".input-group-addon");
  var datepicker = elemento;
  var fecha = eDate.val() === "" ? moment() : moment(eDate.val());
  if (time === undefined) time = false;
  if (time) format += " h:mm A";

  if (sibling.length != 0) {
    datepicker = sibling;

    fecha = eDate.val() === "" ? "" : moment(eDate.val());
    var frmt =
      eDate.val() !== "" && eDate.val() !== "0000-00-00"
        ? fecha.format(format)
        : "";
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
          alert("Formato para fecha no valido. Debe ser " + format);
        }
        eDate.val(moment(elemento.val()).format("YYYY-MM-DD HH:mm:ss"));
      }
    });
  } else {
    fecha =
      eDate.val() !== "" && eDate.val() !== "0000-00-00 00:00:00"
        ? fecha
        : moment();
    datepicker.val(fecha.format(format));
  }

  datepicker
    .daterangepicker({
      locale: {
        format: format,
        daysOfWeek: idioma.daysOfWeek,
        monthNames: idioma.monthNames,
      },
      drops: options.position || "auto",
      showDropdowns: true,
      singleDatePicker: true,
      timePicker: time,
      timePickerIncrement: 15,
    })
    .on("cancel.daterangepicker", function (ev, picker) {
      $(this).val("");
    });

  datepicker.on("apply.daterangepicker", function (ev, picker) {
    if (picker === undefined) picker = datepicker.data("daterangepicker");

    eDate.val(picker.startDate.format("YYYY-MM-DD HH:mm:ss"));
    if (sibling.length != 0) elemento.val(picker.startDate.format(format));
  });

  datepicker.on("hide.daterangepicker", function (ev, picker) {
    if (picker === undefined) picker = datepicker.data("daterangepicker");

    eDate.val(picker.startDate.format("YYYY-MM-DD HH:mm:ss"));
    if (sibling.length != 0) elemento.val(picker.startDate.format(format));
  });

  if (sibling.length == 0) datepicker.trigger("apply.daterangepicker");
}

function cargarDoughnut(id, data, names, color) {
  var myChart = echarts.init(document.getElementById(id));
  myChart.setOption({
    tooltip: {
      transitionDuration: 0,
      showDelay: 0,
      hideDelay: 0,
      position: [0, 0],
      trigger: "item",
      formatter: "{a} <br/>{b} : {c} ({d}%)",
    },
    legend: {
      orient: "vertical",
      x: "left",
      textStyle: {
        color: "auto",
      },
      data: names,
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
                color: "rgba(165,165,165,1)",
              },
            },
            labelLine: {
              show: false,
              lineStyle: {
                color: "rgba(165,165,165,1)",
              },
            },
            color: function (params) {
              var red = color.red + params.dataIndex * 33;
              var green = color.green + params.dataIndex * 33;
              var blue = color.blue + params.dataIndex * 33;
              return "rgba(" + red + "," + green + "," + blue + ",1)";
            },
          },
        },
        type: "pie",
        radius: ["50%", "70%"],
        data: data,
      },
    ],
  });
  return myChart;
}

function cargarDoughnut2(id, data, color) {
  var myChart = echarts.init(document.getElementById(id));
  myChart.setOption({
    title: {
      x: "center",
      y: "center",
      itemGap: 20,
      textStyle: {
        color: "rgba(30,144,255,0.8)",
        fontSize: 20,
        fontWeight: "bolder",
      },
    },
    tooltip: {
      transitionDuration: 0,
      showDelay: 0,
      hideDelay: 0,
      position: [0, 0],
      show: true,
      formatter: "{a} <br/>{b} : {c} ({d}%)",
    },
    legend: {
      orient: "vertical",
      x: $("#pie").width() / 2 + 10,
      y: 20,
      itemGap: 12,
      textStyle: {
        color: "auto",
      },
      data: [data.name1, data.name2],
    },
    series: [
      {
        name: data.name1,
        type: "pie",
        clockWise: false,
        radius: [50, 70],
        itemStyle: {
          normal: {
            label: { show: false },
            labelLine: { show: false },
            color:
              "rgba(" +
              color[0].red +
              "," +
              color[0].green +
              "," +
              color[0].blue +
              ",1)",
          },
        },
        data: [
          {
            value: data.value1,
            name: data.name1,
          },
          {
            value: data.value2,
            name: "invisible",
            itemStyle: {
              normal: {
                color: "rgba(0,0,0,0)",
                label: { show: false },
                labelLine: { show: false },
              },
              emphasis: {
                color: "rgba(0,0,0,0)",
              },
            },
          },
        ],
      },
      {
        name: data.name2,
        type: "pie",
        clockWise: false,
        radius: [30, 50],
        itemStyle: {
          normal: {
            label: { show: false },
            labelLine: { show: false },
            color:
              "rgba(" +
              color[1].red +
              "," +
              color[1].green +
              "," +
              color[1].blue +
              ",1)",
          },
        },
        data: [
          {
            value: data.value2,
            name: data.name2,
          },
          {
            value: data.value1,
            name: "invisible",
            itemStyle: {
              normal: {
                color: "rgba(0,0,0,0)",
                label: { show: false },
                labelLine: { show: false },
              },
              emphasis: {
                color: "rgba(0,0,0,0)",
              },
            },
          },
        ],
      },
    ],
  });
  return myChart;
}

function cargarAutocomplete(obj, input) {
  var source = Object.keys(obj).map(function (key) {
    return obj[key];
  });
  input.autocomplete({
    source: source,
  });
}

function cerrarSesion() {
  navegar("login");
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
    ajax(
      "buildListaCategorias",
      { tipo: $("#selectTipo").val() },
      "transacciones"
    );
  }
}

function validarFormulario(form) {
  var $myForm = $(form),
    valid = $myForm[0].checkValidity();
  if (!valid)
    $('<input type="submit">').hide().appendTo($myForm).click().remove();

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

  var cvs = document.createElement("canvas");
  cvs.width = natW;
  cvs.height = natH;

  var ctx = cvs.getContext("2d").drawImage(source_img_obj, 0, 0, natW, natH);
  var newImageData = cvs.toDataURL(mime_type, quality / 100);
  var result_image_obj = new Image();
  result_image_obj.src = newImageData;
  return result_image_obj;
}

function madePdf(idVenta) {
  ajax("crearCotizacionPdf", { id: idVenta }, "cotizaciones");
}

function crearCotizacionPdf() {}

function notificacionNoVentaSinStock(nombreProducto, stock) {
  alert(
    `El Producto: ${nombreProducto} no se puede vender con un stock de: ${stock} unidades`
  );
}

function isInt(className, tipo) {
  tipo = typeof tipo !== "undefined" ? tipo : "#";
  $(tipo + className).keypress(function (e) {
    console.log(e.which);
    // between 0 and 9
    if ((e.which < 48 || e.which > 57) && e.which != 8) {
      return false; // stop processing
    }
  });
}

function showAlert(tipo, msj, time = 1500) {
  var title = "";
  var newTipo = "";

  switch (tipo) {
    case 1:
      title = "Correcto";
      newTipo = "success";
      break;
    case 2:
      title = "Error";
      newTipo = "error";
      break;
    case 3:
      title = "Atención";
      newTipo = "warning";
      break;
    case 4:
      break;
  }

  $(document).ready(function () {
    if (tipo != 4) {
      toastr[newTipo](msj, title);
      new swal({
        title: title,
        text: msj,
        type: newTipo,
        icon: newTipo,
        timer: time,
        showConfirmButton: false,
      });
    }
  });
}

function addCommas(nStr) {
  nStr += "";
  x = nStr.split(".");
  x1 = x[0];
  x2 = x.length > 1 ? "." + x[1] : "";
  var rgx = /(\d+)(\d{3})/;
  while (rgx.test(x1)) {
    x1 = x1.replace(rgx, "$1" + "," + "$2");
  }
  return x1 + x2;
}

function removeCommas(nStr) {
  nStr = parseFloat(nStr.replace(/,/g, ""));
  return nStr;
}

function searchSelectGral(className, tipo, URL, numChr, txtSeach, idSearch) {
  tipo = typeof tipo !== "undefined" ? tipo : ".";
  idSearch = typeof idSearch !== "undefined" ? idSearch : "0";

  $(tipo + className).select2({
    width: "100%",
    placeholderOption: "first",
    theme: "bootstrap",
    dir: "ltr",
    placeholder: txtSeach,
    maximumSelectionSize: 6,
    minimumInputLength: numChr,
    containerCssClass: ":all:",
    language: {
      noResults: function () {
        return "No se encontraron datos";
      },
      inputTooShort: function () {
        return "Capture " + numChr + " caracteres mínimo para su búsqueda";
      },
    },
    tags: [],
    ajax: {
      url: URL,
      dataType: "json",
      quietMillis: 250,
      data: function (params) {
        params["idSearch"] = idSearch;
        return { q: params };
      },
      processResults: function (data) {
        var results;
        results = [];
        $.each(data, function (idx, item) {
          results.push({
            id: item.id,
            text: item.text,
          });
        });

        return { results: results };
      },
    },
  });
}

function actionExt(URL, action, id) {
  var formData = new FormData();
  formData.append("action", action);
  formData.append("id", id);

  $.ajax({
    url: URL,
    data: formData,
    type: "POST",
    contentType: false,
    processData: false,
  })
    .done(function (result) {})
    .fail(function (result) {})
    .always(function (result) {});
}

function converToLocalTime(serverDate) {
  var dt = new Date(Date.parse(serverDate));
  var localDate = dt;

  var gmt = localDate;
  var min = gmt.getTime() / 1000 / 60; // convert gmt date to minutes
  var localNow = new Date().getTimezoneOffset(); // get the timezone
  // offset in minutes
  var localTime = min - localNow; // get the local time

  var dateStr = new Date(localTime * 1000 * 60);
  // dateStr = dateStr.toISOString("yyyy-MM-dd'T'HH:mm:ss.SSS'Z'"); // this will return as just the server date format i.e., yyyy-MM-dd'T'HH:mm:ss.SSS'Z'
  dateStr = dateStr.toString("yyyy-MM-dd'T'HH:mm:ss.SSS'Z'");
  return dateStr;
}

function newDatePicker(className, tipo) {
  tipo = typeof tipo !== "undefined" ? tipo : ".";
  $(function () {
    $(tipo + className).datepicker({
      format: "dd/mm/yyyy HH:mm",
      rtl: false,
      templates: {
        leftArrow: "<i class='fas fa-angle-left'></i>",
        rightArrow: "<i class='fas fa-angle-right'></i>",
      },
      onChange: function (selectedDates, dateStr, instance) {
        if (funcion != undefined) {
          setTimeout(function () {
            window[funcion]();
          }, 500);
        }
      },
    });
  });
}

function validateEmail(email) {
  var re =
    /^(([^<>()[\]\\.,;:\s@"]+(\.[^<>()[\]\\.,;:\s@"]+)*)|(".+"))@((\[[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}])|(([a-zA-Z\-0-9]+\.)+[a-zA-Z]{2,}))$/;
  return re.test(email);
}
