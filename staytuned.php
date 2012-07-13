<!DOCTYPE html>
<html>
<head>
<title>Gypsy Generation</title>
<meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />
<link rel="stylesheet" type="text/css" href="/css/staytuned.css" />
<link href='http://fonts.googleapis.com/css?family=Quicksand|Josefin+Sans:100,400,600' rel='stylesheet' type='text/css' />
<script type='text/javascript' src="/js/jquery-1.7.1.min.js"></script>
<script type='text/javascript' src="/js/utils.js"></script>
<script type='text/javascript'>
//<![CDATA[
// ]]>
</script>
<?php 
 function alert($valid)
 {
 	if($valid == 1)
 	{	
	 	echo '
 		<script type="text/javascript">
 			alert("Thanks! You\'ll be hearing more about this project from us shortly. In the meantime, follow Gypsy Generation on Facebook, Twitter and Tumblr. Peace & Love.");
 		</script>
 		';
 	}
 	else if($valid == 0)
 	{
 		echo '
 		<script type="text/javascript">
 			alert("The Email Address you entered does not appear to be valid");
 		</script>
 		';
 	}
 }
?>
</head>
<body onload="init();">
	<img id="play" class="controls" src="/img/000551-black-ink-grunge-stamp-texture-icon-media-a-media27-pause-sign.png" alt="Play" title="Like what you hear? Follow us on Facebook to get your daily music doses." onclick="pause();">
	<img id="next" class="controls" src="img/000548-black-ink-grunge-stamp-texture-icon-media-a-media24-arrows-seek-forward.png" alt="Next" title="Like what you hear? Follow us on Facebook to get your daily music doses." onclick="shuffle();">
	<audio autoplay="autoplay">
	</audio>
	<div id="container">
		<div class="gif">
			<img class="gif" src="img/gypsy-coming-soon.gif" title="stay tuned!" alt="postit">
		</div>
		<div class="form">
			<div class="email">
				be the first to find out more. leave your email address here.
			</div>
			<form action="email.php" method="post">
				<table>
					<tr>
						<td>
							<input class="input" type="text"  name="email"/>
						</td>
						<td>
							<input 	class="submit" type="submit" value="join us"/>
						</td>
						<td>
							<div class="follow">
								<img class="follow" src="img/facebook-logo-webtreats.png" alt="Facebook" title="Facebook" onclick="window.open('http://www.facebook.com/pages/GypsyGeneration/154579501238072', '_blank');">
							</div>
						</td>
						<td>
							<div class="follow">
								<img class="follow" src="img/twitter-bird3-webtreats.png" alt="Twitter" title="Twitter" onclick="window.open('http://twitter.com/gypsygeneration', '_blank');">
							</div>
						</td>
						<td>
							<div class="follow">
								<img class="follow" src="img/tumblr-webtreats.png" alt="Tumblr" title="Tumblr" onclick="window.open('http://gypsygeneration.tumblr.com/', '_blank');">
							</div>
						</td>
					</tr>
				</table>
			</form>
		</div>
	</div>
</body>
</html>
