jQuery(document).ready(function($){

    $('#vendor-slideshow-wrapper #slideshow-main ul').after('<ul id="vendor-slideshow-nav" class="jcarousel-skin-oo">').cycle({
      fx: 'fade',
      speed: 'slow',
      timeout: 0,
      pager: '#vendor-slideshow-nav',
      width: 570,

      pagerAnchorBuilder: function(idx, slide) {
      var slideSRC = $(slide).find('img').attr('src');
      var slideTitle = $(slide).find('img').attr('title');
      return '<li><a href="#"><img src="' + slideSRC + '" width="75" style="max-height:65px;padding-top:5px;" /></a></li>';
      }   
    }); 

  /* find the tallest Vendor image */
  var tallest = 0;


  // get a count of the slides
  var thecount = $('#vendor-slideshow-nav li').length;

  // convert the auto generated pager list from Cycle to a jCarousel 
  $('#vendor-slideshow-nav').jcarousel({
    scroll: 5,
    visible: 5
  });
  
  // if there is only one image, then hide the carousel
  if ( thecount <= 1 ) {

    $('.jcarousel-container').css('display','none');

  }


});
