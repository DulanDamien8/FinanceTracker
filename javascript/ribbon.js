//jquery script responsible for the ribbon behaviour
//if window width is smaller that 960px then we hide the ribbon of the homepage
//and display the button which expands it,
//if it is bigger or equal then we show the ribbon


//if ribbon button is clicked we show the ribbon
$(document).ready(function(){
    $(".ribBtn").click(function(){
        $('.ribbon').show();
        $(".ribbonBtn").hide();
    });

    $(window).resize(function() {
        if ($(this).width() < 960) {
            $('.ribbon').hide();
            $('.ribbonBtn').show();
        } else {
            $('.ribbon').show();
            $('.ribbonBtn').hide();
        }
    });
});