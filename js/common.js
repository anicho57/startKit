
(function($) {
    $(function() {
        $("a[href^=#]:not([href$=#])").click( function() {
           var speed = 800;
           var href= $( this).attr( "href");
           var target = $(href == "#" || href == "" ? 'html' : href);
           var position = target.offset().top;
           $('body,html').animate({scrollTop:position}, speed, 'swing');
           return false;
        });

        $("a[href^='http://']:not([href*='" + location.hostname + "']),[href*='https://']:not([href*='" + location.hostname + "'])").attr('target', '_blank').addClass('blank');
        $("a[href$='.pdf']").attr('target', '_blank').addClass('pdf');
        $("a[href$='.jpg'],a[href$='.gif'],a[href$='.png']").addClass('thickbox');

    });

})(jQuery);
