<?php 

set_time_limit(0);
ini_set('default_socket_timeout', 300);
session_start();
    
/*---------- INSTAGRAM API KEYS ------------*/

define('clientID', 'fd56a9ca4b334360b5a91e9af9715c38');
define('clientSecret', 'a759d78e8ba540f99df66f5999d71592');
define('redirectURI', 'http://gregjw.com/instagramdl/index.php');
define('imageDirectory','archive/');

if($_GET['code'])
{
    $code = $_GET['code'];
    $url  = "https://api.instagram.com/oauth/access_token";
    $access_token_setting = array{
        'client_id'     =>      clientID,
        'client_secret' =>      clientSecret,
        'grant_type'    =>      'authorization_code',
        'redirect_uri'  =>      redirectURI,
        'code'          =>      $code
    };
        
    $curl = curl_init($url);
    curl_setopt($curl, CURLOPT_POST, true);
    curl_setopt($curl, CURLOPT_POSTFIELDS, $access_token_setting);
    curl_setopt($curl, CURLOPT_RETURNTRANSFER, 1);
    curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, false);
    
    $result = curl_exec($curl);
    
} else { ?>
    // Logged Out

<!DOCTYPE html>
<html>
<body>
    <a href="https://api.instagram.com/oauth/authorize/?client_id=<?php echo clientID; ?>&redirect_uri=<?php echo redirectURI; ?>&response_type=code">Login</a>
</body>
</html>

<?php

       }

?>