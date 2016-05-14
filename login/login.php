<?php

//This file presents login link

// define('FACEBOOK_SDK_V4_SRC_DIR', __DIR__ . '/facebook-sdk-v5/');
// require_once __DIR__ . '/facebook-sdk-v5/autoload.php';

// session_start();

// #create the facebook object
// $fb = new Facebook\Facebook([
//   'app_id' => '1540489982923433',
//   'app_secret' => '5349b9f24b1e6da1edbc94cc33b125e0',
//   'default_graph_version' => 'v2.5',
// ]);

// $helper = $fb->getRedirectLoginHelper();
// $permissions = ['email']; //optional

// //TODO need to hange login url
// //take user to app's authoriation screen
// //on approval, redirect them to url u specified via the call-back 
// $loginUrl = $helper->getLoginUrl('http://localhost:8080/login-callback.php', $permissions);

// echo '<a href="' . $loginUrl . '">Log in with Facebook!</a>';

// use some namespaces
use Facebook\FacebookSession;
use Facebook\FacebookRequest;
use Facebook\GraphObject;
use Facebook\GraphUser;

// used to return json-encoded data
$obj = new StdClass();
// default status: true (success)
$obj->status = true;

// the facebook token cookie is not set
if(!isset($_COOKIE['fb_token'])) {
    // logout
    // unset all the session's variables
    session_unset();

    $obj->status = false;
    $obj->message = 'Logout';
    die(json_encode($obj));
}

// I'm already logged in
if($_SESSION['user_id'] !== false) {
    $obj->message = 'Already logged in';
    die(json_encode($obj));
}

// load the facebook SDK (v.4.0)
// see https://github.com/facebook/facebook-php-sdk-v4

// yes, you need all of them.
require_once('facebook/FacebookSession.php');
require_once('facebook/FacebookRedirectLoginHelper.php');
require_once('facebook/FacebookRequest.php');
require_once('facebook/FacebookResponse.php');
require_once('facebook/FacebookSDKException.php');
require_once('facebook/FacebookRequestException.php');
require_once('facebook/FacebookOtherException.php');
require_once('facebook/FacebookAuthorizationException.php');
require_once('facebook/GraphObject.php');
require_once('facebook/GraphUser.php');
require_once('facebook/GraphSessionInfo.php');

FacebookSession::setDefaultApplication('1540489982923433', '5349b9f24b1e6da1edbc94cc33b125e0');

// bind the JavaScript SDK session token with the PHP SDK
$session = new FacebookSession($_COOKIE['fb_token']);

// in case someone manually changed the cookie
// or the session expired...
try {
    $session->validate();
} catch (FacebookRequestException $ex) {
    $obj->status = false;
    $obj->message = 'Invalid facebook session';
    $obj->more = $ex->getMessage();
    die(json_encode($obj));
} catch (\Exception $ex) {
    $obj->status = false;
    $obj->message = 'Graph API error';
    $obj->more = $ex->getMessage();
    die(json_encode($obj));
}

// session ok, retrieve data
try {
    $request = new FacebookRequest($session, 'GET', '/me');
    // this means: retrieve a GraphObject and cast it as a GraphUser (as /me returns a GraphUser object)
    $me = $request->execute()->getGraphObject(GraphUser::className());
} catch (FacebookRequestException $ex) {
    $obj->status = false;
    $obj->message = 'Facebook Request error';
    $obj->more = $ex->getMessage();
    die(json_encode($obj));
} catch (\Exception $ex) {
    $obj->status = false;
    $obj->message = 'Generic Facebook error';
    $obj->more = $ex->getMessage();
    die(json_encode($obj));
}

// setup the user object
$user = new StdClass();
$user->facebookId = $me->getId();
$user->name = $me->getName();
$user->firstName = $me->getFirstName();
$user->lastName = $me->getLastName();
$user->email = $me->getProperty('email');   // some properties don't have a specific method
$user->gender = $me->getProperty('gender'); // hint: var_dump($me)
$user->locale = $me->getProperty('locale');

// insert here the object in your database (insert on key exists update, for example)
// PDO, MySQLi, MongoClient...

// insert the user data in the session
// you can alternatively serialise the object and save it all in one $_SESSION variable
$_SESSION['user_id'] = $me->getId(); // the facebook user's id
$_SESSION['user_name'] = $user->name; // short of firstname lastname
$_SESSION['user_firstname'] = $user->firstName;
$_SESSION['user_lastname'] = $user->lastName;
$_SESSION['user_locale'] = $user->locale; // may be used to integrate a multi-language content system

$obj->message = 'Logged in';
echo json_encode($obj);

?>