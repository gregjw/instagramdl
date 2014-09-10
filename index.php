<?php

set_time_limit(0); 							
ini_set('default_socket_timeout', 300);		
session_start(); 							


/*------ Instagram API KEYS --------*/	

define('clientID', 'fd56a9ca4b334360b5a91e9af9715c38');
define('clientSecret', 'a759d78e8ba540f99df66f5999d71592');
define('redirectURI', 'http://gregjw.com/instagramdl/index.php');
define('imageDirectory','archive/');

  
//Connect with Instagram
function connectToInstagram($url){
	$ch = curl_init();						
	
	curl_setopt_array($ch, array(			
		CURLOPT_URL => $url,				
		CURLOPT_RETURNTRANSFER => true,		
		CURLOPT_SSL_VERIFYPEER => false,	
		CURLOPT_SSL_VERIFYHOST => 2			
	));

	$result = curl_exec($ch);				
	curl_close($ch);						
	return $result;							
}

function getUserID($userName){
	$url = 'https://api.instagram.com/v1/users/search?q='. $userName .'&client_id='. clientID;
	$instagramInfo = connectToInstagram($url);
	$results = json_decode($instagramInfo, true); 	

	return $results['data'][0]['id'];
}

function printImages($userID){
	$url = 'https://api.instagram.com/v1/users/'. $userID .'/media/recent?client_id='. clientID .'&count=-1';
	$instagramInfo = connectToInstagram($url);
	$results = json_decode($instagramInfo, true);
	
    echo '<head><title>instagramDL - DONE </title><link href="main.css" rel="stylesheet" type="text/css"></head><br /><div id="dl"><p align="center"> Your Instagram photos can now be saved! </p></div> <br/><br/>';
    
	foreach($results['data'] as $item){
		$image_url = $item['images']['low_resolution']['url'];
		echo '<p align="center"><img src="'.$image_url.'" /> </p><br/>';
		savePicture($image_url);
	}
}

function savePicture($image_url){
	$filename = basename($image_url);
	
	
	$destination = imageDirectory.$filename;
	file_put_contents($destination, file_get_contents($image_url));
}

if($_GET['code']){
	$code = $_GET['code'];
	$url = "https://api.instagram.com/oauth/access_token";
	$access_token_settings = array(
			'client_id'                =>     clientID,
			'client_secret'            =>     clientSecret,
			'grant_type'               =>     'authorization_code',
			'redirect_uri'             =>     redirectURI,
			'code'                     =>     $code
	);
	$curl = curl_init($url);    									
	curl_setopt($curl,CURLOPT_POST,true);   						
	curl_setopt($curl,CURLOPT_POSTFIELDS,$access_token_settings);   
	curl_setopt($curl, CURLOPT_RETURNTRANSFER, 1);   				
	curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, false);   			
	$result = curl_exec($curl);   									
	curl_close($curl);   											

	$results = json_decode($result,true);
	
	$userName = $results['user']['username']; 
	$userID = getUserID($userName);
	printImages($userID);
	
}else{ ?>


<!DOCTYPE html>
<html>
<head>
<title>instagramDL</title>
<link href="main.css" rel="stylesheet" type="text/css">
<link href='http://fonts.googleapis.com/css?family=Open+Sans:300' rel=
    'stylesheet' type='text/css'>
</head>
<body>
<div id="dl" align="center">
	<a href="https://api.instagram.com/oauth/authorize/?client_id=<?php echo clientID; ?>&redirect_uri=<?php echo redirectURI; ?>&response_type=code">Log in to Instagram</a>
    <br>
    <br>
    <p> It's 100% Safe, I promise.</p>
    <p> This website works in tandem with the Instagram API, so when you click that button, it'll take you to Instagram. <br>
    This website never touches your data. We just receive your photos so you can save them easier!</p>
    <br>
    <br>
    <a href="http://gregjw.com">Back to gregjw.com</a>
</div>
</body>
</html>

<?

}  


?>