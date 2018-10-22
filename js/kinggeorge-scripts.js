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
});