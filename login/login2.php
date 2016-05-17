<?php
//another file to try a diff login implementation

	session_start();

	define('APP_ID', '1540489982923433');
	define('APP_SECRET', '5349b9f24b1e6da1edbc94cc33b125e0');
	
	//need to change
	define('REDIRECT_URL', 'https://www.google.ca/');
	
	define('FACEBOOK_SDK_V4_SRC_DIR', __DIR__ . '\facebook-php-sdk-v4\src\Facebook/');
	
	// After that block of code you will then need to link to all the SDK files that you need to use,
	//  I may be going overkill with the amount I have added ( you may not need all of these! )
	//   I have not tested fully to see if I dont require some of these. The you will want to initialize all the class namespaces which will be used:
	
require_once(realpath($_SERVER["DOCUMENT_ROOT"]) . '\vendor\facebook\php-sdk-v4\src\Facebook\autoload.php' );
	
require_once(realpath($_SERVER["DOCUMENT_ROOT"]) . '\vendor\facebook\php-sdk-v4\src\Facebook\FacebookSession.php' );

require_once(realpath($_SERVER["DOCUMENT_ROOT"]) . '\vendor\facebook\php-sdk-v4\src\Facebook\FacebookRedirectLoginHelper.php' );
require_once(realpath($_SERVER["DOCUMENT_ROOT"]) . '\vendor\facebook\php-sdk-v4\src\Facebook\FacebookRequest.php' );
require_once(realpath($_SERVER["DOCUMENT_ROOT"]) . '\vendor\facebook\php-sdk-v4\src\Facebook\FacebookResponse.php' );





require_once(realpath($_SERVER["DOCUMENT_ROOT"]) . '\vendor\facebook\php-sdk-v4\src\Facebook\GraphNodes\GraphObject.php' );
require_once(realpath($_SERVER["DOCUMENT_ROOT"]) . '\vendor\facebook\php-sdk-v4\src\Facebook\Authentication\AccessToken.php' );
//require_once(realpath($_SERVER["DOCUMENT_ROOT"]) . '\vendor\facebook\php-sdk-v4\src\Facebook\HttpClients\FacebookHttpable.php' );
require_once(realpath($_SERVER["DOCUMENT_ROOT"]) . '\vendor\facebook\php-sdk-v4\src\Facebook\HttpClients\FacebookCurlHttpClient.php' );
require_once(realpath($_SERVER["DOCUMENT_ROOT"]) . '\vendor\facebook\php-sdk-v4\src\Facebook\HttpClients\FacebookCurl.php' ); 

require_once(realpath($_SERVER["DOCUMENT_ROOT"]) . '\vendor\facebook\php-sdk-v4\src\Facebook\Exceptions\FacebookSDKException.php' );
require_once(realpath($_SERVER["DOCUMENT_ROOT"]) . '\vendor\facebook\php-sdk-v4\src\Facebook\Exceptions\FacebookRequestException.php' );
require_once(realpath($_SERVER["DOCUMENT_ROOT"]) . '\vendor\facebook\php-sdk-v4\src\Facebook\Exceptions\FacebookAuthorizationException.php' );
	

	use Facebook\FacebookSession;
	use Facebook\FacebookRedirectLoginHelper;
	use Facebook\FacebookRequest;
	use Facebook\FacebookResponse;
	
	use Facebook\FacebookSDKException;
	use Facebook\FacebookRequestException;
	
	
	use Facebook\FacebookAuthorizationException;
	use Facebook\GraphObject;
	use Facebook\Entities\AccessToken;
	//use Facebook\HttpClients\FacebookHttpable;
	use Facebook\HttpClients\FacebookCurlHttpClient;
	use Facebook\HttpClients\FacebookCurl;
	
FacebookSession::setDefaultApplication( APP_ID, APP_SECRET );

$helper = new FacebookRedirectLoginHelper( REDIRECT_URL );


try {

  $session = $helper->getSessionFromRedirect();
} catch( FacebookRequestException $ex ) {
  // When Facebook returns an error
} catch( Exception $ex ) {
  // When validation fails or other local issues
} 


if ( isset( $session ) ) {

  $request = new FacebookRequest( $session, 'GET', '/me' );
  $response = $request->execute();

  $graphObject = $response->getGraphObject();


echo "name =" . $graphObject->getProperty('name') . "<br>";
echo "email =" . $graphObject->getProperty('email') . "<br>";
echo "first_name =" . $graphObject->getProperty('first_name') . "<br>";
echo "last_name =" . $graphObject->getProperty('last_name') . "<br>";
echo "link =" . $graphObject->getProperty('link') . "<br>";
echo "image =" . $graphObject->getProperty('image') . "<br>";

echo '<img src="https://graph.facebook.com/'. $graphObject->getProperty('id') .'/picture?type=square" />';

echo json_encode($graphObject);

} else {

  echo '<a href="' . $helper->getLoginUrl( array('scope' => ' email,
                                                              user_birthday,
                                                              user_location,
                                                              user_work_history,
                                                              user_about_me,
                                                              user_hometown
                                                              ')) . '">Login</a>';
}

?>