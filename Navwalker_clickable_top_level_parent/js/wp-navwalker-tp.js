jQuery(document).ready(function() { 


  // Add the font-awesome chevron-down icon to the top level parent with dropdown elements.
  function enable_caret_dropdown() { 
      if('.menu-item-has-children') { 
        jQuery('.menu-item-has-children').children('a').append('<button data-toggle="dropdown" class="nw-cst-button nw-dropdown-position"><i class="fa fa-chevron-down" aria-hidden="true"></i></button>');
      }
    caret_dropdown();
  };

  // Add dropdown functionality to the chevron-down icon.
  function caret_dropdown() {
      jQuery('.nw-cst-button').on('click',function(){
        var thisElem = jQuery(this).closest('li');
        jQuery('.menu-item').not(this).removeClass('open');
        jQuery(thisElem).toggleClass('open');
      });
    caret_close();
  };

  // Close the dropdown on mouseleave.
  function caret_close() { 
      jQuery('ul[role="menu"]').on('mouseleave',function(){ 
        jQuery(this).closest('li.open').removeClass('open');
      });
  };

  enable_caret_dropdown();
});
