jQuery(document).ready(function() {
    jQuery('.toggle-nav').click(function(e) {
        jQuery(this).toggleClass('toggled');
        jQuery( jQuery('.toggle-nav').attr('href')).toggleClass('toggled');
 
        e.preventDefault();
    });
});

jQuery(window).scroll(function() {
if (jQuery(this).scrollTop() > 1){  
    jQuery('#sticky-header').addClass("sticky");
  }
  else{
    jQuery('#sticky-header').removeClass("sticky");
  }
});