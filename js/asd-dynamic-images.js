function set_dynamic_image_src( image_id, url_xs, url_sm, url_md, url_lg, url_xl, url_xxl ) {

   jQuery(document).ready( function() {

       var wwidth = jQuery(window).width();
       var newimage="";

       if (  wwidth < 768 ) {
          if ( url_xs ) { 
             newimage = url_xs;
          }
       }
       if ( wwidth >= 768  ) {
          if ( url_sm ) {
             newimage = url_sm;
          }
       }
       if ( wwidth >= 992  ) {
          if ( url_md ) {
             newimage = url_md;
          }
       }
       if ( wwidth >= 1200  ) {
          if ( url_lg != "") {
             newimage = url_lg;
          }
       }
       if ( wwidth >= 1600  ) {
          if ( url_xl != "" ) {
             newimage = url_xl;
          }
       }
       if ( wwidth >= 1900  ) {
          if ( url_xxl)  {
             newimage = url_xxl;
          }
       }
      if ( newimage ) {
          jQuery( image_id ).attr( "src", newimage );
      }
   });

}
