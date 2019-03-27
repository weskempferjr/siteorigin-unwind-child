(function($) {
    "use strict"; // Start of use strict


    /*
     *  Code to run testimonial slider
     */
    var displayIndex = 0;
    var slideCount =  $('.testimonial-container').length;

    $('.testimonial-container').eq( 0).removeClass('ct-hidden');

    var interval = setInterval(function(){

        $('.testimonial-container').addClass('ct-hidden');
        $('.testimonial-container').eq( displayIndex).removeClass('ct-hidden');

        displayIndex++;
        displayIndex = displayIndex >= slideCount ? 0 : displayIndex;


    }, 15000);

    // End testimonial slider code.

    // Add shadow to sticky menu
    $('#masthead .top-bar').addClass('z-depth-1');



})(jQuery);