var $j = jQuery;

function inner_sub_menu_generator() {
  if ( $j( '#attorneys' ).length ) {
    $j( '.inner-sub-nav ul' ).append( '<li><a href="#attorneys">Attorneys</a></li>' );
  };
  if ( $j( '#recent-news' ).length ) {
    $j( '.inner-sub-nav ul' ).append( '<li><a href="#recent-news">Recent News</a></li>' );
  };
  if ( $j( '#profile' ).length ) {
    $j( '.inner-sub-nav ul' ).append( '<li><a href="#profile">Profile</a></li>' );
  };
}

$j(document).ready(function($) {
	inner_sub_menu_generator();

  // Scroll (in pixels) after which the "To Top" link is shown
  var offset = 300,
    //Scroll (in pixels) after which the "back to top" link opacity is reduced
    offset_opacity = 1200,
    //Duration of the top scrolling animation (in ms)
    scroll_top_duration = 700,
    //Get the "To Top" link
    $back_to_top = $('.to-top');


  // Affix from Bootstrap
  $('#sidebar-affix .widget').affix({
    offset: {
      top: function() {
        return $('#sidebar-affix').offset().top;
      },
      bottom: function () {
        return $('.below-content').outerHeight(true) + $('.site-footer').outerHeight(true) + 100;
      }
    }
  });

  $('#navbar-affix').affix({
    offset: {
      top: function() {
        return $('.inner-scrollspy').offset().top;
      }
    }
  });

  // Scrollspy from bootstrap
  $('body').scrollspy({ target: '#navbar-affix' });


  //Visible or not "To Top" link
  $(window).scroll(function(){
    ( $(this).scrollTop() > offset ) ? $back_to_top.addClass('top-is-visible') : $back_to_top.removeClass('top-is-visible top-fade-out');
    if( $(this).scrollTop() > offset_opacity ) {
      $back_to_top.addClass('top-fade-out');
    }
  });

  //Smoothy scroll to top
  $back_to_top.on('click', function(event){
    event.preventDefault();
    $('body,html').animate({
      scrollTop: 0 ,
      }, scroll_top_duration
    );
  });

  // Email address disclaimer modal popup
  $('a[href^="mailto:"]:not(#email-agree)').click(function(event) {
    event.preventDefault();
    $('#email-disclaimer #email-agree').attr('href', $(this).attr('href'));
    $('#email-disclaimer').modal('show');
  });
});
