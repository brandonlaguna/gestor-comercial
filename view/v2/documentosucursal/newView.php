<div class="br-pagetitle"></div>
<div class="br-pagebody">
    <div class="br-section-wrapper">
        <div class="form-layout form-layout-1">
            <form id="save_comprobante" finish="documentoSucursal/guardarActualizar">
            <div class="row mg-b-25">
                <?=$this->component('formSelect',[
                    'title'     =>  'Documento ',
                    'col'       =>  'col-sm-12 col-md-4 col-lg-4',
                    'name'      =>  'documento',
                    'id'        =>  'documento',
                    'items'     =>  $documentosSucursal,
                    'selected'  =>  0,
                    'required'  =>  true
                ])?>

                <?=$this->component('formInput',[
                    'title'         =>  'Serie Comprobante',
                    'id'            =>  'serie',
                    'name'          =>  'serie',
                    'autocomplete'  =>  'off',
                    'placeholder'   =>  'Numero o letra serie del comprobante',
                    'col'           =>  'col-sm-12 col-md-4 col-lg-4'
                ])?>

                <?=$this->component('formInput',[
                    'title'         =>  'Consecutivo',
                    'id'            =>  'consecutivo',
                    'name'          =>  'consecutivo',
                    'autocomplete'  =>  'off',
                    'placeholder'   =>  'Para iniciar consecutivo use el numero "0" cero',
                    'col'           =>  'col-sm-12 col-md-4 col-lg-4'
                ])?>

                <?=$this->component('formSelect',[
                    'title'     =>  'Afecta Contabilidad? ',
                    'col'       =>  'col-sm-12 col-md-4 col-lg-4',
                    'name'      =>  'contabilidad',
                    'id'        =>  'contabilidad',
                    'items'     =>  [["item_id"=>0,"item_name"=>"NO"],["item_id"=>1,"item_name"=>"SI"]],
                    'selected'  =>  0,
                    'required'  =>  true
                ])?>

                <?=$this->component('formSelect',[
                    'title'     =>  'Tipo de formato',
                    'col'       =>  'col-sm-12 col-md-4 col-lg-4',
                    'name'      =>  'contabilidad',
                    'id'        =>  'contabilidad',
                    'items'     =>  $formatosImpresion,
                    'selected'  =>  0,
                    'required'  =>  true
                ])?>

                <?=$this->component('formSelect',[
                    'title'     =>  'Documento por defecto en Ventas POS',
                    'label'     =>  'Omitir si es para compras',
                    'col'       =>  'col-sm-12 col-md-4 col-lg-4',
                    'name'      =>  'contabilidad',
                    'id'        =>  'contabilidad',
                    'items'     =>   [["item_id"=>0,"item_name"=>"NO"],["item_id"=>1,"item_name"=>"SI"]],
                    'selected'  =>  0,
                ])?>

                <div class="col-lg-12">
                    <div class="form-group">
                        <label class="form-control-label">Resolucion de la factura: <span class="tx-danger">*</span></label>
                        <textarea rows="5" class="form-control mg-t-20" placeholder="Salto de linea '|' Barra vertical" name="resolucion"></textarea>
                    </div>
                </div><!-- col-4 -->
                
                
            </div>
            </form>
            </div>
            <div class="form-layout-footer mt-4">
                    <button class="btn btn-info" id="send" onclick="sendForm('save_comprobante')">Agregar</button>
                    <a href="#admin/conf_comprobante" class="btn btn-secondary">Cancelar</a>
                </div>
            
        </div>
    </div>
</div>


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