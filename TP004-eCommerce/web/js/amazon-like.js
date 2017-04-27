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

    var currencyChoice = $('.currency-choice');
    currencyChoice.on('change', onChangeCurrencyChoice);
    function onChangeCurrencyChoice(e){
        var data = $(this).parent().serialize();

        $.ajax({
            data:data,
            dataType: 'json',
            method: 'post',
            url: '/fr/ajax/change-currency',
            success: onSuccessCurrencyChoice
        })

        $(this).parent().submit();
    }

    function onSuccessCurrencyChoice(data){
    }
});
