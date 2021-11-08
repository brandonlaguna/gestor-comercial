
$( document ).ready(init);
function init() {
    $("#fecha_final").hide();
    $("#factura_proveedor").hide();
    $("#nuevo_tercero").click(nuevo_tercero);
    $("#formaPago").change(fecha_final);
    $(".calculate").keyup(calculate);
    $(".calculate").change(calculate);
    $("#AddItem").click(sendItem);
    $("#detalleComprobante").change(calculoCompra);
    $("#sendCompra").click(sendCompra);
    //$("#updateCompra").click(updateCompra);
}

function fecha_final(){
    estado = $("#formaPago option:selected").text();
    if(estado.includes("Credito") || estado.includes("credito")){
        $("#fecha_final").show();
        $("#factura_proveedor").show();
    }else{
        $("#fecha_final").hide();
        $("#factura_proveedor").hide();
    }
}

function nuevo_tercero(){
    $.post("index.php?controller=almacen&action=new_tercero&data=modal",data, function(r) {
        $(".modal-body").html(r);
    });
}
function calculate(){
    var cantidad = $("#cantidad").val();
    if(cantidad !=""){
        cantidad=cantidad;
    }else{
        cantidad =1;
    }
    var precio_unitario = $("#costo_producto").val();
    var importe = $(".imp_compra option:selected").val();
    //obtener informacion del impuesto
    $.ajax({
        method: "POST",
        url: "index.php?controller=Impuestos&action=getImpuesto",
        cache : "false",
        data: {imp:importe},
        success : function(r) {
            $(".infinite-linear").html("");
            r = JSON.parse(r);
            var sub_total = parseInt(cantidad) * parseInt(precio_unitario);
            var total = parseInt((precio_unitario * cantidad) * ((r.im_porcentaje/100)+1)) ;
            $("#sub_total_compra").val(sub_total);
            $("#total_compra").val(total);
        },
        error : function(xhr, status) {
        },
        beforeSend: function(){
            $(".infinite-linear").html(linearLoading());
            
        }
        })
}

function sendItem(){
    var tercero = $("#proveedor").val();
  $("#ItemsToAdd tbody tr").each(function () {
   json ="";

   $(this).find("input").each(function () {
    $this=$(this);
      json+=',"'+$this.attr("id")+'":"'+$this.val()+'"'
   });

   $(this).find("select").each(function () {
    $this=$(this);
      json+=',"'+$this.attr("name")+'":"'+$(this).find('option:selected').val()+'"'
   });

   obj=JSON.parse('{'+json.substr(1)+'}');
   $.each(obj, function(i, item) {
    $("#"+i).val("");
});
$.ajax({
    method: "POST",
    url: "index.php?controller=Comprobantes&action=sendItem",
    cache : "false",
    data:{data:obj,pos:pos,tercero:tercero},
    success : function(r) {
        loadCart();
        try {
            r =JSON.parse(r);
            if(r.success){
                
            }else if(r.errors){
                $.each(r.errors, function(i, field){
                    toastMessage('error',r.errors[i].errors);
                });
            //$(".linearLoading").html("");
            }
        } catch (e) {
            r =JSON.parse(r);
            toastMessage('error',r);
        }
    },
    error : function(xhr, status) {
    },
    beforeSend: function(){
    }
    });
  });
  
}

function loadCart() {   
    var data = "comprobante";
      $.ajax({
        method: "POST",
        url: "index.php?controller=Articulo&action=loadCart",
        cache : "false",
        data: {data:data},
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
        //$("#calculoCompra").html(r); deshabilitado porque no se necesita la informacion por el momento
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
        url: "index.php?controller=Comprobantes&action=crearComprobante",
        cache : "false",
        data: data,
        success : function(r) {
            loadCart();
            try {
                
                r =JSON.parse(r);
                if(r.success){
                    $(location).attr('href',"#"+r.success);
                }else if(r.errors){
                        
                        $.each(r.errors, function(i, field){
                            toastMessage('error',r.errors[i].errors);
                        });
                              
                    $(".linearLoading").html("");
                }
            } catch (e) {
                r =JSON.parse(r);
                toastMessage('error',r);
            }
        },
        error : function(xhr, status) {
            //$(".br-mainpanel").html("error en la consulta");
        },
        beforeSend: function(){
            $(".linearLoading").html(linearLoading());
        }
        })
}
