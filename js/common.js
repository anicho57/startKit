
(function($) {
  'use strict';

  $(function() {
    $base.pageLoading('is-loading');
    $base.pageTop();
    $base.linkStyle();
    // $base.labelStat();
    // $base.cpYear('#js-now-year', 2016);
  });

  var $window = $(window);
  var $body = $('body');

  var $base = {
    pageLoading : function(classname, delay) {
      var d = delay || 0;
      $body.addClass(classname);
      $window.on('load', function() {
          window.setTimeout(function() {
              $body.removeClass(classname);
          }, d);
      });
    },

    pageTop : function() {
      $("a[href^=#]:not([href$=#])").on("click", function(e) {
         e.preventDefault();
         var href= $( this).attr( "href");
         var target = $(href === "#top" || href === "" ? 'html' : href);
         var position = target.offset().top;
         $('body,html').animate({scrollTop:position}, speed, 'swing');
      });
    },

    linkStyle : function() {
      $("a[href^='http://']:not([href*='" + location.hostname + "']),[href*='https://']:not([href*='" + location.hostname + "'])").attr('target', '_blank').addClass('blank');
      $("a[href$='.pdf']").attr('target', '_blank').addClass('pdf');
      $("a[href$='.jpg'],a[href$='.gif'],a[href$='.png']").addClass('thickbox');
    },

    labelStat : function() {
      var label = $('label');
      label.find(":checked").closest("label").addClass("checked");
      label.click(function() {
        label.filter(".checked").removeClass("checked");
        label.find(":checked").closest(label).addClass("checked");
      });
    },

    cpYear : function( selector, fromYear ) {
      var now = new Date();
      var html = (fromYear < now.getFullYear()) ? ' - ' + now.getFullYear() : '';
      $(selector).html(html);
    }
  };

})(jQuery);

// Google Analytics
// (function(i,s,o,g,r,a,m){i['GoogleAnalyticsObject']=r;i[r]=i[r]||function(){
// (i[r].q=i[r].q||[]).push(arguments)},i[r].l=1*new Date();a=s.createElement(o),
// m=s.getElementsByTagName(o)[0];a.async=1;a.src=g;m.parentNode.insertBefore(a,m)
// })(window,document,'script','//www.google-analytics.com/analytics.js','ga');

// ga('create', 'UA-xxxxxxxx-1', 'auto');
// ga('send', 'pageview');
