<!--
Overview: This code encompasses the initial functions run when the app is launched. There are two ways the app can be
launched and two different functions that will run accordingly.If the app is launched from the app list, a function will
execute to retrieve the last post the user made using the Game Portal app in order to retrieve their login information, 
which is simply usernames.

1. If it doesn't find a post with this information, the user will be redirected to the settings page where they can enter
	their log-in information and save it.
2. If the post is found, the page will be redirected to the post page where the user can check-in and post to the musubi 
	feed. The usernames are set to variables in the URL, so the user won't have to worry about their login information.
-->
<html>
	<head/>
	<script type="text/javascript" src="http://mobisocial.stanford.edu/musubi/apps/SocialKit-JS/lib/socialKit-1.js"></script>
	<script type="text/javascript" src="http://mobisocial.stanford.edu/musubi/apps/SocialKit-JS/jquery-1.5.2.min.js"></script>
	
	<script type="text/javascript">

	function setCookie(c_name,value,exdays){
		var exdate=new Date();
		exdate.setDate(exdate.getDate() + exdays);
		var c_value=escape(value) + ((exdays==null) ? "" : "; expires="+exdate.toUTCString());
		document.cookie=c_name + "=" + c_value;
	}

	function MusuWriter(app) {
		this.appContext = app;
	}

	var musu;
       var start_obj_DbObj;
	Musubi.ready(function(context) {
		musu = new MusuWriter(context);

		/*start_Db_Obj = musu.appContext.obj;
		var user_ids = start_Db_Obj.json["USERIDS"];
		alert(start_Db_Obj.JSON);
		alert(JSON.stringify(start_Db_Obj));
		alert(user_ids[0]);

		//Style we have to use to query, but not functional 
		if (start_Db_Obj != null){	
			var tempvar;
			setTimeout(function(){
				tempvar = startDbObj.query("type = 'gamecheckin_user'", "_id desc limit 10");
			},5000);
                     alert(tempvar);
		}

		var game_stuff_list = dbObj.query("type='gamecheckin_user'", "_id desc limit 100");  
          		var obj_id = current_obj.json.USERIDS.id; 
			if(obj_id != musu.appContext.user.id){
				var network = current_obj.json.network; 
				var game = current_obj.json.game_title; 
				var name = current_obj.json.name; 
				var ingame_id = "";
	
				if(network == "Steam")
					ingame_id = current_obj.json.steamID; 
				else if (network == "PlayStation")
					ingame_id = current_obj.json.psnID;
		
				setCookie("name", name, null);
				window.location='http://mobisocial.stanford.edu/gamecheckin/musubi/apps/landing.php&?username='
					 + ingame_id + '&network=' + network + '&currentgame' + game;
		*/
		

		var psnName = "";
		var steamName = "";
		var recon_obj = null; 
		var game_user_list = musu.appContext.feed.query("type='gamecheckin_user'", "_id desc limit 100"); 
		if(game_user_list.length != 0){
			for(var i = (game_user_list.length-1); i>=0; i--){
				recon_obj = new SocialKit.Obj(game_user_list[i]); 
				if (musu.appContext.user.id == recon_obj.json.USERIDS.id) {
					steamName = recon_obj.json.USERIDS.steamID;
					psnName = recon_obj.json.USERIDS.psnID;	
					break;
				}
			}
		}

		if(!psnName && !steamName){			
			alert("Please Enter Login Information"); 
			window.location = 'http://mobisocial.stanford.edu/gamecheckin/musubi/apps/settings.html'; 
			
		}else{
			console.log("Setting Cookies, changing windows");
			window.location='http://mobisocial.stanford.edu/gamecheckin/musubi/apps/post.php?psnName=' + psnName + '&steamName=' + steamName;
		}
	});

	</script>
	</head>
	<body>
	<img id="background" src="http://mobisocial.stanford.edu/gamecheckin/musubi/apps/images/Other%20Images/indexback.png"
		height=100% width=100% style="z-index:1;position:absolute"/>
	</body>
</html>