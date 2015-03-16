
(function($) {
    $(function() {
        $("a[href^=#]:not([href$=#])").on("click", function() {
           var speed = 800;
           var href= $( this).attr( "href");
           var target = $(href === "#top" || href === "" ? 'html' : href);
           var position = target.offset().top;
           $('body,html').animate({scrollTop:position}, speed, 'swing');
           return false;
        });

        $("a[href^='http://']:not([href*='" + location.hostname + "']),[href*='https://']:not([href*='" + location.hostname + "'])").attr('target', '_blank').addClass('blank');
        $("a[href$='.pdf']").attr('target', '_blank').addClass('pdf');
        $("a[href$='.jpg'],a[href$='.gif'],a[href$='.png']").addClass('thickbox');

        var label = $('label');
        label.find(":checked").closest("label").addClass("checked");
        label.click(function() {
          label.filter(".checked").removeClass("checked");
          label.find(":checked").closest(label).addClass("checked");
        });

    });

})(jQuery);

// Google Analytics
var _gaq = _gaq || [];
_gaq.push(['_setAccount', 'UA-xxxxxxxx-x']);
_gaq.push(['_trackPageview']);

(function() {
  var ga = document.createElement('script'); ga.type = 'text/javascript'; ga.async = true;
  ga.src = ('https:' == document.location.protocol ? 'https://ssl' : 'http://www') + '.google-analytics.com/ga.js';
  var s = document.getElementsByTagName('script')[0]; s.parentNode.insertBefore(ga, s);
})();
