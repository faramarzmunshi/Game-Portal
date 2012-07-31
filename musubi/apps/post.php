<!DOCTYPE html> 
<!-- 
PHP Explanation:
This PHP code retrieves the usernames that were provided in the URL then proceeds to use the Steam
and PSN APIs to gather information on which network is currently active. It does so by sending a request
for a specific username and receiving a response in the form of an XML. Information on network activity is 
search for in the XML, and used to determine what message will be posted.
-->
<?php
	$steamUsername = $_GET["steamName"];
	$PSNusername = $_GET["psnName"];
	$user_xml = file_get_contents("http://www.steamcommunity.com/id/" . $steamUsername . "/?xml=1");

	$xml = simplexml_load_string($user_xml);
	$steamId = $xml->steamID64[0];

	$key = "E22671BF0F254C6A176C159000114CC8";
	$current_xml = file_get_contents("http://api.steampowered.com/ISteamUser/GetPlayerSummaries/v0002/?key=" . $key . "&steamids=" . $steamId . "&format=xml");

	$xml = simplexml_load_string($current_xml);
	$player = $xml->players->player;

	$name = $player->personaname[0];

	if(isset($player->realname[0])) {
	    $name = $player->realname[0];
	}

	$gamextrainfo = "";

	if(isset($player->gameextrainfo[0])) {
	  $gametitle = $player->gameextrainfo[0];
	  $network = "Steam";
	}else {
		function get_data($url){
			$ch = curl_init();
			$timeout = 5;
			curl_setopt($ch,CURLOPT_URL,$url);
			curl_setopt($ch,CURLOPT_RETURNTRANSFER,1);
		  	curl_setopt($ch,CURLOPT_CONNECTTIMEOUT,$timeout);
		  	curl_setopt($ch, CURLOPT_USERAGENT, "Mozilla/4.0 (compatible; MSIE5.01; Windows NT 5.0)");
		  	$data = curl_exec($ch);
		 	curl_close($ch);
		  	return $data;
		}

		if ($PSNusername == " ") {
			$gametitle = "nothing";
		}
		else {
			$currentXml = get_data("http://www.psnapi.com.ar/ps3/api/psn.asmx/getGames?sPSNID=" . $PSNusername);
			$xml = simplexml_load_string($currentXml);
           		$gametitle = $xml->Game[0]->Title[0];
          		$imageURL = $xml->Game[0]->Image[0];
			$network = "PlayStation";
			$name = $PSNusername;
		}
	}
		
	$gametitle = preg_replace('/[^a-zA-Z0-9_ %\[\]\.\(\)%&-]/s', '', $gametitle);
	$fulltext = ($name." is playing ".$gametitle." on the ".$network." network.");
?>

<!--[HTML]================[HTML]================[HTML]================[HTML]================[HTML]================[HTML]================[HTML]================-->
<html>
    <head>
	<script type="text/javascript" src="http://mobisocial.stanford.edu/musubi/apps/SocialKit-JS/lib/socialKit-1.js"></script>
	<script type="text/javascript" src="http://mobisocial.stanford.edu/musubi/apps/SocialKit-JS/jquery-1.5.2.min.js"></script>

       <style type="text/css">
       	html{
	        	background-color: black;     	
        	}           
       </style>
        
       <title>Gamer Check-In</title>

	<!-- 
	Script Explanation:
	This script is run when a user presses the Check-In button. The function retrieves the variables that were
	found by the PHP code, sets them to HTMl variables, stores them in a json of content, and posts that content to 
	the feed.         
	-->
	<script>
	function alertMusubi() {
		
		var game = '<?php echo $gametitle ?>';
		var network = '<?php echo $network ?>';
		var name = '<?php echo $name ?>';
		var psnName = '<?php echo $PSNusername ?>';
		var steamName = '<?php echo $steamUsername ?>';
		var text = '<?php echo $fulltext ?>';

		var style = "font-size: 8pt;color:white;background-color:#404040;";
		var html = '<font style="' + style + '">' + text + '</font>';
		var id = musu.appContext.user.id;
		var imageURL = '<?php echo $imageURL ?>';

		var content = {"__html" : html, "text" : text, "USERIDS": {"steamID":steamName, 
			"psnID": psnName, "id" : id}, "game_title": game, "network": network, "name": name, "imageURL": imageURL};

    		var obj = new SocialKit.Obj({type : "gamecheckin_user", name: "someobj", json: content}) 
	    	musu.appContext.feed.post(obj);
	    	musu.appContext.quit();	
	}
	</script>


    </head>
    
    <!--[BODY]================[BODY]================[BODY]================[BODY]================[BODY]================[BODY]================[BODY]================-->
    <body>
   	<center>
		<img id = "background" src="http://mobisocial.stanford.edu/gamecheckin/musubi/apps/images/Themes/Alpha/Background.png" 
			alt="Broken Img" height=100% width=100% style="z-index:1;"/>
		<img id = "checkinbutton" onClick="alertMusubi()" src="http://mobisocial.stanford.edu/gamecheckin/musubi/apps/images/Themes/Alpha/CheckInButton.png"
			alt="Broken Img" height=55% width=50% style="z-index:2;position:absolute;top:27%;left:25%;"/>
		
		<div id="heading">
			<a href="about.html">
			<img id="about" src="http://mobisocial.stanford.edu/gamecheckin/musubi/apps/images/Themes/Alpha/AboutButton.png" 
				alt="Broken Img" height=10% width=13% align="middle" style="z-index:3;position:absolute;top:2%;left:6%;" /></a>
			
			<img id="gameportaltitle" src="http://mobisocial.stanford.edu/gamecheckin/musubi/apps/images/Themes/Alpha/GamePortalTitle.png"
				alt="Broken Img" height=16%px width=36% align="middle" style="z-index:3;position:absolute;top:0.1%;left:32%;" />
					 
			<a href="http://mobisocial.stanford.edu/gamecheckin/musubi/apps/settings.html">
			<img id="settingsbutton" src="http://mobisocial.stanford.edu/gamecheckin/musubi/apps/images/Themes/Alpha/SettingsButton.png" 
			alt="Broken Img" height=7% width=18% align="middle" style="z-index:3;position:absolute;top:2.8%;left:78%;" /></a>		
		</div>			 
   	</center>
   	
	<!--
	Script Explanation:
	This script contains two functions necessary for implementing different visual themes between pages. When a theme is selected in the settings page,
	a cookie is created and retrieved from every other page. The name of the theme is taken from the cookie and the source path for all images is changed 
	to take images from that theme's folder.
	-->
	<script> 
		function getCookie(c_name){
			var i,x,y,ARRcookies=document.cookie.split(";");
			for (i=0;i<ARRcookies.length;i++){
				x=ARRcookies[i].substr(0,ARRcookies[i].indexOf("="));
			  	y=ARRcookies[i].substr(ARRcookies[i].indexOf("=")+1);
				x=x.replace(/^\s+|\s+$/g,"");
				if (x==c_name){
					return unescape(y);
			    	}
			}
		}

		var theme = getCookie("themename");

		if(theme!=null){	
			$(function() {
				$("#about").attr("src", "http://mobisocial.stanford.edu/gamecheckin/musubi/apps/images/Themes/" + theme + "/AboutButton.png")
				$("#gameportaltitle").attr("src", "http://mobisocial.stanford.edu/gamecheckin/musubi/apps/images/Themes/" + theme + "/GamePortalTitle.png")
				$("#settingsbutton").attr("src", "http://mobisocial.stanford.edu/gamecheckin/musubi/apps/images/Themes/" + theme + "/SettingsButton.png")
				$("#checkinbutton").attr("src", "http://mobisocial.stanford.edu/gamecheckin/musubi/apps/images/Themes/" + theme + "/CheckInButton.png")
				$("#background").attr("src", "http://mobisocial.stanford.edu/gamecheckin/musubi/apps/images/Themes/" + theme + "/Background.png")
			});
		}	
	</script>   	 
	<script type="text/javascript" src="http://mobisocial.stanford.edu/gamecheckin/musubi/apps/app.js"></script>
     </body>
</html>