$( document ).ready(init);
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

//$("#detalleComprobante").change();
}
function addProduct(){
    var value = $("#codigo_contable").val();
}

function fecha_final(){
    estado = $("#formaPago").val();
    if(estado == "1" || estado =="on"){
        $("#fecha_final").show();
        $("#factura_proveedor").show();
    }else{
        $("#fecha_final").hide();
        $("#factura_proveedor").hide();
    }
}

function calculate(){
    var cantidad = $("#cantidad").val();
    if(cantidad !=""){
        cantidad=cantidad;
    }else{
        cantidad =0;
    }
    var precio_unitario = $("#costo_producto").val();
    var importe = $("#imp_compra").val();
    var sub_total = parseInt(cantidad) * parseInt(precio_unitario);
    var total = parseInt((precio_unitario * cantidad) * ((importe/100)+1)) ;
    $("#sub_total_compra").val(sub_total);
    $("#total_compra").val(total);
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
    url: "index.php?controller=Compras&action=sendItem",
    cache : "false",
    data:{data:obj,pos:pos,tercero:tercero},
    success : function(r) {
        loadCart();
    },
    error : function(xhr, status) {
    },
    beforeSend: function(){
    }
    });
  });
  
}

function loadCart() {   
      $.ajax({
        method: "POST",
        url: "index.php?controller=Articulo&action=loadCart",
        cache : "false",
        success : function(r) {
            $("#bodycart").html(r);
            calculoCompra();
            $(".infinite-linear").html("");
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
      calculoCompra();
}

function deleteItemDetalle(id){
    $.post("index.php?controller=Compras&action=deleteItemDetalle",{data:id}, function(r) {
        console.log(r);
        //loadCart();
      });
      //calculoCompra();
}
 
function calculoCompra(){
    if($("#detalleComprobante option:selected").val() != null){
        data = $("#detalleComprobante option:selected").val();
    }else{
        data =$("#detalleComprobante").val();
    }
    
    contabilidad = $("#contabilidad").val();
    $.post("index.php?controller=Compras&action=calculoCompra",{data:data,contabilidad:contabilidad}, function(r) {
        $("#calculoCompra").html(r);
      });
} 

function sendCompra(){
    var url="";
    //preparar datos
    //preparando informacion 
    //$("#sendCompra").addClass("disabled");
    //$("#sendCompra").html("Enviando, Espere...");
    //$("#sendCompra").removeAttr("id");
   var cont = $("#contabilidad").val();
    
    //data = $("#formCompra").serializeArray();
    var x = $("#formCompra").serializeArray();
    data ="";
    $.each(x, function(i, field){
    data+=',"'+field.name+'":"'+field.value+'"'
    //data +=',"'+field.name+'":"'+field.value+'"'
    });
    data=JSON.parse('{'+data.substr(1)+'}');

    $.ajax({
        method: "POST",
        url: "index.php?controller=Compras&action=crearCompra"+cont,
        cache : "false",
        data: data,
        success : function(r) {
            loadCart();
            console.log(r);
            r =JSON.parse(r);
            $(location).attr('href',"#file/ingreso"+cont+"/"+r.success);
        },
        error : function(xhr, status) {
            //$(".br-mainpanel").html("error en la consulta");
        },
        beforeSend: function(){
            $(".br-mainpanel").html(loading());
        }
        })

    // $.post("index.php?controller=Compras&action=crearCompra"+cont,data, function(r) {
    //     r =JSON.parse(r); 
    //     if(r.error){
    //         console.log(r.error);
    //     }else{
    //         setTimeout(function () {
    //         $(location).attr('href',"#file/ingreso"+cont+"/"+r.success);
    //         },300);
    //     }

    // });

    
    
    
}

function updateCompra(){
    //preparar datos
    //preparando informacion 
    $("#updateCompra").addClass("disabled");
    $("#updateCompra").html("Enviando, Espere...");
    $("#updateCompra").removeAttr("id");
    
    //data = $("#formCompra").serializeArray();
    var x = $("#formCompra").serializeArray();
    data ="";
    $.each(x, function(i, field){
    data+=',"'+field.name+'":"'+field.value+'"'
    //data +=',"'+field.name+'":"'+field.value+'"'
    });
    data=JSON.parse('{'+data.substr(1)+'}');

    // $.post("index.php?controller=Compras&action=updateCompra",data, function(r) {
    //     r =JSON.parse(r);
    //     if(r.error){
    //         console.log(r.error);
    //     }else{
    //         $(location).attr('href',"#file/ingreso/"+r.success);
    //     }
    // });

    $.ajax({
        method: "POST",
        url: "index.php?controller=Compras&action=updateCompra",
        cache : "false",
        data: data,
        success : function(r) {
            r =JSON.parse(r);
            $(location).attr('href',"#file/ingreso/"+r.success);
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
        $(".modal-body").html(r);
    });
}