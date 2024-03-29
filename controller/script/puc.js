$( document ).ready(init);
function init() {
  codigo_contable();
  autocomplete_articulo();
  get_clients();
  codigo_contableby();
}


function autocomplete(pos, arr) {
    inp = document.getElementById(pos);
    /*the autocomplete function takes two arguments,
    the text field element and an array of possible autocompleted values:*/
    var currentFocus;
    /*execute a function when someone writes in the text field:*/
    inp.addEventListener("input", function(e) {
        var a, b, i, val = this.value;
        /*close any already open lists of autocompleted values*/
        closeAllLists();
        if (!val) { return false;}
        currentFocus = -1;
        /*create a DIV element that will contain the items (values):*/
        a = document.createElement("DIV");
        a.setAttribute("id", this.id + "autocomplete-list");
        a.setAttribute("class", "autocomplete-items shadow p-3 mb-5 bg-white rounded");
        a.setAttribute("style", "z-index:20;position:absolute;");
        /*append the DIV element as a child of the autocomplete container:*/
        this.parentNode.appendChild(a);
        /*for each item in the array...*/
        for (i = 0; i < arr.length; i++) {
          /*check if the item starts with the same letters as the text field value:*/
          if (arr[i].substr(0, val.length).toUpperCase() == val.toUpperCase() ) {
//|| arr[i].toUpperCase().includes(val.toUpperCase())
            /*create a DIV element for each matching element:*/
            b = document.createElement("DIV");
            /*make the matching letters bold:*/
            b.innerHTML = "<strong>" + arr[i].substr(0, val.length) + "</strong>";
            b.innerHTML += arr[i].substr(val.length);
            /*insert a input field that will hold the current array item's value:*/
            b.innerHTML += "<input type='hidden' value='" + arr[i] + "'>";
            /*execute a function when someone clicks on the item value (DIV element):*/
            b.addEventListener("click", function(e) {
                //console.log(this.getElementsByTagName("input")[0].value);
                /*insert the value for the autocomplete text field:*/
                getItem(this.getElementsByTagName("input")[0].value);
                inp.value = this.getElementsByTagName("input")[0].value;
                /*close the list of autocompleted values,
                (or any other open lists of autocompleted values:*/
                closeAllLists(); 
            });

            a.appendChild(b);
          }
        }
    });
    /*execute a function presses a key on the keyboard:*/
    inp.addEventListener("keydown", function(e) {
        var x = document.getElementById(this.id + "autocomplete-list");
        if (x) x = x.getElementsByTagName("div");
        if (e.keyCode == 40) {
          /*If the arrow DOWN key is pressed,
          increase the currentFocus variable:*/
          currentFocus++;
          /*and and make the current item more visible:*/
          addActive(x);
        } else if (e.keyCode == 38) { //up
          /*If the arrow UP key is pressed,
          decrease the currentFocus variable:*/
          currentFocus--;
          /*and and make the current item more visible:*/
          addActive(x);
        } else if (e.keyCode == 13) {
          /*If the ENTER key is pressed, prevent the form from being submitted,*/
          e.preventDefault();
          if (currentFocus > -1) {
            /*and simulate a click on the "active" item:*/
            if (x) x[currentFocus].click();
          }
        }
    });
    function addActive(x) {
      /*a function to classify an item as "active":*/
      if (!x) return false;
      /*start by removing the "active" class on all items:*/
      removeActive(x);
      if (currentFocus >= x.length) currentFocus = 0;
      if (currentFocus < 0) currentFocus = (x.length - 1);
      /*add class "autocomplete-active":*/
      x[currentFocus].classList.add("autocomplete-active");
    }
    function removeActive(x) {
      /*a function to remove the "active" class from all autocomplete items:*/
      for (var i = 0; i < x.length; i++) {
        x[i].classList.remove("autocomplete-active");
      }
    }
    function closeAllLists(elmnt) {
      /*close all autocomplete lists in the document,
      except the one passed as an argument:*/
      var x = document.getElementsByClassName("autocomplete-items");
      for (var i = 0; i < x.length; i++) {
        if (elmnt != x[i] && elmnt != inp) {
          x[i].parentNode.removeChild(x[i]);
        }
      }
    }
    /*execute a function when someone clicks in the document:*/
    document.addEventListener("click", function (e) {
        closeAllLists(e.target);
    });
  }
  


  /*An array containing all the country names in the world:*/
  //ajax whit the data
  //clients
  pos = $("#pos").val();
  
  if(pos == "Ingreso"){
      data = "Proveedor";
  }else if(pos == "Venta"){
    data = "Cliente";
  }else{
    data ="";
  }

  function codigo_contable(){
    $.post("index.php?controller=sucursal&action=getPuc",{data:data}, function(response) {
      
      try{
        res = JSON.parse(response);
        var puc = res;
        document.getElementById("codigo_contable").onclick = function() {autocomplete("codigo_contable",puc)};
    }catch(e){}
  });
  }

  function codigo_contableby(){
    var attr = $("#codigo_contableby").attr("attr");
    var param = $("#codigo_contableby").attr("param");
    $.post("index.php?controller=sucursal&action=getPucBy",{attr:attr,param:param}, function(response) {
     
      try{
        res = JSON.parse(response);
        var puc = res;
        document.getElementById("codigo_contableby").onclick = function() {autocomplete("codigo_contableby",puc)};
    }catch(e){}
  });
  }
  

function autocomplete_articulo(){
  $.post("index.php?controller=Articulo&action=autoCompleteArticulo",{data:data}, function(response) {
    
    try{
      res = JSON.parse(response);
      var articulo = res;
      document.getElementById("nombre_articulo").onclick = function() {autocomplete("nombre_articulo",articulo)};
    }catch(e){}
  });
}


function get_clients() {
  $.post("index.php?controller=sucursal&action=getclients",{data:data}, function(response) {
    
    try{
      res = JSON.parse(response);
      cliente = res;
      document.getElementById("proveedor").onclick = function() {autocomplete("proveedor", cliente)};
  }catch(e){}
  });
}


function getItem(value){
  $.post("index.php?controller=Articulo&action=getItem",{data:value}, function(r) {
    try {
      response = JSON.parse(r);
      //console.log(response);
      if(r != "[]"){
        $.each(response, function(i, item) {
          $("#"+i).val(item);
        });
        $("#cantidad").val(1);
      }else{

      }
    } catch (e) {}
  });
}

  /*initiate the autocomplete function on the "myInput" element, and pass along the countries array as possible autocomplete values:*/
  
