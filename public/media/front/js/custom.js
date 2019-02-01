$(document).ready(function(){
/******************* owl slider **********************/
$('#screenshot_slider').owlCarousel({
    loop:true,
    margin:10,
    nav:true,
    pagination : true,
    responsive:{
        0:{
            items:1
        },
        600:{
            items:1
        },
        1000:{
            items:5
        }
    },
    navText: [
            "<i class='fa fa-angle-left'></i>",
            "<i class='fa fa-angle-right'></i>"
        ],
});

/******************* stiky header **********************/
var banner_Ht = window.innerHeight - $('header').innerHeight();
    $(window).scroll(function(){
      var sticky = $('body'),
          scroll = $(window).scrollTop();

      if (scroll >= 200) sticky.addClass('sticky-header');
      else sticky.removeClass('sticky-header');
});

/******************* mob menue **********************/
    $('.mob-menu').click(function(){
        $('body').toggleClass('open_menu');
    });

/******************* scroll to top **********************/
    $(window).scroll(function() {
    if ($(this).scrollTop() > 50 ) {
        $('.scrolltop:hidden').stop(true, true).fadeIn();
    } else {
        $('.scrolltop').stop(true, true).fadeOut();
    }
});
$(function(){$(".scroll").click(function(){$("html,body").animate({scrollTop:$(".thetop").offset().top},"1000");return false})})




/******************* m custom scrollbar **********************/
    (function($){
        $(window).on("load",function(){
            $(".content").mCustomScrollbar();
        });
        })(jQuery);
    }); 

/******************* smooth Scroll **********************/

// Select all links with hashes
$('a[href*="#"]')
  // Remove links that don't actually link to anything
  .not('[href="#"]')
  .not('[href="#0"]')
  .click(function(event) {
    // On-page links
    if (
      location.pathname.replace(/^\//, '') == this.pathname.replace(/^\//, '') 
      && 
      location.hostname == this.hostname
    ) {
      // Figure out element to scroll to
      var target = $(this.hash);
      target = target.length ? target : $('[name=' + this.hash.slice(1) + ']');
      // Does a scroll target exist?
      if (target.length) {
        // Only prevent default if animation is actually gonna happen
        event.preventDefault();
        $('html, body').animate({
          scrollTop: target.offset().top
        }, 1000, function() {
          // Callback after animation
          // Must change focus!
          var $target = $(target);
          $target.focus();
          if ($target.is(":focus")) { // Checking if the target was focused
            return false;
          } else {
            $target.attr('tabindex','-1'); // Adding tabindex for elements not focusable
            $target.focus(); // Set focus again
          };
        });
      }
    }
  });

/******************* Active Class **********************/
// Add active class to the current link (highlight it)
$(document).ready(function () {
    $('.navigation-bar li a').click(function(e) {

        $('.navigation-bar li.active').removeClass('active');

        var $parent = $(this).parent();
        $parent.addClass('active');
        e.preventDefault();
    });
});

/******************* animated wow js **********************/
new WOW().init();
