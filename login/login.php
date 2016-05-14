<?php

//This file is called when AJAX call is made

// use some namespaces
/*
-Namespace allows u to solve name collision between codes
-group related classes,interfaces, functions and constants together

-Namespacing does for functions and classes what scope does for variables. 
It allows you to use the same function or class name in different parts of the same program without causing a name collision.

http://stackoverflow.com/questions/3384204/what-are-namespaces

A namespace allows you to place a bunch of code under a name and not have any naming conflicts with classes, functions and constants.

It allows your code to live in that namespace.

PHP uses the somewhat controversial character \ to show namespace levels. People got up in arms because it is also used as an escape character.

http://daylerees.com/php-namespaces-explained/
*/
use Facebook\FacebookSession;
use Facebook\FacebookRequest;
use Facebook\GraphObject;
use Facebook\GraphUser;

// used to return json-encoded data
/*
stdClass is PHP's generic empty class, kind of like Object in Java or object in Python
It is useful for anonymous objects, dynamic properties, etc.
*/
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

//TODO

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