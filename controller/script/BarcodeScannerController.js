/* Tras cargar el documento posicionamos el cursor en el lector de código de barras */
    $( document ).ready(init);
    function init() {
    let codigobarras = document.getElementById('codigo_barras');
    let cantidad = document.getElementById('cantidad');
    /* Ponemos el foco en el campo "codigo" */
    codigobarras.focus();
    /* Capturamos el evento de envío de formulario (pulsar ENTER o pulsar Enviar) */
    codigobarras.addEventListener("change", function(e) {
      /* Evitamos el envío real del formulario */
      e.preventDefault();
      /* Hacemos la llamada al API (busqueda.php o el API de ejemplo) */
      $.ajax({
        url: 'index.php?controller=Articulo&action=getItemByBarcode',
        method: 'post',
        data: {
          data: codigobarras.value,
        },
        beforeSend: function(){$(".infinite-linear").html(linearLoading());}
      })
      .done(function(r) {
        try {
          $(".infinite-linear").html("");
          /* Depuramos los datos recibidos */
          response = JSON.parse(r);
          if(response.length > 0){
              $.each(response[0], function(i, item) {
                $("#"+i).val(item);
          });
          $("#cantidad").val(1);
          }else{
            toastMessage('warning','Este codigo no existe en la tabla de articulos');
          }
          cantidad.focus();
        } catch (e) {console.log(e)}
        
      })
      .fail(function() {
        toastMessage('warning','Hubo un error al comunicar con el servidor');
      })
      .always(function() {
        /* Seleccionamos el texto para que se pueda sobreescribir por la siguiente lectura */
        $("input[name='codigo_barras']").select();
      });
    });
  }/* Tras cargar el documento posicionamos el cursor en el lector de código de barras */
