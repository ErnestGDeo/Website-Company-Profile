
jQuery(document).ready(function()
{
    //mobile menu show hide
    jQuery("header #mobile_menu_list ul").hide();
    jQuery("#mobile_menu").click(function(){
        jQuery("header #mobile_menu_list ul").slideToggle();
        return false;
    });
    jQuery("header #mobile_menu_list ul li a").click(function(){
        jQuery("header #mobile_menu_list ul").slideUp();
    });
    //single page nav
    jQuery("header ul").singlePageNav({offset: jQuery('header').outerHeight()});
    //open scroll function
    jQuery("html, body").animate({ scrollTop: 50 }, 0, function(){
        jQuery(this).animate({ scrollTop: 0 },1000);
    });
    //call flex slider function
    jQuery('#main-slider').flexslider();
    //scroll to top
    jQuery(window).scroll(function(){
        if(jQuery(this).scrollTop() > 100){
            jQuery('.scrollup').fadeIn();
        } else {
            jQuery('.scrollup').fadeOut();
        }
    });
    jQuery('.scrollup').click(function(){
        jQuery("html, body").animate({ scrollTop: 0 }, 1000);
        return false;
    });
    //lightbox
    jQuery('a.overlay').lightbox(); 
    
    

 // Tampilkan halaman pertama saat load
 showPage(currentPage);

});

