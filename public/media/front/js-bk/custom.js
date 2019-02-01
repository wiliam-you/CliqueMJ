$(document).ready(function(){

 var banner_Ht = window.innerHeight - $('header').innerHeight();
    $(window).scroll(function(){
      var sticky = $('body'),
          scroll = $(window).scrollTop();

      if (scroll >= 200) sticky.addClass('sticky-header');
      else sticky.removeClass('sticky-header');
});
   

$('#sliderr').owlCarousel({
    loop:true,
    margin:10,
    nav:true,
    pagination : true,
    autoplay:true,
    navText: [
            "<i class='fa fa-angle-left'></i>",
            "<i class='fa fa-angle-right'></i>"
    ],
    responsive:{
        0:{
            items:1
        },
        600:{
            items:3
        },
        1000:{
            items:5
        }
    }
});


}); 

new WOW().init();





