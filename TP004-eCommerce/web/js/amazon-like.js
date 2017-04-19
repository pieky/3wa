$(function() {
    $('#carousel-random-products').carousel({
        interval: 2000
    });

    $(".notification-alert").delay(1500).slideUp("slow", function(){
        $(this).remove();
    });
});
