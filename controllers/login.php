<?php

//This file presents login link

define('FACEBOOK_SDK_V4_SRC_DIR', __DIR__ . '/facebook-sdk-v5/');
require_once __DIR__ . '/facebook-sdk-v5/autoload.php';

session_start();

#create the facebook object
$fb = new Facebook\Facebook([
  'app_id' => '1540489982923433',
  'app_secret' => '5349b9f24b1e6da1edbc94cc33b125e0',
  'default_graph_version' => 'v2.5',
]);

$helper = $fb->getRedirectLoginHelper();
$permissions = ['email']; //optional

//TODO need to hange login url
//take user to app's authoriation screen
//on approval, redirect them to url u specified via the call-back 
$loginUrl = $helper->getLoginUrl('http://localhost:8080/login-callback.php', $permissions);

echo '<a href="' . $loginUrl . '">Log in with Facebook!</a>';

?>