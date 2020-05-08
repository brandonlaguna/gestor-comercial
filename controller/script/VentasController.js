$( document ).ready(init);
function init() {
$("#fecha_final").hide();
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
}
function addProduct(){
    var value = $("#codigo_contable").val();
}

function fecha_final(){
    
    estado = $("#formaPago").val();
    if(estado == "4" || estado =="on"){
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
    url: "index.php?controller=Ventas&action=sendItem",
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
            calculoVenta();
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
      calculoVenta();
}

function calculoVenta(){
    
    data = $("#detalleComprobante option:selected").val();
    contabilidad = $("#contabilidad").val();
    $.post("index.php?controller=Ventas&action=calculoVenta",{data:data,contabilidad:contabilidad}, function(r) {
        $("#calculoVenta").html(r);
      });
    
}

function sendVenta(){
    //preparar datos
    //preparando informacion 
    $("#sendVenta").addClass("disabled");
    $("#sendVenta").html("Enviando, Espere...");
    $("#sendVenta").removeAttr("id");
    var cont = $("#contabilidad").val();
    var x = $("#formVenta").serializeArray();
    data ="";
    $.each(x, function(i, field){
    data+=',"'+field.name+'":"'+field.value+'"'
    });
    data=JSON.parse('{'+data.substr(1)+'}');


    $.ajax({
        method: "POST",
        url: "index.php?controller=Ventas&action=crearVenta"+cont,
        cache : "false",
        data: data,
        success : function(r) {
            r =JSON.parse(r);
            $(location).attr('href',"#file/venta"+cont+"/"+r.success);
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
            console.log(r);
            r =JSON.parse(r);
            
            $(location).attr('href',"#file/venta/"+r.success);
        },
        error : function(xhr, status) {
            //$(".br-mainpanel").html("error en la consulta");
        },
        beforeSend: function(){
            $(".br-mainpanel").html(loading());
        }
        })

    // $.post("index.php?controller=Ventas&action=updateVenta",data, function(r) {
        
    //     r =JSON.parse(r);
    //     if(r.error){
    //         //console.log(r.error);
    //     }else{
    //         $(location).attr('href',"#file/venta/"+r.success);
    //     }
        
    // });

    loadCart();
    
    
}

function nuevo_tercero(){
    $.post("index.php?controller=almacen&action=new_tercero&data=modal",data, function(r) {
        $(".modal-body").html(r);
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
            $(".modal-body").html(r);
        },
        error : function(xhr, status) {
        },
        beforeSend: function(){
            $(".modal-body").html(linearLoading());
            
        }
        })
}