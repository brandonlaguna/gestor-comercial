<script type="text/javascript">
$( document ).ready(init);
function init() {
$('.selectpicker').selectpicker();
collapseMenu();
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
$('.fc-datepicker').datepicker({
    showOtherMonths: true,
    selectOtherMonths: true
});
$("#fecha_final").hide();
$(".default_hidden").hide();
$("#codigo_contable").change(addProduct);
$(".calculate").keyup(calculate);
$("#AddItem").click(sendItem);
$("#detalleComprobante").change(calculoVenta);
$("#formaPago").change(fecha_final);
$("#sendVenta").click(sendVenta);
$("#updateVenta").click(updateVenta);
//$("#detalleComprobante").change();
$("#nuevo_tercero").click(nuevo_tercero);
$("#history").click(history);
$("#AddRet").click(addretencion);
$("#AddIm").click(addimpuesto);
$("#AddMetodoPago").click(addmetodopago);
//$("#codigo_barras").focus();
}

function changeTax(route,value){
    $("#"+route).val(value);
    calculate();
}

function changeCartValue(route,value, param, cdi_id){
    $("#"+route).val(value);
    $.ajax({
        method: "POST",
        url: "index.php?controller=Articulo&action=changeCartValue",
        cache : "false",
        data:{param:param,value:value,cdi_id:cdi_id},
        success : function(r) {
            loadCart();
        },
        error : function(xhr, status) {},
        beforeSend: function(){}
    });
}
function addProduct(){
    var value = $("#codigo_contable").val();
}
function removeColaImpuesto(id){
    $.ajax({
        method: "POST",
        url: "index.php?controller=Impuestos&action=removeCartImpuesto",
        cache : "false",
        data:{id:id},
        success : function(r) {
            loadCart();
        },
        error : function(xhr, status) {
        },
        beforeSend: function(){
        }
        });
}

function removeColaRetencion(id){
    $.ajax({
        method: "POST",
        url: "index.php?controller=Retencion&action=removeCartRetencion",
        cache : "false",
        data:{id:id},
        success : function(r) {
            loadCart();
        },
        error : function(xhr, status) {
        },
        beforeSend: function(){
        }
        });
}

function fecha_final(){
    
    estado = $("#formaPago option:selected").text();
    if(estado.includes("Credito") || estado.includes("credito")){
        $("#fecha_final").show();
    }else{
        $("#fecha_final").hide();
    }
}

function calculate(){
    var cantidad = $("#cantidad").val();
    if(cantidad !=""){
        cantidad=cantidad;
    }else{
        cantidad =0;
    }
    var precio_unitario = $("#precio_venta").val();
    var importe = $("#imp_venta").val();
    var sub_total = parseInt(cantidad) * parseInt(precio_unitario);
    var total = parseInt((precio_unitario * cantidad) * ((importe/100)+1)) ;
    $("#sub_total_venta").val(sub_total);
    $("#total_venta").val(total);
}

function sendItem(){
    var tercero = $("#proveedor").val();
    $("#ItemsToAdd tbody tr").each(function () {
        json ="";
        $(this).find("input").each(function () {
            $this=$(this);
            json+=',"'+$this.attr("id")+'":"'+$this.val()+'"'
        });
        obj=JSON.parse('{'+json.substr(1)+'}');
        $.each(obj, function(i, item) {
            $("#"+i).val("");
        });

        $.ajax({
            method: "POST",
            url: "Cart&action=sendItem",
            cache : "false",
            data:{data:obj,pos:pos,tercero:tercero},
            success : function(r) {
                loadCart();
                $("#codigo_barras").focus();
                try {
                    r =JSON.parse(r);
                    if(r.success){

                    }else if(r.error){
                        toastMessage('error',r.error);
                    }else if(r.warning){
                        toastMessage('warning',r.warning);
                    }
                } catch (e) {
                    r =JSON.parse(r);
                    toastMessage('error',r);
                }
            },
            error : function(xhr, status) {},
            beforeSend: function(){}
        });
    });
}

function loadCart() {
    $.ajax({
        method: "POST",
        url: "index.php?controller=Articulo&action=loadCart",
        cache : "false",
        success : function(r) {
            try {
                $("#bodycart").html(r);
                calculoVenta();
                $(".infinite-linear").html("");
            } catch (error) {
                console.log(error)
            }
           
        },
        error : function(xhr, status) {
        },
        beforeSend: function(){
            $(".infinite-linear").html(linearLoading());
            
        }
        })
}
function deleteItem(id){
    $.post("index.php?controller=Articulo&action=deleteItemToCart",{data:id}, function(r) {
        loadCart();
      });
      calculoVenta();
}

function calculoVenta(){
    data = $("#detalleComprobante option:selected").val();
    contabilidad = $("#contabilidad").val();
    $.post("index.php?controller=Ventas&action=calculoVenta",{data:data,contabilidad:contabilidad}, function(r) {
        try {
            $("#calculoVenta").html(r);
        } catch (error) {
            console.log(error)
        }
      });
}

function sendVenta(){
    //preparar datos
    //preparando informacion
    var cont = $("#contabilidad").val();
    var x = $("#formVenta").serializeArray();
    data ="";
    $.each(x, function(i, field){
    data+=',"'+field.name+'":"'+field.value+'"'
    });
    data=JSON.parse('{'+data.substr(1)+'}');


    $.ajax({
        method: "POST",
        url: "Venta&action=crearVenta",
        cache : "false",
        data: data,
        dataType : 'JSON',
        success : function(r) {
            try {
                if(r.type == "message"){
                    toastMessage(r.alertType,r.response,r.success);
                    $(".linearLoading").html("");
                }else if(r.type == "redirect"){
                    $(location).attr('href',"#"+r.success);
                }else{
                    toastMessage('error',r.error);
                }
            } catch (e) {
                toastMessage('error',e);
            }
            
        },
        error : function(xhr, status) {
            //$(".br-mainpanel").html("error en la consulta");
        },
        beforeSend: function(){
            $(".linearLoading").html(linearLoading());
        }
        })

    loadCart();
    
    
}

function updateVenta(){
    //preparar datos
    //preparando informacion 
    $("#updateVenta").addClass("disabled");
    $("#updateVenta").html("Enviando, Espere...");
    $("#updateVenta").removeAttr("id");
    
    //data = $("#formVenta").serializeArray();
    var x = $("#formVenta").serializeArray();
    data ="";
    $.each(x, function(i, field){
    data+=',"'+field.name+'":"'+field.value+'"'
    //data +=',"'+field.name+'":"'+field.value+'"'
    });
    data=JSON.parse('{'+data.substr(1)+'}');
    $.ajax({
        method: "POST",
        url: "index.php?controller=Ventas&action=updateVenta",
        cache : "false",
        data: data,
        success : function(r) {
            try {
                r =JSON.parse(r);
                $(location).attr('href',"#file/venta/"+r.success);
            } catch (error) {
                console,log(error)
            }
        },
        error : function(xhr, status) {
            //$(".br-mainpanel").html("error en la consulta");
        },
        beforeSend: function(){
            $(".br-mainpanel").html(loading());
        }
        })
    loadCart();
}

function nuevo_tercero(){
    $.post("index.php?controller=almacen&action=new_tercero&data=modal",data, function(r) {
        try {
            $(".modal-body").html(r);
        } catch (error) {
            console.log(error)
        }
    });
}

function history(){
    tercero = $("#proveedor").val();
    $.ajax({
        method: "POST",
        url: "index.php?controller=ventas&action=historyByClient",
        cache : "false",
        data: {tercero:tercero},
        success : function(r) {
            try {
                $(".modal-body").html(r);
            } catch (error) {
                console.log(error)
            }
        },
        error : function(xhr, status) {
        },
        beforeSend: function(){
            $(".modal-body").html(linearLoading());
        }
        })
}

function addimpuesto(){
    data = $(".select2imp option:selected").val();
    proceso = $('#contabilidad').val();
    $.ajax({
        method: "POST",
        url: "index.php?controller=Impuestos&action=addImpuestoToCart",
        cache : "false",
        data: {data:data,proceso:proceso},
        success : function(r) {
            loadCart();
            try {
                $(".linearLoading").html("");
            } catch (error) {
                console.log(error)
            }
        },
        error : function(xhr, status) {
        },
        beforeSend: function(){
            $(".linearLoading").html(linearLoading());
        }
        })
}

function addretencion(){
    data = $(".select2re option:selected").val();
    proceso = $('#contabilidad').val();
    $.ajax({
        method: "POST",
        url: "index.php?controller=Retencion&action=addRetencionToCart",
        cache : "false",
        data: {data:data,proceso:proceso},
        success : function(r) {
            loadCart();
            try {
                $(".linearLoading").html("");
            } catch (error) {
                console.log(error)
            }
        },
        error : function(xhr, status) {
        },
        beforeSend: function(){
            $(".linearLoading").html(linearLoading());
        }
        })
}

function addmetodopago(){
    data = $(".select2mp option:selected").val();
    $.ajax({
        method: "POST",
        url: "Cart&action=addMetodoPagoToCart",
        cache : "false",
        data: {data:data},
        dataType : 'JSON',
        success : function(metodoPago) {
            try {
                if(metodoPago.estadoMetodoPago == false){
                    toastMessage('error', metodoPago.mensajeMetodoPago);
                }
                $(".linearLoading").html("");
                loadCart();
                loadMetodosPago();
            } catch (error) {
                console.log(error)
            }
        },
        error : function(xhr, status) {},
        beforeSend: function(){
            $(".linearLoading").html(linearLoading());
        }
    })
}

function loadMetodosPago() {
    $.ajax({
        method: "POST",
        url: "Cart&action=mostrarMetodosPago",
        cache : "false",
        data: {data:data},
        success : function(metodoPago) {
            $("#listMetodoPago").html(metodoPago);
        },
        error : function(xhr, status) {},
        beforeSend: function(){}
    })
}
</script>