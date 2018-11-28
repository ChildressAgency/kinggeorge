jQuery(document).ready(function($){
  $('#navbar .dropdown').hover(function () {
    $(this).addClass('open');
  }, function () {
    $(this).removeClass('open');
  });

  $('#search-icon-menu').on('click', function(e){
    e.preventDefault();
    $('#search-bar').toggleClass('open');
  });

  $('.share-link').on('focus', function(){
    $(this).select();
  });

  if(typeof $.fn.slick == 'function'){
    $('.instagram-feed').slick({
      slidesToShow: 4,
      slidesToScroll: 1,
      infinite: true,
      variableWidth: true,
      //centerMode: true,
      //centerPadding: '60px',
      dots: false,
      arrows: true,
      nextArrow: $('.instagram_next'),
      prevArrow: $('.instagram_prev'),
      autoplay: true,
      autoplaySpeed: 7000
    });
  }
});