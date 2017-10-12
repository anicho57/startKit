

(function($) {
  'use strict';

  var $Base = function (option) {
    this.start();
  };

  $Base.prototype = {
    option: {},

    start: function() {
      var self = this;
      $('html').removeClass('no-js');
      self.pageLoading();
      self.pageScroll();
      // self.labelStat('is-checked');
      // self.linkStyle('js-popup-image');
      self.spMenuClass();
      self.accordion();
      self.imagePopup();
    },

    pageLoading : function(classname, delay) {
      var self = this;
      $('body').addClass('is-loading');
      $(window).on('load', function() {
        window.setTimeout(function() {
          $('body').removeClass('is-loading');
        }, 0);
        // window.setTimeout(function() {
        //   $('body').removeClass(classname);
        // }, 10000);
      });
      // $(document).on('click','a',function(e){
      //   if ($(this).attr('target') !== '_blank'){
      //     if ( $(this).attr('href').indexOf("#") !== 0 ){
      //       if ($(this).attr('class') !== undefined){
      //         if ( $(this).attr('class').indexOf("js") === -1 ){
      //           $('body').addClass(classname);
      //         }
      //       }else{
      //           $('body').addClass(classname);
      //       }
      //     }
      //   }
      // });
    },

    imagePopup : function() {
      $(window).load(function(){
        setTimeout(function(){
          $('.js-popup-image').each(function(index, el) {
            if (!$(this).find('img').length) {
              $(this).append('<img style="height:0" >');
            }
          });
          $('.js-popup-image').photoSwipe();
        }, 0);
      });
    },

    pageScroll : function() {
      var self = this;
      $('a[href^="#"]:not([href$="#"])').on("click", function(e) {
        e.preventDefault();
        var href= $( this).attr( "href"),
          target = $(href === "#top" || href === "" ? 'html' : href),
          position = target.offset().top;
          // position -= self.pageScrollOffset();
        $('body,html').animate({scrollTop:position}, 800, 'swing');
      });
    },

    pageScrollOffset : function( element ) {
      var h;
      if ( this.displayStat($('.nav-main'))){
        h = $('.nav-main').outerHeight(true);
      }else{
        h = $('.page-header').outerHeight(true);
      }
      return h;
    },

    linkStyle : function() {
      var self = this;
      $('a[href^="http"]:not([href*="' + location.hostname + '"])').attr('target', '_blank').addClass('blank');
      $('a[href$=".pdf"]').attr('target', '_blank').addClass('pdf');
      $('a').filter(function(){return /\.(jpe?g|png|gif)$/i.test(this.href);}).addClass(self.option.linkstyle.modalclassname);
    },

    labelStat : function() {
      var self = this,
          label = $('label');
      label.find(':checked').closest('label').addClass(self.option.labelstat.classname);
      label.on('click', function() {
        label.filter('.checked').removeClass(self.option.labelstat.classname);
        label.find(':checked').closest(label).addClass(self.option.labelstat.classname);
      });
    },

    displayStat : function ( element ) {
      return (element.css('display') === 'block') ? true : false;
    },

    spMenuClass : function() {
      var button = $('#js-sp-menu');
      button.on('click', function() {
        $('#js-nav-sp,#js-sp-menu').toggleClass('is-open');
      });
    },

    accordion : function() {
      var button = $('.js-accordion > :first-child');
      var body = button.next();
      body.hide();
      button.on('click', function(){
        var it = this;
        body = $(this).next();
        body.stop().slideToggle(800, function(){
          if (body.css('display') === 'block'){
            $(it).addClass('is-open');
          }else{
            $(it).removeClass('is-open');
          }
          // $(window).trigger('scroll');
        });
      });
    },

  };

  $(function() {
    var $base = new $Base();
  });

})(jQuery);

// Google Analytics
// (function(i,s,o,g,r,a,m){i['GoogleAnalyticsObject']=r;i[r]=i[r]||function(){
// (i[r].q=i[r].q||[]).push(arguments)},i[r].l=1*new Date();a=s.createElement(o),
// m=s.getElementsByTagName(o)[0];a.async=1;a.src=g;m.parentNode.insertBefore(a,m)
// })(window,document,'script','//www.google-analytics.com/analytics.js','ga');

// ga('create', 'UA-xxxxxxxx-1', 'auto');
// ga('send', 'pageview');
