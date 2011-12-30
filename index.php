<?php

require_once 'fbsdk/src/facebook.php';
require 'ajax.php';

$facebook = new Facebook(array(
  'appId'  => '294222680618585',
  'secret' => 'b4fcd6688d92d74d9bd2cfd615477510',
));

// See if there is a user from a cookie
$user = $facebook->getUser();

if ($user) {
  try {
    // Proceed knowing you have a logged in user who's authenticated.
    $user_profile = $facebook->api('/me');
  } catch (FacebookApiException $e) {
    echo '<pre>'.htmlspecialchars(print_r($e, true)).'</pre>';
    $user = null;
  }
}

?>
<!DOCTYPE html>
<html xmlns:fb="http://www.facebook.com/2008/fbml">
<head>
	<title>collageify</title>
	<link href='http://fonts.googleapis.com/css?family=Lobster' rel='stylesheet' type='text/css'>
	<link href="http://fonts.googleapis.com/css?family=Oswald:regular" rel="stylesheet" type="text/css" >
	<link rel="stylesheet" type="text/css" href="css/style.css" />
	<script type="text/javascript" src="http://ajax.googleapis.com/ajax/libs/jquery/1.3.2/jquery.min.js"></script>
	<script type="text/javascript" src="js/animatedcollapse.js"></script>
	<script type="text/javascript" src="js/ajaxstuff.js"></script>

	<script type="text/javascript">

	animatedcollapse.addDiv('new', 'fade=1,height=80px')
	animatedcollapse.addDiv('existing', 'fade=1,height=100px')
	animatedcollapse.addDiv('more', 'fade=1,height=120px')

	animatedcollapse.ontoggle=function($, divobj, state){ //fires each time a DIV is expanded/contracted
		//$: Access to jQuery
		//divobj: DOM reference to DIV being expanded/ collapsed. Use "divobj.id" to get its ID
		//state: "block" or "none", depending on state
	}

	animatedcollapse.init()

	</script>
	
</head>

  <body>
	<div id="container">
		<div id="logo"><p class="logo">Collageify</p></div>
    <?php if ($user) { ?>
	<?php 
	//User is not new
	$profile = $facebook->api('/me');
	$name= $profile["name"];
	
	echo "<div id=content>";
    echo "<p class=\"welcome\">Welcome back, " . $name . "!</p>";
	echo "</br>";
	echo "</br>";
	echo "<a href=\"javascript:animatedcollapse.toggle('new')\">Make new collage?</a> | <a href=\"javascript:animatedcollapse.toggle('existing')\">Contribute to an existing collage</a> | <a href=\"javascript:animatedcollapse.toggle('more');getContributed()\">Add more to one you already contributed too!</a>";

	?>
	<div id="hiddenstuff">
	<div id= "new" style="width: 300px;display:none;">
		<form action="javascript: newCollage()" method="post">
		Name </br>
		<input id="newname" type="text" name="name" />
		<input type="submit" />
		</form>
	<?php
	//uploadDirectory("ocean/");
	//uploadAlbum("5725922404708861971","1333170199","1");
	?>
	<div id= "albumselection1" style="display: none"></div>
	</div>
	
	<div id= "existing" style="width: 300px;  display:none;">
		
		Collage Name </br>
		<form name="input" action="javascript:getThumb()" method="get">
			<input type="text" id ="thumb"/>
			<input type="submit" value="Submit" />
		</form>
		
		<div id="thumbpic" style="display: none"></div>
		<div id= "albumselection2" style="display: none"></div>
			
			<div id="loading"></div>
		</div>
	
	
	
	
	<div id= "more" style="width: 300px;display:none; ">
		
	<?php 
	//showAlbums($user);
	?>	
	
	<div id="contributed" style="display: none"></div>
	<div id= "albumselection3" style="display: none"></div>
	</div>
	</div>
    <?php } 
	else { ?>
		<p style="font-family: 'Oswald', serif; font-size: 35px; margin: 0px; margin-top: -40px; color: #1b6ba2;">Collageify is a fun and new way to collaboratively share memories with friends.</p></br>
		<p style="font-family: 'Oswald', serif; font-size: 20px">Log in with Facebook to continue!</p>
      <fb:login-button scope="user_photos"></fb:login-button >
    <?php } ?>

	<!-- Facebook stuff-->
    <div id="fb-root"></div>
    <script>
      window.fbAsyncInit = function() {
        FB.init({
          appId: '<?php echo $facebook->getAppID() ?>',
          cookie: true,
          xfbml: true,
          oauth: true
        });
        FB.Event.subscribe('auth.login', function(response) {
          window.location.reload();
        });
        FB.Event.subscribe('auth.logout', function(response) {
          window.location.reload();
        });
      };
      (function() {
        var e = document.createElement('script'); e.async = true;
        e.src = document.location.protocol +
          '//connect.facebook.net/en_US/all.js';
        document.getElementById('fb-root').appendChild(e);
      }());
    </script>
	</div>
	<div id="fillerspace"></div>
	<div id="useridlol" style="display:none"><?php echo $user; ?></div>

	</div>
	</div>
  </body>
</html>
