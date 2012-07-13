<?php

$c = new ShareAndFollow();

$cssOptions = $c->getCSSOptions();
$buildCss =''; // start of CSS or head section build
$printCSS = '';

if ($cssOptions['cssid']==$c->_options['cssid'] && $c->_options['add_css']=='false'){
   $buildCss =  $cssOptions['screen'];
   $printCSS =  $cssOptions['print'];
}
else {
if ($c->_options['add_css']=='true'){

$buildCss .="/* cssid=".$c->_options['cssid']."                            */   \n";
$buildCss .="/* WARNING!! this file is dynamicaly generated changes will  */ \n";
$buildCss .="/* be overwritten with every change to the admin screen.      */ \n";
$buildCss .="/* You can add css to this file in the admin screen.       */ \n\n";
}
function findLocation($location, $width){
switch ($location){
    case '000':
        return $local = 0;
        break;
    case '00f':
        return $local = $width;
        break;
    case '333':
        return $local = $width * 2;
        break;
    case '666':
        return $local = $width * 3;
        break;
    case '999':
        return $local = $width * 4;
        break;
    case 'ccc':
        return $local = $width * 5;
        break;
     case 'f00':
        return $local = $width * 6;
        break;
    case 'f0f':
        return $local = $width * 7;
        break;
    case 'fff' :
        return $local = $width * 8;
        break;
    }
}

$buildCss .=".socialwrap li.icon_text a img, .socialwrap li.iconOnly a img, .followwrap li.icon_text a img, .followwrap li.iconOnly a img{border-width:0 !important;background-color:none;}";
if ($c->_options['follow_location'] == "right" || $c->_options['follow_location'] == "left") {
if ($c->_options['follow_location'] == "right") {$corner = 'left';}else{$corner = 'right';} // border corner
$buildCss .= "#follow.".$c->_options['follow_location']." {width:";
if ('text_replace'==$c->_options['follow_list_style'])
    {$buildCss .= "30";}
else { $buildCss .= $c->_options['tab_size']+8;} // width of tab
$buildCss .= "px;position:fixed; ";
$buildCss .= $c->_options['follow_location']; // left or right side
$buildCss .= ":0; top:".$c->_options['distance_from_top']."px;";
    if ($c->_options['background_transparent']!='yes')
    {$buildCss .= "background-color:#".$c->_options['background_color'].";";}
$buildCss .= "padding:10px 0;font-family:impact,charcoal,arial, helvetica,sans-serif;-moz-border-radius-top".$corner.": 5px;-webkit-border-top-".$corner."-radius:5px;-moz-border-radius-bottom".$corner.":5px;-webkit-border-bottom-".$corner."-radius:5px;";
   if ($c->_options['border_transparent']!='yes'){ $buildCss .= "border:2px solid #".$c->_options['border_color'].";border-".$c->_options['follow_location']."-width:0}";}
   else {$buildCss .= "}";}
   $buildCss .= "#follow.".$c->_options['follow_location']." ul {padding:0; margin:0; list-style-type:none !important;font-size:24px;color:black;}\n";
   $buildCss .= "#follow.".$c->_options['follow_location']." ul li {padding-bottom:".$c->_options['follow_list_spacing']."px;list-style-type:none !important;padding-left:4px;padding-right:4px}\n";
//end of box setup

// turn off if not needed
// if($c->_options['add_follow_text']=="true"){
$buildCss .="#follow img{border:none;}";
$buildCss .="#follow.".$c->_options['follow_location']." ul li.".$c->_options['word_value']." {margin:0 4px;}\n";
$buildCss .="#follow.".$c->_options['follow_location']." ul li.".$c->_options['word_value']." img {border-width:0;display:block;overflow:hidden; background:transparent url(".WP_PLUGIN_URL."/share-and-follow/images/impact/".$c->_options['word_value']."-".$c->_options['follow_location'].".png) no-repeat ";
switch($c->_options['word_value']){
        case "follow":
            $location = findLocation($c->_options['follow_color'], 21);
            $buildCss .= "-".$location."px 0px;height:79px;width:20px;";
        break;
        case "followme":
            $location = findLocation($c->_options['follow_color'], 21);
            $buildCss .= "-".$location."px 0px;height:114px;width:20px;";
        break;
        case "followus":
            $location = findLocation($c->_options['follow_color'], 21);
            $buildCss .= "-".$location."px 0px;height:107px;width:20px;";
        break;
        case "connect":
            $location = findLocation($c->_options['follow_color'], 20);
            $buildCss .= "-".$location."px 0px;height:99px;width:19px;";
        break;
        case "aansluiten":
            $location = findLocation($c->_options['follow_color'], 21);
            $buildCss .= "-".$location."px 0px;height:125px;width:20px;";
        break;
        case "ajouter":
            $location = findLocation($c->_options['follow_color'], 23);
            $buildCss .= "-".$location."px 0px;height:89px;width:22px;";
        break;
        case "communicate":
            $location = findLocation($c->_options['follow_color'], 21);
            $buildCss .= "-".$location."px 0px;height:155px;width:20px;";
        break;
        case "deelnemen":
            $location = findLocation($c->_options['follow_color'], 21);
            $buildCss .= "-".$location."px 0px;height:129px;width:20px;";
        break;
        case "join":
            $location = findLocation($c->_options['follow_color'], 23);
            $buildCss .= "-".$location."px 0px;height:56px;width:22px;";
        break;
        case "mededeling":
            $location = findLocation($c->_options['follow_color'], 24);
            $buildCss .= "-".$location."px 0px;height:134px;width:23px";
        break;
        case "network":
            $location = findLocation($c->_options['follow_color'], 21);
            $buildCss .= "-".$location."px 0px;height:98px;width:20px;";
        break;
        case "overzichten":
            $location = findLocation($c->_options['follow_color'], 21);
            $buildCss .= "-".$location."px 0px;height:132px;width:20px;";
        break;
        case "publications":
            $location = findLocation($c->_options['follow_color'], 23);
            $buildCss .= "-".$location."px 0px;height:143px;width:22px;";
        break;
        case "rejoindre":
            $location = findLocation($c->_options['follow_color'], 23);
            $buildCss .= "-".$location."px 0px;height:111px;width:22px;";
        break;
        case "reseau":
            $location = findLocation($c->_options['follow_color'], 22);
            $buildCss .= "-".$location."px 0px;height:87px;width:21px;";
        break;
        case "review":
            $location = findLocation($c->_options['follow_color'], 21);
            $buildCss .= "-".$location."px 0px;height:95px;width:20px;";
        break;
        case "seconnecter":
            $location = findLocation($c->_options['follow_color'], 20);
            $buildCss .= "-".$location."px 0px;height:147px;width:19px;";
        break;
        case "suivre":
            $location = findLocation($c->_options['follow_color'], 21);
            $buildCss .= "-".$location."px 0px;height:79px;width:20px;";
        break;
        case "toevoegen":
            $location = findLocation($c->_options['follow_color'], 23);
            $buildCss .= "-".$location."px 0px;height:120px;width:22px;";
        break;
         case "verbinden":
            $location = findLocation($c->_options['follow_color'], 21);
            $buildCss .= "-".$location."px 0px;height:117px;width:20px;";
        break;
        case "volgen":
            $location = findLocation($c->_options['follow_color'], 24);
            $buildCss .= "-".$location."px 0px;height:82px;width:23px;";
        break;
        case "volgonze":
            $location = findLocation($c->_options['follow_color'], 24);
            $buildCss .= "-".$location."px 0px;height:108px;width:23px;";
        break;
        case "volgons":
            $location = findLocation($c->_options['follow_color'], 22);
            $buildCss .= "-".$location."px 0px;height:93px;width:21px;";
        break;
        case "volgmij":
            $location = findLocation($c->_options['follow_color'], 24);
            $buildCss .= "-".$location."px 0px;height:95px;width:23px;";
        break;
        case "comunicar":
            $location = findLocation($c->_options['follow_color'], 21);
            $buildCss .= "-".$location."px 0px;height:127px;width:20px;";
        break;
        case "conectar":
            $location = findLocation($c->_options['follow_color'], 20);
            $buildCss .= "-".$location."px 0px;height:107px;width:19px;";
        break;
        case "juntar":
            $location = findLocation($c->_options['follow_color'], 23);
            $buildCss .= "-".$location."px 0px;height:80px;width:22px;";
        break;
        case "rede":
            $location = findLocation($c->_options['follow_color'], 21);
            $buildCss .= "-".$location."px 0px;height:63px;width:20px;";
        break;
         case "resenhas":
            $location = findLocation($c->_options['follow_color'], 21);
            $buildCss .= "-".$location."px 0px;height:111px;width:20px;";
        break;
        case "seguir":
            $location = findLocation($c->_options['follow_color'], 24);
            $buildCss .= "-".$location."px 0px;height:81px;width:23px;";
        break;
        case "sigame":
            $location = findLocation($c->_options['follow_color'], 24);
            $buildCss .= "-".$location."px 0px;height:97px;width:23px;";
        break;
        case "siganos":
            $location = findLocation($c->_options['follow_color'], 24);
            $buildCss .= "-".$location."px 0px;height:103px;width:23px;";
        break;
        default:
            $buildCss .="height:79px;";
        break;
}
$buildCss .="}\n";
$buildCss .="#follow.".$c->_options['follow_location']." ul li a {display:block;}\n";

//
// Build text replacement details for single CSS option (although it is a sprite anyway
//
if ($c->_options['follow_list_style']=='text_replace'){
    $follow_words_list = array (
            'facebook'=>array('height'=>'91','width'=>'20', 'leftWhite_Xaxis'=>'0','leftWhite_Yaxis'=>'0','rightWhite_Xaxis'=>'1125','rightWhite_Yaxis'=>'42',  'leftBlack_Xaxis'=>'21','leftBlack_Yaxis'=>'0','rightBlack_Xaxis'=>'1104','rightBlack_Yaxis'=>'42', ),
            'hyves'=>array('height'=>'57','width'=> '22', 'leftWhite_Xaxis'=>'42','leftWhite_Yaxis'=>'0','rightWhite_Xaxis'=>'1081','rightWhite_Yaxis'=>'76',  'leftBlack_Xaxis'=>'66','leftBlack_Yaxis'=>'0','rightBlack_Xaxis'=>'1057','rightBlack_Yaxis'=>'76', ),
            'imdb'=>array('height'=>'49','width'=> '20', 'leftWhite_Xaxis'=>'42','leftWhite_Yaxis'=>'60','rightWhite_Xaxis'=>'1083','rightWhite_Yaxis'=>'24',  'leftBlack_Xaxis'=>'67','leftBlack_Yaxis'=>'60','rightBlack_Xaxis'=>'1058','rightBlack_Yaxis'=>'24', ),
            'bandcamp'=>array('height'=>'103','width'=> '22', 'leftWhite_Xaxis'=>'89','leftWhite_Yaxis'=>'0','rightWhite_Xaxis'=>'1034','rightWhite_Yaxis'=>'30',  'leftBlack_Xaxis'=>'112','leftBlack_Yaxis'=>'0','rightBlack_Xaxis'=>'1011','rightBlack_Yaxis'=>'30', ),
            'coconex'=>array('height'=>'83','width'=> '17', 'leftWhite_Xaxis'=>'135','leftWhite_Yaxis'=>'0','rightWhite_Xaxis'=>'993','rightWhite_Yaxis'=>'50',  'leftBlack_Xaxis'=>'153','leftBlack_Yaxis'=>'0','rightBlack_Xaxis'=>'975','rightBlack_Yaxis'=>'50', ),
            'dailymotion'=>array('height'=>'117','width'=> '22', 'leftWhite_Xaxis'=>'171','leftWhite_Yaxis'=>'0','rightWhite_Xaxis'=>'952','rightWhite_Yaxis'=>'16',  'leftBlack_Xaxis'=>'194','leftBlack_Yaxis'=>'0','rightBlack_Xaxis'=>'929','rightBlack_Yaxis'=>'16', ),
            'delicious'=>array('height'=>'93','width'=> '20', 'leftWhite_Xaxis'=>'217','leftWhite_Yaxis'=>'0','rightWhite_Xaxis'=>'908','rightWhite_Yaxis'=>'40',  'leftBlack_Xaxis'=>'238','leftBlack_Yaxis'=>'0','rightBlack_Xaxis'=>'887','rightBlack_Yaxis'=>'40', ),
            'deviantart'=>array('height'=>'101','width'=> '20', 'leftWhite_Xaxis'=>'259','leftWhite_Yaxis'=>'0','rightWhite_Xaxis'=>'866','rightWhite_Yaxis'=>'32',  'leftBlack_Xaxis'=>'280','leftBlack_Yaxis'=>'0','rightBlack_Xaxis'=>'845','rightBlack_Yaxis'=>'32', ),
            'digg'=>array('height'=>'42','width'=> '23', 'leftWhite_Xaxis'=>'301','leftWhite_Yaxis'=>'0','rightWhite_Xaxis'=>'821','rightWhite_Yaxis'=>'91',  'leftBlack_Xaxis'=>'325','leftBlack_Yaxis'=>'0','rightBlack_Xaxis'=>'797','rightBlack_Yaxis'=>'91', ),
            'flickr'=>array('height'=>'53','width'=> '20', 'leftWhite_Xaxis'=>'302','leftWhite_Yaxis'=>'43','rightWhite_Xaxis'=>'823','rightWhite_Yaxis'=>'37',  'leftBlack_Xaxis'=>'323','leftBlack_Yaxis'=>'43','rightBlack_Xaxis'=>'802','rightBlack_Yaxis'=>'37', ),
            'foursquare'=>array('height'=>'110','width'=> '22', 'leftWhite_Xaxis'=>'350','leftWhite_Yaxis'=>'0','rightWhite_Xaxis'=>'773','rightWhite_Yaxis'=>'23',  'leftBlack_Xaxis'=>'373','leftBlack_Yaxis'=>'0','rightBlack_Xaxis'=>'750','rightBlack_Yaxis'=>'23', ),
            'google_buzz'=>array('height'=>'112','width'=> '23', 'leftWhite_Xaxis'=>'396','leftWhite_Yaxis'=>'0','rightWhite_Xaxis'=>'726','rightWhite_Yaxis'=>'21',  'leftBlack_Xaxis'=>'419','leftBlack_Yaxis'=>'0','rightBlack_Xaxis'=>'703','rightBlack_Yaxis'=>'21', ),
            'gowalla'=>array('height'=>'77','width'=> '23', 'leftWhite_Xaxis'=>'443','leftWhite_Yaxis'=>'0','rightWhite_Xaxis'=>'679','rightWhite_Yaxis'=>'56',  'leftBlack_Xaxis'=>'468','leftBlack_Yaxis'=>'0','rightBlack_Xaxis'=>'654','rightBlack_Yaxis'=>'56', ),
            'itunes'=>array('height'=>'63','width'=> '20', 'leftWhite_Xaxis'=>'493','leftWhite_Yaxis'=>'0','rightWhite_Xaxis'=>'632','rightWhite_Yaxis'=>'70',  'leftBlack_Xaxis'=>'514','leftBlack_Yaxis'=>'0','rightBlack_Xaxis'=>'611','rightBlack_Yaxis'=>'70', ),
            'plaxo'=>array('height'=>'52','width'=> '22', 'leftWhite_Xaxis'=>'486','leftWhite_Yaxis'=>'80','rightWhite_Xaxis'=>'637','rightWhite_Yaxis'=>'1',  'leftBlack_Xaxis'=>'511','leftBlack_Yaxis'=>'80','rightBlack_Xaxis'=>'612','rightBlack_Yaxis'=>'1', ),
            'lastfm'=>array('height'=>'65','width'=> '20', 'leftWhite_Xaxis'=>'535','leftWhite_Yaxis'=>'0','rightWhite_Xaxis'=>'590','rightWhite_Yaxis'=>'68',  'leftBlack_Xaxis'=>'556','leftBlack_Yaxis'=>'0','rightBlack_Xaxis'=>'569','rightBlack_Yaxis'=>'68', ),
            'rss'=>array('height'=>'31','width'=> '17', 'leftWhite_Xaxis'=>'535','leftWhite_Yaxis'=>'67','rightWhite_Xaxis'=>'593','rightWhite_Yaxis'=>'35',  'leftBlack_Xaxis'=>'556','leftBlack_Yaxis'=>'67','rightBlack_Xaxis'=>'572','rightBlack_Yaxis'=>'35', ),
            'linkedin'=>array('height'=>'80','width'=> '20', 'leftWhite_Xaxis'=>'577','leftWhite_Yaxis'=>'0','rightWhite_Xaxis'=>'548','rightWhite_Yaxis'=>'53',  'leftBlack_Xaxis'=>'598','leftBlack_Yaxis'=>'0','rightBlack_Xaxis'=>'527','rightBlack_Yaxis'=>'53', ),
            'moddb'=>array('height'=>'66','width'=> '20', 'leftWhite_Xaxis'=>'619','leftWhite_Yaxis'=>'0','rightWhite_Xaxis'=>'506','rightWhite_Yaxis'=>'67',  'leftBlack_Xaxis'=>'640','leftBlack_Yaxis'=>'0','rightBlack_Xaxis'=>'485','rightBlack_Yaxis'=>'67', ),
            'myspace' =>array('height'=>'88','width'=> '19', 'leftWhite_Xaxis'=>'661','leftWhite_Yaxis'=>'0','rightWhite_Xaxis'=>'465','rightWhite_Yaxis'=>'45',  'leftBlack_Xaxis'=>'680','leftBlack_Yaxis'=>'0','rightBlack_Xaxis'=>'446','rightBlack_Yaxis'=>'45', ),
            'newsletter'=>array('height'=>'105','width'=> '20', 'leftWhite_Xaxis'=>'699','leftWhite_Yaxis'=>'0','rightWhite_Xaxis'=>'426','rightWhite_Yaxis'=>'28',  'leftBlack_Xaxis'=>'720','leftBlack_Yaxis'=>'0','rightBlack_Xaxis'=>'405','rightBlack_Yaxis'=>'28', ),
            'orkut'=>array('height'=>'52','width'=> '20', 'leftWhite_Xaxis'=>'741','leftWhite_Yaxis'=>'0','rightWhite_Xaxis'=>'384','rightWhite_Yaxis'=>'81',  'leftBlack_Xaxis'=>'762','leftBlack_Yaxis'=>'0','rightBlack_Xaxis'=>'363','rightBlack_Yaxis'=>'81', ),
            'picasa'=>array('height'=>'65','width'=> '22', 'leftWhite_Xaxis'=>'741','leftWhite_Yaxis'=>'54','rightWhite_Xaxis'=>'384','rightWhite_Yaxis'=>'14',  'leftBlack_Xaxis'=>'762','leftBlack_Yaxis'=>'54','rightBlack_Xaxis'=>'361','rightBlack_Yaxis'=>'14', ),
            'soundcloud'=>array('height'=>'116','width'=> '20', 'leftWhite_Xaxis'=>'785','leftWhite_Yaxis'=>'0','rightWhite_Xaxis'=>'340','rightWhite_Yaxis'=>'17',  'leftBlack_Xaxis'=>'807','leftBlack_Yaxis'=>'0','rightBlack_Xaxis'=>'318','rightBlack_Yaxis'=>'17', ),
            'sphinn'=>array('height'=>'62','width'=> '21', 'leftWhite_Xaxis'=>'828','leftWhite_Yaxis'=>'0','rightWhite_Xaxis'=>'295','rightWhite_Yaxis'=>'66',  'leftBlack_Xaxis'=>'852','leftBlack_Yaxis'=>'0','rightBlack_Xaxis'=>'271','rightBlack_Yaxis'=>'66', ),
            'vimeo'=>array('height'=>'60','width'=> '20', 'leftWhite_Xaxis'=>'830','leftWhite_Yaxis'=>'69','rightWhite_Xaxis'=>'295','rightWhite_Yaxis'=>'4',  'leftBlack_Xaxis'=>'854','leftBlack_Yaxis'=>'69','rightBlack_Xaxis'=>'271','rightBlack_Yaxis'=>'4', ),
            'stumble'=>array('height'=>'133','width'=> '21', 'leftWhite_Xaxis'=>'875','leftWhite_Yaxis'=>'0','rightWhite_Xaxis'=>'248','rightWhite_Yaxis'=>'0',  'leftBlack_Xaxis'=>'897s','leftBlack_Yaxis'=>'0','rightBlack_Xaxis'=>'226','rightBlack_Yaxis'=>'0', ),
            'tumblr'=>array('height'=>'67','width'=> '20', 'leftWhite_Xaxis'=>'920','leftWhite_Yaxis'=>'0','rightWhite_Xaxis'=>'205','rightWhite_Yaxis'=>'66',  'leftBlack_Xaxis'=>'941','leftBlack_Yaxis'=>'0','rightBlack_Xaxis'=>'184','rightBlack_Yaxis'=>'66', ),
            'twitter'=>array('height'=>'65','width'=> '20',  'leftWhite_Xaxis'=>'920','leftWhite_Yaxis'=>'68','rightWhite_Xaxis'=>'205','rightWhite_Yaxis'=>'0',  'leftBlack_Xaxis'=>'941','leftBlack_Yaxis'=>'68','rightBlack_Xaxis'=>'184','rightBlack_Yaxis'=>'0', ),
            'vkontakte'=>array('height'=>'96','width'=> '21', 'leftWhite_Xaxis'=>'962','leftWhite_Yaxis'=>'0','rightWhite_Xaxis'=>'163','rightWhite_Yaxis'=>'38',  'leftBlack_Xaxis'=>'983','leftBlack_Yaxis'=>'0','rightBlack_Xaxis'=>'142','rightBlack_Yaxis'=>'38', ),
            'youtube'=>array('height'=>'81','width'=> '21', 'leftWhite_Xaxis'=>'1004','leftWhite_Yaxis'=>'0','rightWhite_Xaxis'=>'119','rightWhite_Yaxis'=>'53',  'leftBlack_Xaxis'=>'1027','leftBlack_Yaxis'=>'0','rightBlack_Xaxis'=>'96','rightBlack_Yaxis'=>'53', ),
            'xing'=>array('height'=>'42','width'=> '23', 'leftWhite_Xaxis'=>'1050','leftWhite_Yaxis'=>'0','rightWhite_Xaxis'=>'72','rightWhite_Yaxis'=>'91',  'leftBlack_Xaxis'=>'1074','leftBlack_Yaxis'=>'0','rightBlack_Xaxis'=>'48','rightBlack_Yaxis'=>'91', ),
            'yelp'=>array('height'=>'44','width'=> '22', 'leftWhite_Xaxis'=>'1051','leftWhite_Yaxis'=>'50','rightWhite_Xaxis'=>'72','rightWhite_Yaxis'=>'41',  'leftBlack_Xaxis'=>'1075','leftBlack_Yaxis'=>'50','rightBlack_Xaxis'=>'48','rightBlack_Yaxis'=>'41', ),
            'yahoo_buzz'=>array('height'=>'106','width'=> '22', 'leftWhite_Xaxis'=>'1099','leftWhite_Yaxis'=>'0','rightWhite_Xaxis'=>'24','rightWhite_Yaxis'=>'27',  'leftBlack_Xaxis'=>'1123','leftBlack_Yaxis'=>'0','rightBlack_Xaxis'=>'0','rightBlack_Yaxis'=>'27', ),
            'xfire'=>array('height'=>'45','width'=> '20', 'leftWhite_Xaxis'=>'619','leftWhite_Yaxis'=>'67','rightWhite_Xaxis'=>'506','rightWhite_Yaxis'=>'21',  'leftBlack_Xaxis'=>'640','leftBlack_Yaxis'=>'67','rightBlack_Xaxis'=>'485','rightBlack_Yaxis'=>'21', ),
            'ya'=>array('height'=>'21','width'=> '17', 'leftWhite_Xaxis'=>'1145','leftWhite_Yaxis'=>'0','rightWhite_Xaxis'=>'1170','rightWhite_Yaxis'=>'112',  'leftBlack_Xaxis'=>'1164','leftBlack_Yaxis'=>'0','rightBlack_Xaxis'=>'1151','rightBlack_Yaxis'=>'112', ),
            'posterous'=>array('height'=>'90','width'=> '19', 'leftWhite_Xaxis'=>'1146','leftWhite_Yaxis'=>'23','rightWhite_Xaxis'=>'1167','rightWhite_Yaxis'=>'20',  'leftBlack_Xaxis'=>'1167','leftBlack_Yaxis'=>'23','rightBlack_Xaxis'=>'1146','rightBlack_Yaxis'=>'20', ),
            'slideshare'=>array('height'=>'96','width'=> '20', 'leftWhite_Xaxis'=>'1186','leftWhite_Yaxis'=>'0','rightWhite_Xaxis'=>'1212','rightWhite_Yaxis'=>'35',  'leftBlack_Xaxis'=>'1207','leftBlack_Yaxis'=>'0','rightBlack_Xaxis'=>'1191','rightBlack_Yaxis'=>'35', ),
            'feedburner'=>array('height'=>'100','width'=> '19', 'leftWhite_Xaxis'=>'1230','leftWhite_Yaxis'=>'0','rightWhite_Xaxis'=>'1254','rightWhite_Yaxis'=>'33',  'leftBlack_Xaxis'=>'1250','leftBlack_Yaxis'=>'0','rightBlack_Xaxis'=>'1234','rightBlack_Yaxis'=>'33', ),
            'plurk'=>array('height'=>'46','width'=> '20', 'leftWhite_Xaxis'=>'1396','leftWhite_Yaxis'=>'0','rightWhite_Xaxis'=>'1425','rightWhite_Yaxis'=>'87',  'leftBlack_Xaxis'=>'1417','leftBlack_Yaxis'=>'0','rightBlack_Xaxis'=>'1404','rightBlack_Yaxis'=>'87', ),
            'faves'=>array('height'=>'48','width'=> '19', 'leftWhite_Xaxis'=>'1441','leftWhite_Yaxis'=>'0','rightWhite_Xaxis'=>'1381','rightWhite_Yaxis'=>'85',  'leftBlack_Xaxis'=>'1461','leftBlack_Yaxis'=>'0','rightBlack_Xaxis'=>'1361','rightBlack_Yaxis'=>'85', ),
            'bebo'=>array('height'=>'44','width'=> '19', 'leftWhite_Xaxis'=>'1527','leftWhite_Yaxis'=>'0','rightWhite_Xaxis'=>'1295','rightWhite_Yaxis'=>'89',  'leftBlack_Xaxis'=>'1547','leftBlack_Yaxis'=>'0','rightBlack_Xaxis'=>'1275','rightBlack_Yaxis'=>'89', ),
            'ning'=>array('height'=>'40','width'=> '21', 'leftWhite_Xaxis'=>'1524','leftWhite_Yaxis'=>'46','rightWhite_Xaxis'=>'1296','rightWhite_Yaxis'=>'47',  'leftBlack_Xaxis'=>'1546','leftBlack_Yaxis'=>'46','rightBlack_Xaxis'=>'1274','rightBlack_Yaxis'=>'47', ),
            'wordpress'=>array('height'=>'96','width'=> '21', 'leftWhite_Xaxis'=>'1481','leftWhite_Yaxis'=>'0','rightWhite_Xaxis'=>'1340','rightWhite_Yaxis'=>'37',  'leftBlack_Xaxis'=>'1503','leftBlack_Yaxis'=>'0','rightBlack_Xaxis'=>'1318','rightBlack_Yaxis'=>'37', ),
            'squidoo'=>array('height'=>'71','width'=> '20', 'leftWhite_Xaxis'=>'1438','leftWhite_Yaxis'=>'50','rightWhite_Xaxis'=>'1383','rightWhite_Yaxis'=>'12',  'leftBlack_Xaxis'=>'1460','leftBlack_Yaxis'=>'50','rightBlack_Xaxis'=>'1361','rightBlack_Yaxis'=>'12', ),
            'technet'=>array('height'=>'72','width'=> '19', 'leftWhite_Xaxis'=>'1398','leftWhite_Yaxis'=>'49','rightWhite_Xaxis'=>'1424','rightWhite_Yaxis'=>'12',  'leftBlack_Xaxis'=>'1417','leftBlack_Yaxis'=>'49','rightBlack_Xaxis'=>'1405','rightBlack_Yaxis'=>'12', ),
            'meetup'=>array('height'=>'68','width'=> '19', 'leftWhite_Xaxis'=>'1356','leftWhite_Yaxis'=>'0','rightWhite_Xaxis'=>'1466','rightWhite_Yaxis'=>'65',  'leftBlack_Xaxis'=>'1376','leftBlack_Yaxis'=>'0','rightBlack_Xaxis'=>'1446','rightBlack_Yaxis'=>'65', ),
            'getglue'=>array('height'=>'68','width'=> '21', 'leftWhite_Xaxis'=>'1312','leftWhite_Yaxis'=>'0','rightWhite_Xaxis'=>'1508','rightWhite_Yaxis'=>'65',  'leftBlack_Xaxis'=>'1334','leftBlack_Yaxis'=>'0','rightBlack_Xaxis'=>'1486','rightBlack_Yaxis'=>'65', ),
            'identica'=>array('height'=>'74','width'=> '19', 'leftWhite_Xaxis'=>'1271','leftWhite_Yaxis'=>'0','rightWhite_Xaxis'=>'1551','rightWhite_Yaxis'=>'59',  'leftBlack_Xaxis'=>'1291','leftBlack_Yaxis'=>'0','rightBlack_Xaxis'=>'1531','rightBlack_Yaxis'=>'59', ),
            'blogger'=>array('height'=>'65','width'=> '20', 'leftWhite_Xaxis'=>'1568','leftWhite_Yaxis'=>'0','rightWhite_Xaxis'=>'1593','rightWhite_Yaxis'=>'65',  'leftBlack_Xaxis'=>'1590','leftBlack_Yaxis'=>'0','rightBlack_Xaxis'=>'1572','rightBlack_Yaxis'=>'65', ),
);

    $buildCss .= "/* start text replacemnt words*/\n";
    foreach ($follow_words_list as $key=>$value){
        // check if link exist
        // perform if there ignore otherwise
        if ($c->_options['follow_'.$key]=='yes'|| $key=='rss'){
            if ($c->_options['follow_location']=="left"){
            $buildCss .="#follow.".$c->_options['follow_location']." ul li.text_replace a img.".$key." {background:transparent url(".WP_PLUGIN_URL."/share-and-follow/images/impact/word-strip-left.png) no-repeat; background-position:-".$value['leftWhite_Xaxis']."px -".$value['leftWhite_Yaxis']."px;";
            $buildCss .="height:".$value['height']."px;width:".$value['width']."px}\n";
            $buildCss .= "#follow.".$c->_options['follow_location']." ul li.text_replace a img.".$key.":hover {background-position:-".$value['leftBlack_Xaxis']."px -".$value['leftBlack_Yaxis']."px;}\n";
            }
            if ($c->_options['follow_location']=="right"){
            $buildCss .="#follow.".$c->_options['follow_location']." ul li.text_replace a img.".$key." {background:transparent url(".WP_PLUGIN_URL."/share-and-follow/images/impact/word-strip-right.png) no-repeat; background-position:-".$value['rightWhite_Xaxis']."px -".$value['rightWhite_Yaxis']."px;";
            $buildCss .="height:".$value['height']."px;width:".$value['width']."px}\n";
            $buildCss .= "#follow.".$c->_options['follow_location']." ul li.text_replace a img.".$key.":hover {background-position:-".$value['rightBlack_Xaxis']."px -".$value['rightBlack_Yaxis']."px;}\n";
            }
        }
    }
$buildCss .= "/* end text replacemnt words */ \n";
}
$buildCss .="#follow.".$c->_options['follow_location']." ul li.".$c->_options['word_value']." span, #follow ul li a span {display:none}";
}
    // end of text replacement stuff
    // do bottom area
    if ($c->_options['follow_location'] == "bottom"  && $c->_options['follow_list_style'] == "text_replace" ){
        $buildCss .="#follow.bottom {width:100%; position:fixed; left:0px; bottom:0px;";
            if ($c->_options['background_transparent']!='yes'){$buildCss .="background-color:#".$c->_options['background_color'].";";}
        $buildCss .="font-family:impact,charcoal,arial, helvetica,sans-serif;";
            if ($c->_options['border_transparent']!='yes'){$buildCss .="border:2px solid #".$c->_options['border_color'].";border-width:2px 0 0 0;";}
        $buildCss .="}\n";
        $buildCss .="#follow.".$c->_options['follow_location']." ul {padding:0 0 0 20px; margin:0; list-style-type:none !important;font-size:24px;color:black;}\n";
        $buildCss .="#follow.".$c->_options['follow_location']." ul li {";
        if ('text_replace'==$c->_options['follow_list_style']){$buildCss .="margin-left:4px;";}else { $buildCss .="margin:4px;";}
        $buildCss .="padding-bottom:10px;list-style-type:none !important; display:inline;}";
        $buildCss .="#follow.".$c->_options['follow_location']." ul li.follow {color:#".$c->_options['follow_color'].";}\n";
        $buildCss .="#follow.".$c->_options['follow_location']." ul li a {margin-right:".$c->_options['$follow_list_spacing']."; background-image:none !important}\n";
        $buildCss .="#follow.".$c->_options['follow_location']." ul li.text_replace a {color:white;text-decoration:none;}\n";
        $buildCss .="#follow.".$c->_options['follow_location']." ul li.text_replace a:hover {color:black}";
    } else if ($c->_options['follow_location'] == "bottom"  && $c->_options['follow_list_style'] != "text_replace" ){
        $buildCss .="#follow.bottom {width:100%; position:fixed; left:0px; bottom:0px;";
        if ($c->_options['background_transparent']!='yes'){$buildCss .= "background-color:#".$c->_options['background_color'].";" ;}
        if ($c->_options['border_transparent']!='yes'){$buildCss .="border:2px solid #".$c->_options['border_color'].";border-width:2px 0 0 0;}\n";}
        $buildCss .="}\n";
        $buildCss .="#follow.bottom ul {padding-left:20px;list-style-type:none;} #follow.bottom ul li {float:left;padding-top:4px;margin-right:10px;list-style-type:none;}\n";
        $buildCss .="#follow.".$c->_options['follow_location']." ul li.follow {color:#".$c->_options['follow_color'].";line-height:".$c->_options['tab_size']."px;}\n";
    }
    //do top area
if ($c->_options['follow_location'] == "top"  && $c->_options['follow_list_style'] == "text_replace" ){
        $buildCss .="#follow.top {width:100%; position:fixed; left:0px; top:0px;";
        if ($c->_options['background_transparent']!='yes'){ $buildCss .="background-color:#".$c->_options['background_color'].";";}
        $buildCss .="font-family:impact,charcoal,arial, helvetica,sans-serif;";
        if ($c->_options['border_transparent']!='yes'){ $buildCss .="border:2px solid #".$c->_options['border_color'].";border-width:0px 0 2px 0;";}
        $buildCss .= "}\n";
        $buildCss .="#follow.".$c->_options['follow_location']." ul {padding:0 0 0 20px; margin:0; list-style-type:none !important;font-size:24px;color:black;}";
        $buildCss .="#follow.".$c->_options['follow_location']." ul li {";
        if ('text_replace'==$c->_options['follow_list_style']){$buildCss .="margin-left:4px;";}else { $buildCss .="margin:4px;";}
        $buildCss .="padding-bottom:10px;list-style-type:none !important; display:inline;}";
        $buildCss .="#follow.".$c->_options['follow_location']." ul li.follow {color:#".$c->_options['follow_color'].";}";
        $buildCss .="#follow.".$c->_options['follow_location']." ul li a {margin-right:".$c->_options['follow_list_spacing']."px; background-image:none !important}";
        $buildCss .="#follow.".$c->_options['follow_location']." ul li.text_replace a {color:white;text-decoration:none;}";
        $buildCss .="#follow.".$c->_options['follow_location']." ul li.text_replace a:hover {color:black;}";
} else if ($c->_options['follow_location'] == "top" && $c->_options['follow_list_style'] != "text_replace" ){
        $buildCss .="#follow.top {width:100%; position:fixed; left:0px; top:0px;";
        if ($c->_options['background_transparent']!='yes'){
            $buildCss .="background-color:#".$c->_options['background_color'].";";
        }
        if ($c->_options['border_transparent']!='yes'){
            $buildCss .="border:2px solid #".$c->_options['border_color'].";border-width:0 0 2px 0;";
        }
        $buildCss .="}";
            $buildCss .="#follow.top ul {padding-left:20px;list-style-type:none;}";
            $buildCss .="#follow.top ul li {float:left;padding-top:4px;margin-right:".$c->_options['follow_list_spacing']."px;list-style-type:none;}";
            
            $buildCss .="#follow.".$c->_options['follow_location']." ul li.follow {color:#".$c->_options['follow_color'].";line-height:".$c->_options['tab_size']."px;}";
            $buildCss .="body {padding-top:". ($c->_options['tab_size']+8)."px}";
        $buildCss .="#follow a {text-decoration:none}
li.icon_text a span.head{}
li.icon_text a:hover span {border-bottom:solid 1px blue;}";
}
// finish add icons
$buildCss .=".share {margin:0 ".$c->_options['spacing']."px ".$c->_options['spacing']."px 0;}\n";
$buildCss .="ul.row li {float:left;list-style-type:none;list-style:none;}\n";
$buildCss .="li.iconOnly a span.head {display:none}\n";
$buildCss .="#follow.left ul.size16 li.follow{margin:0px auto !important}\n";
$buildCss .="li.icon_text a {padding-left:0;margin-right:".$c->_options['spacing']."px}\n";
$buildCss .="li.text_only a {background-image:none !important;padding-left:0;}\n";
$buildCss .="li.text_only a img {display:none;}\n";
$buildCss .="li.icon_text a span{background-image:none !important;padding-left:0 !important; }\n";
$buildCss .="li.iconOnly a span.head {display:none}\n";
$buildCss .="ul.socialwrap li {margin:0 ".$c->_options['spacing']."px ".$c->_options['spacing']."px 0 !important;}\n";
$buildCss .="ul.socialwrap li a {text-decoration:none;}";
$buildCss .="ul.row li {float:left;line-height:auto !important;}\n";
$buildCss .="ul.row li a img {padding:0}";
$buildCss .=".size16 li a,.size24 li a,.size32 li a, .size48 li a, .size60 li a {display:block}";
$buildCss .="ul.socialwrap {list-style-type:none !important;margin:0; padding:0;text-indent:0 !important;}\n";
$buildCss .="ul.socialwrap li {list-style-type:none !important;background-image:none;padding:0;list-style-image:none !important;}\n";
$buildCss .="ul.followwrap {list-style-type:none !important;margin:0; padding:0}\n";
$buildCss .="ul.followwrap li {margin-right:".$c->_options['spacing']."px;margin-bottom:".$c->_options['spacing']."px;list-style-type:none !important;}\n";
$buildCss .="#follow.right ul.followwrap li, #follow.left ul.followwrap li {margin-right:0px;margin-bottom:0px;}\n";
$buildCss .=".shareinpost {clear:both;padding-top:".$c->_options['top_padding']."px}";
$buildCss .=".shareinpost ul.socialwrap {list-style-type:none !important;margin:0 !important; padding:0 !important}\n";
$buildCss .=".shareinpost ul.socialwrap li {padding-left:0 !important;background-image:none !important;margin-left:0 !important;list-style-type:none !important;text-indent:0 !important}\n";
$buildCss .=".socialwrap li.icon_text a img, .socialwrap li.iconOnly a img{border-width:0}";
$buildCss .="ul.followrap li {list-style-type:none;list-style-image:none !important;}\n";
$buildCss .="div.clean {clear:left;}\n";
$buildCss .="div.display_none {display:none;}\n";
$buildCss .=".button_holder_bottom,.button_holder_left{margin-right:5px;display:inline}.button_holder_right{margin-left:5px;display:inline}";
$buildCss .=".button_holder_show_interactive {display:inline;margin-right:5px;}.button_holder_bottom iframe,.button_holder_left iframe,.button_holder_right iframe,.button_holder_show_interactive iframe{vertical-align:top}";
$buildCss .=".tall *[id*='___plusone']{position:relative;top:-12px}";

//
// end of standard CSS style setup
//
//
// theme support
//
if ($c->_options['theme_support']!= 'none') {
    $buildCss .=  "/* adding theme support for ".$c->_options['theme_support']."   */ \n";
        switch($c->_options['theme_support']){
            case "default":
                    $buildCss .= ".entry ul.socialwrap li:before, #sidebar ul li.share_links ul.socialwrap li:before, #sidebar ul li.follow_links ul.followwrap li:before, #content .entry ul.socialwrap li:before {content: \"\" !important;}
                    .entry ul.socialwrap li{padding:0;margin:0;text-indent:0;}
                    .entry ul.socialwrap li a span {padding-left:9px;}
                    .entry ul.socialwrap li a img {padding:0;border:none;}
                    .entry ul.socialwrap {padding:0;margin-top:20px}
                    #follow ul {margin:0}\n";
                    break;
            case "choco":
                    $buildCss .= ".post ul.socialwrap {margin-left:0 !important; margin-top:20px; clear:left;}
                    .post .bg ul.socialwrap {margin-left:0 !important; margin-top:0px}
                    .post .entry .shareinpost img {background-color:none; border-width:0; padding:0}
                    \n";
                    break;
            case "arjuna":
                    $buildCss .= ".postContent ul.socialwrap li {margin-left:0; clear:left;}
                    .postContent ul.socialwrap {margin-top:20px;}\n";
                    if ($c->_options['follow_location']=='top'){
                        $buildCss .= ".headerBG {top:10px;}
                        .header {margin-top:10px;}\n";
                    }
                    break;
            case "dojo":
                $buildCss .=  " .content ul.socialwrap li {margin-left:0px;}
                                .content ul.socialwrap {margin-top:20px;}";
                    break;
            case "tribune":
                $buildCss .= "#main-lt ul.socialwrap, #main-lt ul.followwrap {padding-top:10px; padding-left:10px;}
                              #main-rt ul.socialwrap, #main-rt ul.followwrap {padding-top:10px; padding-left:10px;}";
                break;
            case "intrepidity":
                    $buildCss .= ".entry_content ul.socialwrap {padding-left:0 ;margin-top:20px}";
                    if ($c->_options['follow_location']=='top'){
                        $buildCss .= "#bg {margin-top:".($c->_options['size']+8)."px;}\n";
                    }
                    break;
            case "thesis":
                    $buildCss .= "#footer #follow a {border-width:0;}";
                    if ($c->_options['follow_location']=='left' || $c->_options['follow_location']=='right'){
                    $buildCss .= "#footer #follow li {display:block}";
                    }
                    break;
            case "mymag":
                    $buildCss .= "#follow {z-index:1000;}
                                  #subpage .content ul.socialwrap li {background-image:none;padding:0 !important}";
                break;
            case "frugal" :
                $buildCss .= "#wrap #content .postarea ul.socialwrap li {margin-left:0}";
                break;
            default:
            break;
        }
}
//
// support for extra CSS embeded into file from user
//
if (!empty($c->_options['extra_css'])) {
        $buildCss .= stripslashes($c->_options['extra_css']);
}



//
// print support via CSS, this section excludes CSS selectors
//
if ($c->_options['add_css']=='true'){
 $printCSS ="/* cssid=".$c->_options['cssid']."                            */   \n";
 $printCSS .="/* WARNING!! this file is dynamicaly generated changes will  */ \n";
 $printCSS .="/* be overwritten with every change to the admin screen.      */ \n";
 $printCSS .="/* You can add css to this file in the admin screen.       */ \n\n\n\n\n";
}
 $printCSS .= "body {background: white;font-size: 12pt;color:black;}
 * {background-image:none;}
 #wrapper, #content {width: auto;margin: 0 5%;padding: 0;border: 0;float: none !important;color: black;background: transparent none;}
 a { text-decoration : underline; color : #0000ff; }\n";
 //
 // excludes
 if (!empty($c->_options['css_print_excludes'])) {
        $printCSS .= $c->_options['css_print_excludes']." {display:none}\n";
}
//
// user CSS for print
if (!empty($c->_options['extra_print_css'])) {
        $printCSS .= stripslashes($c->_options['extra_print_css']);
}
$cssAdminOptions['cssid'] = $c->_options['cssid'];
$cssAdminOptions['screen'] = $buildCss;
$cssAdminOptions['print'] = $printCSS;
update_option("ShareAndFollowCSS", $cssAdminOptions);
delete_transient("ShareAndFollowCSS");
$c->update_plugin_options();
}
?>