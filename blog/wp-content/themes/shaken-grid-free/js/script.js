jQuery.noConflict();
(function($) {

	$('span.view-large').hide();
	myOp = 1;
	
	var $container = $('#sort, .sort');
	
	// add randomish size classes
    $container.find('.box').each(function(){
    	var $this = $(this);
    	resize($this);
    });//end find each
     
      
	  $(window).load( function(){
		// Init	
		$container.isotope({
			itemSelector : '.box',		
			masonry: {
				isFitWidth: true,
				columnWidth : 240
			}
		});	
		
		//relayout the masonry boxes because it is not always correct.
		$('#sort, .sort').isotope('reLayout',null);
		
	  });//end .load
	
	mouseOver();
	
	//search box text behavior
	$('#s').blur(function(){
		var val = $('#s').val().trim();
		if(val == null || val == '')
		{
			$('#s').val('who/what/where?');
		}
	});
	
	//search box text behavior
	$('#s').focus(function(){
		var val = $('#s').val().trim();
		if(val == 'who/what/where?')
		{
			$('#s').val('');
		}
		
	});
	
	//add gallery class to single page image container
	$('.wide-col img:not(#sidebar img)').parent().addClass('gallery');
	// Colorbox
	$(".gallery").colorbox({
		rel: 'gal',
		maxWidth: '85%',
		maxHeight: '85%'
	});
	
	$(".member_avatar").colorbox({
		rel:'nofollow',
		html:function(){
					//display base information before getting the html
					$(this).find(".profile_info").css("display","block");
					$html = $(this).html();
					$button = $(this).parent().find(".action").html();
					if($button)
					{
						$html += '<div class="action" style="margin-top:15px; height:20px;">' + $button + '</div>';
					}
					return $html;
				},
		onClosed:function(){
					$(this).find(".profile_info").css("display","none");
				}
	})
	
	//buddypress admin
	$('#wp-admin-bar .padder').hover(function(){
			$('#wp-admin-bar .padder').css('opacity','1');
		},
		function(){
			$('#wp-admin-bar .padder').css('opacity','0');
		}
	);
	
})(jQuery);