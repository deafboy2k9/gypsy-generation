jQuery(document).ready(function($) {  //start of jquery
$('#border_color,#background_color').ColorPicker ({
            onSubmit: function(hsb, hex, rgb, el) {
		$(el).val(hex);
		$(el).ColorPickerHide();
	},
	onBeforeShow: function () {
                var html = '';
                var count = this.value.length;
                if (count != '3'){html = this.value;}
                else {
                    html += this.value.substr(0,1);
                    html += this.value.substr(0,1);
                    html += this.value.substr(1,1);
                    html += this.value.substr(1,1);
                    html += this.value.substr(2,1);
                    html += this.value.substr(2,1);
                }
		$(this).ColorPickerSetColor(html);
	}

})
.bind('keyup', function(){
	$(this).ColorPickerSetColor(this.value);
});

});// end of jquery
