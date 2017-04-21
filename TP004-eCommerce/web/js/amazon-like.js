$(function() {
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
});
