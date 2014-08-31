<?php 

set_time_limit(0);
ini_set('default_socket_timeout', 300);
session_start();
    
/*---------- INSTAGRAM API KEYS ------------*/

define('clientID', 'fd56a9ca4b334360b5a91e9af9715c38');
define('clientSecret', 'a759d78e8ba540f99df66f5999d71592');
define('redirectURI', 'http://gregjw.com/instagramdl/index.php');
define('imageDirectory','archive/');

?>

<!DOCTYPE html>
<html>
<body>
    <a href="https://api.instagram.com/oauth/authorize/?client_id=<?php echo clientID; ?>&redirect_uri=<?php echo redirectURI; ?>&response_type=code">Login</a>
</body>
</html>