$(function() {
    //セレクトボックスが切り替わったら発動
    $('select').change(function() {
        $('.form-check-input').prop('checked', false);
    });
});
