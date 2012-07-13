jQuery(document).ready(function($) {
	$("input[name^='media-credit']").keyup(function (event) {
		var author = '<a href="profile.php">' + $("div#user_info a").html() + '</a>';
		var separator = $("input[name='media-credit[separator]']").val();
		var organization = $("input[name='media-credit[organization]']").val();
		$("span#preview").html(author + separator + organization);
	});
});
