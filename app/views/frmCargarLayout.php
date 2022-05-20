<?php
ob_start();
?>

<div class="panel panel-default">
    <div class="panel-heading">
        <h5 class="panel-title">                                                            
            Cargar Layout
        </h5> 
    </div>
    <div class="panel-body">
        <div id="layout">
            <div class="form-group">
                <label class="col-md-3">Seleccionar Archivo</label>
                <div class="col-md-12">
                    <input type="file" id="adjunto" name="adjunto" class="form-control" onchange="if (!formato(this)) { this.value = ''; }">
                </div>
            </div>
            <div style="clear: both;"></div>
            <br>
            <div class="form-group">
                <div id="divAlertDanger" class="alert-danger" style="display: none;"></div>
                <div id="divAlertInfo" class="alert-info" style="display: none;"></div>
                <div id="divAlertWarning" class="alert-warning" style="display: none;"></div>
                <div id="divAlertSuccess" class="alert-success" style="display: none;"></div>
            </div>
            <div class="form-group">
                <div class="col-md-12">
                    <input type="button" id="btnCargarLayout" class="btn btn-danger" value="Cargar Layout" onclick="cargarLayout();">
                    <input type="button" id="btnLimpiar" class="btn btn-danger" value="Limpiar" onclick="limpiar();">
                    <input type="button" id="btnConsultar" class="btn btn-danger" value="Consultar" onclick="consultar();">
                    
                </div>
            </div>
            <br>
            <div style="clear: both;"></div>
            <br><br>
            <div class="form-group">
                <div id="listaDatosLayout"></div>
            </div>
        </div>
        
        <div id="formulario" style="display: none;">
            <h5 class="panel-title"><b>Consulta de Informaci&oacute;n</b></h5>
            <br>
            <div class="form-group">
                <input type="hidden" id="id" name="id" />
                <label class="col-md-3">Nombre</label>
                <div class="col-md-6">
                    <input type="text" id="nombre" name="nombre" class="form-control">
                </div>
            </div>
            <div style="clear: both;"></div><br>
            <div class="form-group">
                <label class="col-md-3">Apellido Paterno</label>
                <div class="col-md-6">
                    <input type="text" id="paterno" name="paterno" class="form-control">
                </div>
            </div>
            <div style="clear: both;"></div><br>
            <div class="form-group">
                <label class="col-md-3">Apellido Materno</label>
                <div class="col-md-6">
                    <input type="text" id="materno" name="materno" class="form-control">
                </div>
            </div>
            <div style="clear: both;"></div><br>
            <div class="form-group">
                <label class="col-md-3">G&eacute;nero</label>
                <div class="col-md-6">
                    <select id="cveGenero" name="cveGenero" class="form-control">
                        <option value="">Selecciona una opci&oacute;n</option>
                    </select>
                </div>
            </div>
            <div style="clear: both;"></div><br>
            <div class="form-group">
                <label class="col-md-3">Edad</label>
                <div class="col-md-6">
                    <input type="text" id="edad" name="edad" class="form-control">
                </div>
            </div>
            <div style="clear: both;"></div><br>
            <div class="form-group">
                <label class="col-md-3">Sueldo Mensual</label>
                <div class="col-md-6">
                    <input type="text" id="sueldoMensual" name="sueldoMensual" class="form-control">
                </div>
            </div>
            <div style="clear: both;"></div><br>
            <div class="form-group">
                <label class="col-md-3">fecha Inicio</label>
                <div class="col-md-6">
                    <input type="text" id="fechaInicio" name="fechaInicio" class="form-control">
                </div>
            </div>
            <div style="clear: both;"></div><br>
            <div class="form-group">
                <label class="col-md-3">fecha Fin</label>
                <div class="col-md-6">
                    <input type="text" id="fechaFin" name="fechaFin" class="form-control">
                </div>
            </div>
            <div style="clear: both;"></div>
            <br>
            <div class="form-group">
                <input type="button" id="btnRegresar" class="btn btn-danger" value="Regresar" onclick="muestraOcultaForm(1);">
                <input type="button" id="btnBuscar" class="btn btn-danger" value="Buscar" onclick="buscar();">
                <input type="button" id="btnModificar" class="btn btn-danger" value="Modificar" style="display: none;" onclick="modificar();">
                <input type="button" id="btnEliminar" class="btn btn-danger" value="Eliminar" style="display: none;" onclick="eliminar();">
                <input type="button" id="btnLimpiarForm" class="btn btn-danger" value="Limpiar" onclick="limpiarFormulario();">
            </div>
            <div style="clear: both;"></div>
            <br>
            <div id="resultadosBusqueda"></div>
            <br>
        </div>
    </div>
</div>

<script>
    
    consultarGeneros = function(){
        $.ajax({
            type: 'POST',
            url: "../app/facades/layout/LayoutFacade.Class.php",
            data: {
                accion: 'cargarGeneros',
                activo: 'S'
            },
            beforeSend: function (xhr) {
            },
            success: function (data) {
                var html = '<option value="">Selecciona una opcion</option>';
                //console.log(data);
                var result = eval("(" + data + ")");
                if ( parseInt(result.totalCount) > 0 ) {
                    console.log(result.data);
                    for ( var n = 0; n < result.totalCount; n++ ) {
                        html += '<option value="' + result.data[n].cveGenero + '">' + result.data[n].desGenero + '</option>';
                    }
                    $("#cveGenero").html(html);
                }
            },
            error: function (jqXHR, textStatus, errorThrown) {

            }
        });
    };
    
    formato = function (object) {
        var formatos = new Array(".csv");
        var extension = (object.value.substring(object.value.lastIndexOf("."))).toLowerCase();
        var valido = false;
        var mensaje = "";
        for (var index = 0; index < formatos.length; index++) {
            if (formatos[index] === extension) {
                valido = true;
                break;
            } else {
                valido = false;
            }
        }
        if ( !valido ) {
            mensaje += " <span style='font-size: 16px;'>Se esperaba un archivo csv</span>";
        }
        if (valido) {
            //calcular el peso del archivo
            var pesoKb = Math.round(object.files[0].size/1024,2);
            //alert(pesoKb);
            if ( pesoKb > 4096 ) {
                valido = false;
                mensaje += " <br>El archivo supera el tama&ntilde;o m&aacute;ximo permitido de 4Mb, favor de verificar";
            } else {
                valido = true;
            }
        }

        if (!valido) {
            $("#divAlertWarning").html("");
            $("#divAlertWarning").html(mensaje).fadeIn('slow').delay(5000).fadeOut('slow');
            return false;
        } else {
            return true;
        }
    };
    
    $("#adjunto").on('change', function (e) {
        var ext = $("input#adjunto").val().split(".").pop().toLowerCase();
        if ( $.inArray(ext, ["csv"]) !== -1 ) {
            var contenidoCsv = new Array();
            contenidoCsv[0] = (e.target.files);
            var file = e.target.files[0];
            console.log(file);
            var reader = new FileReader();
            //reader.readAsText(file);
            console.log(reader);
            var csvval = "";
            reader.onloadend = function (e) {
                csvval = e.target.result.split("\n");
                //csvval = eval("csvval");
                mostrarContenidoLayout(csvval);
            };
            reader.readAsText(e.target.files.item(0));
        } else {
            $("#divAlertWarning").html("<span style='font-size: 16px;'>Se esperaba un archivo CSV</span>");
        }
    });
    
    limpiar = function(){
        $("#adjunto").val("");
        $("#listaDatosLayout").html("");
        $("#btnCargarLayout").show();
        $("#btnLimpiar").show();
        $("#btnConsultar").show();
        muestraOcultaForm(1);
    };
    
    mostrarContenidoLayout = function(csv){
        console.log($.trim(csv[0]).substring(0,5));
        var html = '';
        if ( $.trim(csv[0]).substring(0,6) === 'nombre' ) {
            html += '<div class="panel panel-default"><div class="panel-heading"><h4 class="panel-title"><a data-toggle="collapse" data-parent="#accordion" > Datos del Layout</a></h4></div>';
            //html += "<div><center><h5><b>Movimientos<b></h5></center></div>";
            html += "<table class='table table-striped' id='tablaListaPagos'>";
            html += "<thead><th>Nombre</th><th>Genero</th><th>Edad</th><th>Sueldo Mensual</th></thead><tbody>";
            var detalle = new Array();
            var nombre = "";
            var conceptoDetalle = '';
            for ( var n = 1; n < csv.length; n++ ) {
                detalle[n] = csv[n].split(",");
                console.log(n + " - " + detalle[n]);
                html += "<tr>";
                html += "<td>" + detalle[n][0].replace(/"/g, "") + " " + detalle[n][1].replace(/"/g, "") + " " + detalle[n][2].replace(/"/g, "") + "</td>";
                html += "<td>" + detalle[n][3].replace(/"/g, "") + "</td>";
                html += "<td>" + detalle[n][4].replace(/"/g, "") + "</td>";
                html += "<td align='right'>" + number_format(detalle[n][5], 2, ".", ",") + "</td>";
                html += "</tr>";
            }
            html += "</tbody></table>";
        } else {
            $("#divAdvertencia").html("El archivo cargado no tiene un formato valido, favor de verificar").fadeIn('slow').delay('5000').fadeOut('slow');
            $("#btnCargarLayout").hide();
        }
        $("#listaDatosLayout").html(html);
    };
    
    consultar = function(){
        muestraOcultaForm(2);
        limpiarFormulario();
    };
    
    buscar = function(){
        $("#resultadosBusqueda").html("");
        $.ajax({
            type: 'POST',
            url: "../app/facades/layout/LayoutFacade.Class.php",
            data: {
                accion: 'consultarRegistros',
                id: $("#id").val(),
                nombre: $("#nombre").val(),
                paterno: $("#paterno").val(),
                materno: $("#materno").val(),
                cveGenero: $("#cveGenero").val(),
                edad: $("#edad").val(),
                sueldoMensual: $("#sueldoMensual").val(),
                fechaInicio: $("#fechaInicio").val(),
                fechaFin: $("#fechaFin").val()
            },
            beforeSend: function (xhr) {
            },
            success: function (data) {
                var html = "<table class='table table-striped' id='tablaListaPagos'>";
                html += "<thead><th>Nombre</th><th>Genero</th><th>Edad</th><th>Sueldo Mensual</th></thead><tbody>";
                //console.log(data);
                var result = eval("(" + data + ")");
                if ( parseInt(result.totalCount) > 0 ) {
                    for ( var n = 0; n < result.totalCount; n++ ) {
                        html += "<tr style='cursor: pointer;' onclick=cargarDatos(" + result.data[n].id + ")>";
                        html += "<td>" + result.data[n].nombre + " " + result.data[n].paterno + " " + result.data[n].materno + "</td>";
                        html += "<td>" + result.data[n].desGenero + "</td>";
                        html += "<td>" + result.data[n].edad + "</td>";
                        html += "<td align='right'>" + number_format(result.data[n].sueldoMensual, 2, ".", ",") + "</td>";
                        html += "</tr>";
//                        $("#btnModificar").show();
//                        $("#btnEliminar").show();
                    }
                    html += "</tbody>";
                    html += "</table>";
                    $("#resultadosBusqueda").html(html);
                } else {
                    alert(result.mensaje);
//                    $("#btnModificar").hide();
//                    $("#btnEliminar").hide();
                }
                
            },
            error: function (jqXHR, textStatus, errorThrown) {

            }
        });
    };
    
    cargarDatos = function(id){
        $.ajax({
            type: 'POST',
            url: "../app/facades/layout/LayoutFacade.Class.php",
            data: {
                accion: 'consultarRegistros',
                id: id
            },
            dataType: "json",
            beforeSend: function (xhr) {
            },
            success: function (data) {
//                var result = eval("(" + data + ")");
//                console.log(result);
                if ( parseInt(data.totalCount) > 0 ) {
                    $("#id").val(data.data[0].id);
                    $("#nombre").val(data.data[0].nombre);
                    $("#paterno").val(data.data[0].paterno);
                    $("#materno").val(data.data[0].materno);
                    $("#cveGenero").val(data.data[0].cveGenero);
                    $("#edad").val(data.data[0].edad);
                    $("#sueldoMensual").val(data.data[0].sueldoMensual);
                    $("#btnModificar").show();
                    $("#btnEliminar").show();
                } else {
                    $("#btnModificar").hide();
                    $("#btnEliminar").hide();
                }
            },
            error: function (jqXHR, textStatus, errorThrown) {

            }
        });
    };
    
    limpiarFormulario = function(){
        $("#id").val("");
        $("#nombre").val("");
        $("#paterno").val("");
        $("#materno").val("");
        $("#cveGenero").val("");
        $("#edad").val("");
        $("#sueldoMensual").val("");
        $("#fechaInicio").val("");
        $("#fechaFin").val("");
        $("#resultadosBusqueda").html("");
        $("#btnModificar").hide();
        $("#btnEliminar").hide();
    };
    
    eliminar = function(){
        var id = $("#id").val();
        if ( id === '' ) {
            alert('Debe seleccionar una persona a eliminar');
        } else {
            if ( confirm("Esta seguro de eliminar los datos?") ) {
                $.ajax({
                    type: 'POST',
                    url: "../app/facades/layout/LayoutFacade.Class.php",
                    data: {
                        accion: 'eliminarRegistro',
                        id: id
                    },
                    dataType: "json",
                    beforeSend: function (xhr) {
                    },
                    success: function (data) {
        //                var result = eval("(" + data + ")");
        //                console.log(result);
                        if ( parseInt(data.totalCount) > 0 ) {
                            alert('Registro eliminado correctamente');
                            limpiarFormulario();
                        } else {
                            alert(data.mensaje);
                        }
                    },
                    error: function (jqXHR, textStatus, errorThrown) {

                    }
                });
            }
        }
    };
    
    modificar = function(){
        var id = $("#id").val();
        if ( id === '' ) {
            alert('Debe seleccionar una persona a modificar');
        } else {
            if ( confirm("Los datos son correctos?") ) {
                $.ajax({
                    type: 'POST',
                    url: "../app/facades/layout/LayoutFacade.Class.php",
                    data: {
                        accion: 'modificarRegistro',
                        id: id,
                        nombre: $("#nombre").val(),
                        paterno: $("#paterno").val(),
                        materno: $("#materno").val(),
                        cveGenero: $("#cveGenero").val(),
                        edad: $("#edad").val(),
                        sueldoMensual: $("#sueldoMensual").val()
                    },
                    dataType: "json",
                    beforeSend: function (xhr) {
                    },
                    success: function (data) {
        //                var result = eval("(" + data + ")");
        //                console.log(result);
                        if ( parseInt(data.totalCount) > 0 ) {
                            alert('Registro actualizado correctamente');
                            buscar();
                        } else {
                            alert(data.mensaje);
                        }
                    },
                    error: function (jqXHR, textStatus, errorThrown) {

                    }
                });
            }
        }
    };
    
    cargarLayout = function(){
        var formData = new FormData();
        formData.append('accion', 'enviarLayout');
        formData.append('layout', $('#adjunto')[0].files[0]);
        if ( $("#adjunto").val() === '' ) {
            $("#divAlertWarning").html('debe seleccionar un archivo csv').fadeIn('slow').delay('5000').fadeOut('slow');
        } else {
            $.ajax({
                type: 'POST',
                url: "../app/facades/layout/LayoutFacade.Class.php",
                contentType: false,
                processData: false,
                data: formData,
                dataType: "json",
                beforeSend: function (xhr) {
                    $("#btnCargarLayout").hide();
                    $("#btnLimpiar").hide();
                    $("#btnConsultar").hide();
                },
                success: function (data) {
                    console.log(data);
                    if (data.estatus === "ok") {
                        $("#divAlertSuccess").html(data.mensaje).fadeIn('slow').delay('5000').fadeOut('slow');
                        //limpiar();
                        $("#btnCargarLayout").show();
                        $("#btnLimpiar").show();
                        $("#btnConsultar").show();
                        $("#adjunto").val("");
                        $("#listaDatosLayout").html("");
                    } else {
                        $("#divAlertWarning").html(data.mensaje).fadeIn('slow').delay('5000').fadeOut('slow');
                        $("#btnCargarLayout").show();
                        $("#btnLimpiar").show();
                        $("#btnConsultar").show();
                    }
                },
                error: function (jqXHR, textStatus, errorThrown) {

                }
            });
        }
    };
    
    number_format = function(number, decimals, dec_point, thousands_sep) {
        number = (number + '').replace(/[^0-9+\-Ee.]/g, '');
        var n = !isFinite(+number) ? 0 : +number,
        prec = !isFinite(+decimals) ? 0 : Math.abs(decimals),
        sep = (typeof thousands_sep === 'undefined') ? ',' : thousands_sep,
        dec = (typeof dec_point === 'undefined') ? '.' : dec_point,
        s = '',
        toFixedFix = function (n, prec) {
            var k = Math.pow(10, prec);
            return '' + Math.round(n * k) / k;
        };

        s = (prec ? toFixedFix(n, prec) : '' + Math.round(n)).split('.');
        if (s[0].length > 3) {
            s[0] = s[0].replace(/\B(?=(?:\d{3})+(?!\d))/g, sep);
        }
        if ((s[1] || '').length < prec) {
            s[1] = s[1] || '';
            s[1] += new Array(prec - s[1].length + 1).join('0');
        }
        return s.join(dec);
    };
    
    fechaVista = function (fecha) {
        var fechaVista = fecha.split(" ");
        return fechaVista[0].split("-")[2] + "/" + fechaVista[0].split("-")[1] + "/" + fechaVista[0].split("-")[0];
    };
    
    function muestraOcultaForm(opc) {
        if ( parseInt(opc) === 1 ) {
            $("#layout").show();
            $("#formulario").hide();
        } else if ( parseInt(opc) === 2 ) {
            $("#layout").hide();
            $("#formulario").show();
        }
    }
    
    $(document).ready(function () {
        consultarGeneros();
        $.datepicker.regional['es'] = {
            closeText: 'Cerrar',
            prevText: '<Ant',
            nextText: 'Sig>',
            currentText: 'Hoy',
            monthNames: ['Enero', 'Febrero', 'Marzo', 'Abril', 'Mayo', 'Junio',
                'Julio', 'Agosto', 'Septiembre', 'Octubre', 'Noviembre',
                'Diciembre'],
            monthNamesShort: ['Ene', 'Feb', 'Mar', 'Abr', 'May', 'Jun', 'Jul',
                'Ago', 'Sep', 'Oct', 'Nov', 'Dic'],
            dayNames: ['Domingo', 'Lunes', 'Martes', 'Mi�rcoles', 'Jueves',
                'Viernes', 'S�bado'],
            dayNamesShort: ['Dom', 'Lun', 'Mar', 'Mi�', 'Juv', 'Vie', 'S�b'],
            dayNamesMin: ['Do', 'Lu', 'Ma', 'Mi', 'Ju', 'Vi', 'S�'],
            weekHeader: 'Sm',
            dateFormat: 'dd/mm/yy',
            firstDay: 1,
            isRTL: false,
            showMonthAfterYear: false,
            yearSuffix: ''
        };
        $.datepicker.setDefaults($.datepicker.regional['es']);
        $("#fechaInicio").datepicker();
        $("#fechaFin").datepicker();
    });
</script>
<?php $contenido = ob_get_clean(); ?>
<?php include 'layout.php'; ?>