function init()
{
	$(document).ready(function(){shuffle();});
}

function play()
{
	var myAudio = document.getElementsByTagName('audio')[0];
	
    $("#play").attr("src", "/img/000551-black-ink-grunge-stamp-texture-icon-media-a-media27-pause-sign.png");
    $("#play").attr("alt", "Pause");
    
    myAudio.load();
	myAudio.play();
}

// add listener function to ended event
function myAddListener()
{
    var myAudio = document.getElementsByTagName('audio')[0];
    myAudio.addEventListener('ended',shuffle,false);
}

function shuffle()
{
	var tracks = ["03_The_Future", "04_Soft", "05_Holes", "07_Hey_Wonderful", "AsobiSeksu", "Born_To_Die",
				"Breathless", "bruises", "Crystalised", "Home", "L8R", "lions_on_the_beach", "Milk",
				"Nothing_To_Give", "Open_Season", "Overboard", "SkeletonKey", "StayAway",
				"TheDive", "Thursday", "Wax", "Wires"];
				
	var trackNum = Math.floor(Math.random()*23);
	var htmlStr = '';

	htmlStr += '<source src="/music/'+tracks[trackNum]+'.mp3" type ="audio/mp3" />';
	htmlStr += '<source src="/music/'+tracks[trackNum]+'.ogg" type ="audio/ogg" />';
	
	$("audio").html(htmlStr);
	
	play();
	myAddListener();
}


function pause()
{
	 var myAudio = document.getElementsByTagName('audio')[0];
	 
       if (myAudio.paused)
       {
           myAudio.play();
           $("#play").attr("src", "/img/000551-black-ink-grunge-stamp-texture-icon-media-a-media27-pause-sign.png");
           $("#play").attr("alt", "Pause");
           $("#play").attr("title", "Pause");
        }
       else
       {
           myAudio.pause();
            $("#play").attr("src", "/img/000546-black-ink-grunge-stamp-texture-icon-media-a-media22-arrow-forward1.png");
             $("#play").attr("alt", "Play");
           $("#play").attr("title", "Play");
        }
}

  // listener function changes src
function myNewSrc() 
{
   var myAudio = document.getElementsByTagName('audio')[0];
    myAudio.src="http://homepage.mac.com/qt4web/myMovie.m4v";
    myAudio.load();
    myAudio.play();
 }
 
 //called by infinite scroll (once per content div)
 function resize(_box)
 {
 	var box = _box;
 	
	img = box.find('img');
	width = img.attr('width');
	height = img.attr('height');
	
	vid = box.find('iframe');
	
	//see if there is an image in the box
	if(img.length > 0)
	{
		//horizontal rectangle
		if(width > height)
		{
			//horizontal rectangle
			box.addClass('width2');
			img.attr('width', '480');
			img.removeAttr('height');
				
		}
		//vertical rectangle
		else if(width < height)
		{
			//vertical rectangle
			box.addClass('height2');
			img.removeAttr('width');
			img.attr('height', '480');
		}
		//square
		else
		{
			//big square
			if(width > 240)
			{
				//resize box
				box.addClass('height2');
				box.addClass('width2');
				img.attr('width', 480);
				img.attr('height', 480);
			}
			else//small square
			{
				img.attr('width', 240);
				img.attr('height', 240);
			}
			
		}
	}
	//if there is a video in the box
	else if(vid.length > 0)
	{
		//big square
		box.addClass('width2');
		//change class so there will be no mouseover events
		box.find('.click').attr('class','click_no_fade');
		//add css same css as .click
		box.find('.click_no_fade').css({
			'background':'#FFFFFF',
			'height':'50px',
			'overflow':'hidden',
			'opacity':'0.9',
			'z-index':'2',
			'position':'relative'
		})
		box.find('.featured_video').css('position','absolute');
		box.find('.featured_video').css('bottom','0px');
		fixEmbed(vid);
	}
 }
 
 function infScrollCallBack(newElements){
 	
 	//resize images and boxes in new posts
 	for(var i = 0; i < newElements.length; i++)
 	{
 		resize(jQuery(newElements[i]));
 	}
	
	jQuery('#sort, .sort').imagesLoaded(function (){
		jQuery('#sort, .sort').isotope( 'appended', newElements);
	});
	
	mouseOver();
	
	jQuery('#sort, .sort').isotope('reLayout',null);
	
 }
 
 function loadVideos(){
 	
	jQuery('#sort, .sort').find('div[id*="featvid"]').each(function (){
		
		var vidClass = jQuery(this).attr('class');
		var url = vidClass.substring(15, vidClass.length);
		var id = jQuery(this).attr('id');
		
		//setup video object
		jwplayer(id).setup({
			flashplayer: "http://www.gypsy-generation.com/blog/wp-content/uploads/jw-player-plugin-for-wordpress/player/player.swf",
			file:url,
			height:240,
			width:480
			});
		
		//apply new styles
		jQuery('#'+id+'_wrapper').css('position','absolute');
		jQuery('#'+id+'_wrapper').css('top','0px');
		
	});

 }
 
 function fixEmbed(iframe){
	var url = iframe.attr("src");
	
	//remove everything after the first get parameter
	url = url.substring(0, url.indexOf('?'));
	
	//only add new parameters to youtube videos to fix z-index problem
	if(url.search('vimeo') == -1)
	{
		iframe.attr("src",url+"?wmode=transparent");
	}
 }
 
 function mouseOver()
 {
	// MouseOver Events
	jQuery('.box').hover(function(){
			jQuery('img', this).fadeTo("fast", 0.75).addClass('box-hover');
			//only add fading to the caption area if there is no video
			jQuery('.click', this).fadeTo("fast", 0);
		},
		function(){
			jQuery('img', this).fadeTo("fast", myOp).removeClass('box-hover');
			//only add fading to the caption area if there is no video
			jQuery('.click', this).fadeTo("fast", 0.9);
		});
 }



