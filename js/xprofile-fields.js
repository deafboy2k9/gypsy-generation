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