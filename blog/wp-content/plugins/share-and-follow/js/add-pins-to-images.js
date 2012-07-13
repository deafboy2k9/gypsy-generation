function add_pins(pinStyle){
      //



      if(typeof pinStyle === 'undefined'){
          pinStyle = 'all';
      }

      switch(pinStyle){
          case 'all':
             jQuery('[class*=" wp-image-"],.pin-it').each(function(){
                
                // $(this).addClass('make-relative');
                thisurl =  window.location;
                thisimageurl = jQuery(this).attr('src');
                attach_pin_to_image(jQuery(this), thisurl, thisimageurl );
                
             });
              break;
          case 'class':
              jQuery('.pin-it').each(function(){
                // $(this).addClass('make-relative');
                thisurl =  window.location;
                thisimageurl = jQuery(this).attr('src');
                attach_pin_to_image(jQuery(this), thisurl, thisimageurl );
             });
              break;

      }
  }

function attach_pin_to_image(obj, pageurl, imageurl ){
                pin = "<a href='http://pinterest.com/pin/create/button/?url=" + encodeURIComponent(pageurl) + "&media=" + encodeURIComponent(imageurl) + "' target='_blank' class='pin-it-image-button' ><span>Pin It</span></a>";
                var check_float = jQuery(obj).css('float');
                floated = ' no-float';
                if(check_float == 'left' || check_float == 'right'){
                    floated = 'align' + check_float;
                  
                }

                check_parent = jQuery(obj).parent('a').length;

                if(check_parent == true){
                    obj = jQuery(obj).parent('a');    
                }

                jQuery(obj).wrap("<span class='make-relative " + floated  + "' />");
                jQuery(obj).after( pin );
                

}