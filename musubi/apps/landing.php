<!DOCTYPE html>
<?php
	$network = $_GET["network"];
	$username = $_GET["username"];
	$currentgame = $_GET["currentgame"];
	$status = "Not Playing ".$currentgame." on the ".$network." network anymore";
	if ($network == "Steam") {
		$imageURL = "http://rocketdock.com/images/screenshots/steam1.png";	
		$user_xml = file_get_contents("http://www.steamcommunity.com/id/" . $usernmae . "/?xml=1");
		$xml = simplexml_load_string($user_xml);
		$steamId = $xml->steamID64[0];
		$key = "E22671BF0F254C6A176C159000114CC8";
		$current_xml = file_get_contents("http://api.steampowered.com/ISteamUser/GetPlayerSummaries/v0002/?key=" . $key . "&steamids=" . $steamId . "&format=xml");
		$xml = simplexml_load_string($current_xml);
		$player = $xml->players->player;
		$gamextrainfo = "";
		if(isset($player->gameextrainfo[0])) {
		  $gametitle = $player->gameextrainfo[0];
		  $status = "Still playing ".$gametitle. " on the ". $network. " network.";
		}
	}
	if ($network == "Playstation") {
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
		$currentXml = get_data("http://www.psnapi.com.ar/ps3/api/psn.asmx/getGames?sPSNID=" . $username);
		$xml = simplexml_load_string($currentXml);
       	$gametitle = $xml->Game[0]->Title[0];
		$imageURL = $xml->Game[0]->Image[0];
		$gametitle = preg_replace('/[^a-zA-Z0-9_ %\[\]\.\(\)%&-]/s', '', $gametitle);
		if ($gametitle == $currentgame) {
			$status = "Still playing ".$gametitle. " on the ". $network. " network.";
		}
	}
?>


<html>
    <head>
       <script type="text/javascript" src="http://mobisocial.stanford.edu/musubi/apps/SocialKit-JS/lib/socialKit-1.js"></script>
	   <script type="text/javascript" src="http://mobisocial.stanford.edu/musubi/apps/SocialKit-JS/jquery-1.5.2.min.js"></script>

	<script type="text/javascript">
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
		
		var status = '<?php echo $status ?>';
		var imageURL = '<?php echo $imageURL ?>';
		var name = '<?php echo $username ?>';
		alert ("Status: " + status + " imageURL: " + imageURL);
    </script>        


        <style type="text/css">
        
        	html{
        	background-color: #888888  ;
        	
        	}
        
        	#heading{
        	background-color:#b0c4de;
        	height: 70px;
        	}
        	
        	#div1{
        	height: 140px;
        	}
        	
        	
        	#div3{
        	height: 15px;
        	}
        	
        	#div2{
        	height: 50px;
        	}
        	
        	#main{
        	background-color:#404040;
        	height: 300px; 
        	}
              
        </style>

    <title>Gamer Portal</title>
    </head>
    
    <!--BODY===========================================================================-->
    
    <body>
   	<center>
   	
    <div id="heading">
	</a><img id = "statustitle" src = "" height=80pt width=200pt align="middle"
			/>
	</a>
	</div>
	
	<div id="div1"></div><!--=========================================================-->
	
	<div id ="main"/>

	<br/>
	<br/>
	<div id="div3"></div>
	<br/>

	</div>
	
	<div id="div2"></div><!--=========================================================-->
	
	<div id="footer">
	</div>
	</center>

	<script type = text/javascript>
		  	$('#div1').append('<br/><br/><font color="white" size=5pt><strong></strong>Player: ' + name + ' <id = "playername"/font><br/>');
		  	$('#div1').append('<font color="white" size=5pt><strong></strong>Status: ' + status + ' <id = "status"/font> <br/> <br/> </a><img src = "' + imageURL + '"height=260pt width=260pt align="middle "id = "gamepic" style="padding-right:70px"/></a>');	 				
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
			theme = "Dark Wood";
			$(function() {
				$("#statustitle").attr("src", "http://mobisocial.stanford.edu/gamecheckin/musubi/apps/images/Themes/" + theme + "/StatusTitle.png")
			});
		  
		  </script>
	      <script type="text/javascript" src="app.js"></script>
	  
     </body>
</html>