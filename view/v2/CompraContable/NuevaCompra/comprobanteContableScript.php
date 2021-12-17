<script type="text/javascript">
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

    //$("#detalleComprobante").change();
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
        data: { param: param, value: value, cdi_id: cdi_id },
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
        data: { id: id },
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
        data: { id: id },
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
            url: "index.php?controller=Cart&action=sendItem",
            cache: "false",
            data: { data: obj, pos: pos, tercero: tercero },
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
    $.post("index.php?controller=Articulo&action=deleteItemToCart", { data: id }, function(r) {
        loadCart();
    });
    calculoCompra();
}

function deleteItemDetalle(id) {
    $.post("index.php?controller=Compras&action=deleteItemDetalle", { data: id }, function(r) {
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
    $.post("index.php?controller=CompraContable&action=calculoCompra", { data: data, contabilidad: contabilidad }, function(r) {
        $("#calculoCompra").html(r);
    });
}

function sendCompra() {
    var form = document.getElementById('formCompra');
    var data = new FormData(form);
    $.ajax({
        type        : 'POST',
		method      : 'POST',
		cache       : false,
		contentType : false,
		processData : false,
        url         : "CompraContable&action=crearCompraContable",
        cache       : "false",
        data        : data,
        dataType    : 'JSON',
        success: function(r) {
            try {
                if (r.success) {
                    $(location).attr('href', "#" + r.success);
                } else{
                    console.log(r.error);
                    toastMessage(r.alertType, r.response);
                    $(".linearLoading").html("");
                }
            } catch (e) {
                toastMessage('error', e);
            }
        },
        error: function(xhr, status) {
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
    data = $(".select2imp option:selected").val();
    proceso = $('#contabilidad').val();
    $.ajax({
        method: "POST",
        url: "index.php?controller=Impuestos&action=addImpuestoToCart",
        cache: "false",
        data: { data: data, proceso: proceso },
        success: function(r) {
            console.log(r);
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
    data = $(".select2re option:selected").val();
    proceso = $('#contabilidad').val();
    console.log(proceso);
    $.ajax({
        method: "POST",
        url: "index.php?controller=Retencion&action=addRetencionToCart",
        cache: "false",
        data: { data: data, proceso: proceso },
        success: function(r) {
            console.log(r);
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