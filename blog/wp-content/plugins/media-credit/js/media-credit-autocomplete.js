function mediaCreditAutocomplete(id, currAuthorId, currAuthor) {
	var PLUGIN_DIR = "../wp-content/plugins/media-credit/"; //TODO: better way to do this?
	var inputField = "input.[id='attachments[" + id + "][media-credit]']"
	
	jQuery(inputField)
		.click(function() {
			this.select();
			if (this.value == currAuthor) {
		//		this.value = "";
				removeID(id);
			}
		})
		.blur(function() {
			if (this.value == "") {
			/*	
				this.value = currAuthor;
				addID(id, currAuthorId);
			*/
				removeID(id);
			}
		})
		/* --- For jQuery UI autocomplete
		.autocomplete({
			source: PLUGIN_DIR + "search.php",
			minLength: 2,
			select: function(event, ui) {
				addID(id, ui.item.id);
			}
		})*/
		.autocomplete(ajaxurl, {
		//	delay: 200
			extraParams: { action: 'media_credit_author_names' }
		})
		.result(function(event, data, formatted) {
			addID(id, data[1]);
		});
}

function addID(id, author) {
	jQuery("#media-credit-" + id).attr("value", author);
}

function removeID(id) {
	jQuery("#media-credit-" + id).attr("value", "");
}
