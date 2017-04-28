$(function() {

    /* -----------------------------------------------------
     ------ BORDEL A RANGER --------------------------------
     -----------------------------------------------------*/
        $('[data-toggle="popover"]').popover();

        $('#carousel-random-products').carousel({
            interval: 2000
        });

        $(".notification-alert").delay(2500).slideUp("slow", function(){
            $(this).remove();
        });

        $( "a.user-detail-modal" ).on( "click", function() {
            $( "#userModalTitle" ).text( $(this).attr("data-name") );
            $( "#userModalBody" ).text( $(this).attr("data-email") );
        });

        $('#currency-choice').selectpicker();
    /* -----------------------------------------------------
     ------ FIN --------------------------------------------
     -----------------------------------------------------*/

    $('.currency-choice').on('change', function(e) {
        var form = $(this).parent();
        var data = form.serialize();
        $.ajax({
            data:data,
            dataType: 'json',
            method: 'post',
            url: '/fr/ajax/change-currency'
        }).done(function() {
            form.submit();
        });
    });

    $('#form-add-to-cart').on('submit', function(e) {
        e.preventDefault();
        var data = $(this).serialize();

        $.ajax({
            data:data,
            method: 'post',
            url: '/fr/cart/add'
        }).done(function () {
            var succeed = $('.add-to-cart-succeed');
            if(succeed.hasClass('hidden')) {
                succeed.removeClass('hidden').delay(1500).fadeOut();
            } else {
                succeed.fadeIn().delay(1500).fadeOut();
            }

        });
    });

    $('.form-delete-from-cart').on('submit', function(e) {
        e.preventDefault();
        var data = $(this).serialize();
        var id = $(this).children(":first").val();
        $.ajax({
            data:data,
            method: 'post',
            url: '/fr/cart/delete'
        }).done(function() {
            $('#cart-product-'+id).remove();
        });
    });

    $('.product-qte').on('change', function(e) {
        var data = $(this).parent().serialize();
        $.ajax({
            data:data,
            method: 'post',
            url: '/fr/cart/update'
        }).done(function() {
            console.log('ok');
        });
    });


});
