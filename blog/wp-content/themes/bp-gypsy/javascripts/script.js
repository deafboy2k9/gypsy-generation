//jQuery.noConflict();
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
	
	//mouseOver();
	
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
	
	/*$(".member-info").colorbox({
		rel:'nofollow',
		html:function(){
					//display base information before getting the html
					$(this).find(".item").css("display","block");
					$html = $(this).html();
					console.log($html);
					$button = $(this).parent().find(".action").html();
					if($button)
					{
						$html += '<div class="action" style="margin-top:15px; height:20px;">' + $button + '</div>';
					}
					return $html;
				},
		onClosed:function(){
					$(this).find(".info").css("display","none");
				}
	})*/
	
	//buddypress admin
	$('#wp-admin-bar .padder').hover(function(){
			$('#wp-admin-bar .padder').css('opacity','1');
		},
		function(){
			$('#wp-admin-bar .padder').css('opacity','0');
		}
	);
	
})(jQuery);

(

		function(jQ){
			//outerHTML method (http://stackoverflow.com/a/5259788/212076)
			jQ.fn.outerHTML = function() {
				$t = jQ(this);
				if( "outerHTML" in $t[0] ){ 
					return $t[0].outerHTML; 
				}
				else
				{
					var content = $t.wrap('<div></div>').parent().html();
					$t.unwrap();
					return content;
				}
			}

			bpd =
			{
			
			init : function(){
								
					//add image field type on Add/Edit Xprofile field admin screen
				   if(jQ("div#poststuff select#fieldtype").html() !== null){

						if(jQ('div#poststuff select#fieldtype option[value="country"]').html() === null){
							var countryOption = '<option value="country">Country select list</option>';
							jQ("div#poststuff select#fieldtype").append(countryOption);

							var selectedOption = jQ("div#poststuff select#fieldtype").find("option:selected");
							if((selectedOption.length == 0) || (selectedOption.outerHTML().search(/selected/i) < 0)){
								var action = jQ("div#poststuff").parent().attr("action");

								if (action.search(/mode=edit_field/i) >= 0){
									jQ('div#poststuff select#fieldtype option[value="country"]').attr("selected", "selected");
								}
							}
						}
						
						if(jQ('div#poststuff select#fieldtype option[value="links"]').html() === null){
							var linksOption = '<option value="links">Links</option>';
							jQ("div#poststuff select#fieldtype").append(linksOption);

							var selectedOption = jQ("div#poststuff select#fieldtype").find("option:selected");
							if((selectedOption.length == 0) || (selectedOption.outerHTML().search(/selected/i) < 0)){
								var action = jQ("div#poststuff").parent().attr("action");

								if (action.search(/mode=edit_field/i) >= 0){
									jQ('div#poststuff select#fieldtype option[value="links"]').attr("selected", "selected");
								}
							}
						}
						
						if(jQ('div#poststuff select#fieldtype option[value="group"]').html() === null){
							var linksOption = '<option value="group">Primary Group</option>';
							jQ("div#poststuff select#fieldtype").append(linksOption);

							var selectedOption = jQ("div#poststuff select#fieldtype").find("option:selected");
							if((selectedOption.length == 0) || (selectedOption.outerHTML().search(/selected/i) < 0)){
								var action = jQ("div#poststuff").parent().attr("action");

								if (action.search(/mode=edit_field/i) >= 0){
									jQ('div#poststuff select#fieldtype option[value="group"]').attr("selected", "selected");
								}
							}
						}
						

					}

				}
			};
			
			jQ(document).ready(function(){
					bpd.init();
			});
					
		}

	)(jQuery);