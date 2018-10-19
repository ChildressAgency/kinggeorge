jQuery(document).ready(function($){
  $('#navbar .dropdown').hover(function () {
    $(this).addClass('open');
  }, function () {
    $(this).removeClass('open');
  });
});