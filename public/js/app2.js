// @ts-uncheck || ts-check
$(document).ready(function() {
    // konfirmasi delete
    $(document.body).on('submit', '.js-confirm', function() {
        var el = $(this)
        var text = el.data('confirm') ? el.data('confirm') : 'Anda yakin melakukan tindakan ini??'
        var c = confirm(text);
        return c;
    });

    //selectize
    $('.js-selectize').selectize({
        sortField: 'text'
    });

});