function collapseMenu() {
    var menuText = $('.menu-item-label');
    if ($('body').hasClass('collapsed-menu')) {} else {
        $('body').addClass('collapsed-menu');
        $('.show-sub + .br-menu-sub').slideUp();
        menuText.addClass('op-lg-0-force');
        $('.br-sideleft').one('transitionend', function(e) {
            menuText.addClass('d-lg-none');
        });
    }
    return false;
}

function resetModalForm(idModal = null, idButton = null, loadTable = false, loadAlerts = false) {
    $('#' + idModal + ' .form-control').removeClass('is-valid is-invalid ').val(null).tooltip('dispose');
    $('#' + idModal + ' .selectpicker').selectpicker('setStyle', 'btn-outline-valid btn-outline-invalid', 'remove').selectpicker('val', null).tooltip('dispose');
    $('#' + idModal + ' :file').filestyle('clear');
    $('#' + idModal + ' .selectpicker').selectpicker('refresh');
    $('#' + idModal + ' input[type=hidden]').val(0);
    $('#imgArea').filestyle('clear');
    $('#' + idModal + ' .input-check-radio').iCheck('uncheck');
    $('#' + idModal + ' input[type=checkbox]').prop('checked', false)
    idModal != null ? $('#' + idModal).modal('hide') : '';
    idButton != null ? $('#' + idButton).removeAttr('disabled') : '';
    loadTable == true ? ajaxArticulo() : '';
    //loadAlerts == true ? alertasUsuarios():'';
}