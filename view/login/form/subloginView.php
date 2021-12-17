<div class="d-flex align-items-center justify-content-center bg-gray-200 ht-500 pd-x-20 pd-xs-x-0">
            <div class="card wd-350 shadow-base">
            <div class="linear-loading"></div>
              <div class="card-body pd-x-20 pd-xs-40">
                <h5 class="tx-xs-24 tx-normal tx-gray-900 mg-b-15">Ingresa la clave segura</h5>

                <p class="mg-b-30 tx-14">No recuerdas la clave segura? <a href="#refacturacion/">Recuperar</a></p>
                <div class="form-group">
                <input type="hidden" name="pos" id="pos" value="<?=$main_url?>">
                  <input class="form-control" type="password" name="code" placeholder="****" id="code">
                </div><!-- form-group -->
                <div class="form-group">
                <select class="form-control select2" data-placeholder="Selecciona" name="redirect" id="redirect">
                  <option value="#compras/reg_contable">Compras Contables</option>
                  <option value="#ventas/reg_contable">Ventas Contables</option>
                </select>
                </div><!-- form-group -->

                <button class="btn btn-info btn-block" id="sendLogin">Continuar</button>
              </div><!-- card-body -->
            </div><!-- card -->
            
          </div><!-- d-flex -->
          <script src="lib/select2/js/select2.min.js"></script>
          <script src="controller/script/sublogin.js"></script>
          <script>
      $(function(){

        'use strict' 
        if($().select2) {
    $('.select2').select2({
      minimumResultsForSearch: Infinity,
      placeholder: 'Choose one'
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
  $('.br-toggle').on('click', function(e){
          e.preventDefault();
          $(this).toggleClass('on');
        });

      });
    </script>