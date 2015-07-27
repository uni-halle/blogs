;jQuery(function($) {

    $('.duo-kb h3.hndle').click(function() {
        $(this).next('.inside').slideToggle();
    });

    if( $('.duo_prod_panel input').length ){
        $('.duo_prod_panel input').iCheck({
            checkboxClass: 'icheckbox_square',
            radioClass: 'iradio_square',
            increaseArea: '20%' // optional
        });
    }

    if( $('.duo_prod_panel select').length ){
        $('.duo_prod_panel select').selectize();
    }


});