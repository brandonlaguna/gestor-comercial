<script>
$('.fc-datepicker').datepicker({
    showOtherMonths: true,
    selectOtherMonths: true
});
$('.selectpicker').selectpicker();

$(document).ready(init);
function init() {
    $("#fecha_final").hide();
    $("#factura_proveedor").hide();
    $("#codigo_contable").change(addProduct);
    $(".calculate").keyup(calculate);
    $("#AddItem").click(sendItem);
    $("#detalleComprobante").change(calculoCompra);
    $("#formaPago").change(fecha_final);
    $("#sendCompra").click(sendCompra);
    $("#updateCompra").click(updateCompra);
    $("#nuevo_tercero").click(nuevo_tercero);
    $("#AddRet").click(addretencion);
    $("#AddIm").click(addimpuesto);

    // showing modal with effect
    $('.modal-effect').on('click', function(e) {
        e.preventDefault();

        var effect = $(this).attr('data-effect');
        $('#modaldemo8').addClass(effect);
        $('#modaldemo8').modal('show');
    });

    // hide modal with effect
    $('#modaldemo8').on('hidden.bs.modal', function(e) {
        $(this).removeClass(function(index, className) {
            return (className.match(/(^|\s)effect-\S+/g) || []).join(' ');
        });
    });
    if ($().select2) {
        $('.select2imp').select2({
            minimumResultsForSearch: Infinity,
            placeholder: 'Impuestos'
        });

        $('.select2re').select2({
            minimumResultsForSearch: Infinity,
            placeholder: 'Retenciones'
        });

        // Select2 by showing the search
        $('.select2-show-search').select2({
            minimumResultsForSearch: ''
        });

        // Select2 with tagging support
        $('.select2-tag').select2({
            tags: true,
            tokenSeparators: [',', ' ']
        });
    }
    $('.br-toggle').on('click', function(e) {
        e.preventDefault();
        $(this).toggleClass('on');
    });
}

function changeTax(route, value) {
    $("#" + route).val(value);
    calculate();
}

function changeCartValue(route, value, param, cdi_id) {
    $("#" + route).val(value);
    $.ajax({
        method: "POST",
        url: "index.php?controller=Articulo&action=changeCartValue",
        cache: "false",
        data: {
            param: param,
            value: value,
            cdi_id: cdi_id
        },
        success: function(r) {
            console.log(r);
            loadCart();
        },
        error: function(xhr, status) {},
        beforeSend: function() {}
    });
}

function addProduct() {
    var value = $("#codigo_contable").val();
}

function removeColaImpuesto(id) {
    $.ajax({
        method: "POST",
        url: "index.php?controller=Impuestos&action=removeCartImpuesto",
        cache: "false",
        data: {
            id: id
        },
        success: function(r) {
            loadCart();
        },
        error: function(xhr, status) {},
        beforeSend: function() {}
    });
}

function removeColaRetencion(id) {
    $.ajax({
        method: "POST",
        url: "index.php?controller=Retencion&action=removeCartRetencion",
        cache: "false",
        data: {
            id: id
        },
        success: function(r) {
            loadCart();
        },
        error: function(xhr, status) {},
        beforeSend: function() {}
    });
}

function fecha_final() {
    estado = $("#formaPago option:selected").text();
    if (estado.includes("Credito") || estado.includes("credito")) {
        $("#fecha_final").show();
        $("#factura_proveedor").show();
    } else {
        $("#fecha_final").hide();
        $("#factura_proveedor").hide();
    }
}

function calculate() {
    var cantidad = $("#cantidad").val();
    if (cantidad != "") {
        cantidad = cantidad;
    } else {
        cantidad = 0;
    }
    var precio_unitario = $("#costo_producto").val();
    var importe = $("#imp_compra").val();
    var sub_total = parseInt(cantidad) * parseInt(precio_unitario);
    var total = parseInt((precio_unitario * cantidad) * ((importe / 100) + 1));
    $("#sub_total_compra").val(sub_total);
    $("#total_compra").val(total);
}

function sendItem() {
    var tercero = $("#proveedor").val();
    $("#ItemsToAdd tbody tr").each(function() {
        json = "";
        $(this).find("input").each(function() {
            $this = $(this);
            json += ',"' + $this.attr("id") + '":"' + $this.val() + '"'
        });
        obj = JSON.parse('{' + json.substr(1) + '}');
        $.each(obj, function(i, item) {
            $("#" + i).val("");
        });
        $.ajax({
            method: "POST",
            url: "Cart&action=sendItem",
            cache: "false",
            data: {
                data: obj,
                pos: pos,
                tercero: tercero
            },
            success: function(r) {
                loadCart();
            },
            error: function(xhr, status) {},
            beforeSend: function() {}
        });
    });

}

function loadCart() {
    $.ajax({
        method: "POST",
        url: "index.php?controller=Articulo&action=loadCart",
        cache: "false",
        success: function(r) {
            $("#bodycart").html(r);
            calculoCompra();
            $(".infinite-linear").html("");
        },
        error: function(xhr, status) {},
        beforeSend: function() {
            $(".infinite-linear").html(linearLoading());

        }
    })
}

function deleteItem(id) {
    $.post("index.php?controller=Articulo&action=deleteItemToCart", {
        data: id
    }, function(r) {
        loadCart();
    });
    calculoCompra();
}

function deleteItemDetalle(id) {
    $.post("index.php?controller=Compras&action=deleteItemDetalle", {
        data: id
    }, function(r) {
        console.log(r);
        //loadCart();
    });
    //calculoCompra();
}

function calculoCompra() {
    if ($("#detalleComprobante option:selected").val() != null) {
        data = $("#detalleComprobante option:selected").val();
    } else {
        data = $("#detalleComprobante").val();
    }

    contabilidad = $("#contabilidad").val();
    $.post("index.php?controller=Compras&action=calculoCompra", {
        data: data,
        contabilidad: contabilidad
    }, function(r) {
        $("#calculoCompra").html(r);
    });
}

function sendCompra() {
    var url = "";
    var cont = $("#contabilidad").val();
    var x = $("#formCompra").serializeArray();
    data = "";
    $.each(x, function(i, field) {
        data += ',"' + field.name + '":"' + field.value + '"'
    });
    data = JSON.parse('{' + data.substr(1) + '}');
    $.ajax({
        method: "POST",
        url: "Compra&action=crearCompra",
        cache: "false",
        data: data,
        success: function(r) {
            try {
                r = JSON.parse(r);
                if (r.success) {
                    $(location).attr('href', "#" + r.success);
                } else if (r.error) {
                    console.log(r.error);
                    toastMessage('error', r.error);
                    $(".linearLoading").html("");
                }
            } catch (e) {
                r = JSON.parse(r);
                toastMessage('error', r);
            }
        },
        error: function(xhr, status) {
            //$(".br-mainpanel").html("error en la consulta");
        },
        beforeSend: function() {
            $(".linearLoading").html(linearLoading());
        }
    })
}

function updateCompra() {
    //preparar datos
    //preparando informacion

    //data = $("#formCompra").serializeArray();
    var x = $("#formCompra").serializeArray();
    data = "";
    $.each(x, function(i, field) {
        data += ',"' + field.name + '":"' + field.value + '"'
        //data +=',"'+field.name+'":"'+field.value+'"'
    });
    console.log(data);
    data = JSON.parse('{' + data.substr(1) + '}');
    $.ajax({
        method: "POST",
        url: "index.php?controller=Compras&action=updateCompra",
        cache: "false",
        data: data,
        success: function(r) {
            console.log(r);
            try {
                r = JSON.parse(r);
                if (r.success) {
                    $(location).attr('href', "#file/ingreso/" + r.success);
                } else if (r.error) {
                    console.log(r.error);
                    toastMessage('error', r.error);
                    $(".linearLoading").html("");
                }
            } catch (e) {
                r = JSON.parse(r);
                toastMessage('error', r.error);
            }
            //
        },
        error: function(xhr, status) {
            //$(".br-mainpanel").html("error en la consulta");
        },
        beforeSend: function() {
            $(".br-mainpanel").html(loading());
        }
    })

    loadCart();

}

function nuevo_tercero() {
    $.post("index.php?controller=almacen&action=new_tercero&data=modal", data, function(r) {
        $(".modal-body").html(r);
    });
}

function addimpuesto() {
    data = $("#colaImpuestos option:selected").val();
    proceso = $('#contabilidad').val();
    $.ajax({
        method      : "POST",
        url         : "Cart&action=addImpuestoToCart",
        cache       : "false",
        dataType    : 'JSON',
        data: {
            data: data,
            proceso: proceso
        },
        success: function(impuesto) {
            console.log(impuesto);
            if(impuesto.estadoImpuesto == false){
                toastMessage('error', impuesto.mensajeImpuesto);
            }
            loadCart();
            $(".linearLoading").html("");
        },
        error: function(xhr, status) {},
        beforeSend: function() {
            $(".linearLoading").html(linearLoading());
        }
    })
}

function addretencion() {
    data = $("#colaRetenciones option:selected").val();
    proceso = $('#contabilidad').val();
    console.log(proceso);
    $.ajax({
        method: "POST",
        url: "Cart&action=addRetencionToCart",
        cache: "false",
        data: {
            data: data,
            proceso: proceso
        },
        dataType    : 'JSON',
        success: function(retencion) {
            console.log(retencion);
            if(retencion.estadoRetencion == false){
                toastMessage('error', retencion.mensajeRetencion);
            }
            loadCart();
            $(".linearLoading").html("");
        },
        error: function(xhr, status) {},
        beforeSend: function() {
            $(".linearLoading").html(linearLoading());
        }
    })
}
</script>