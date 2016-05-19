//https://developers.facebook.com/docs/facebook-login/web

//note need to make sure app id is private
//set up fb sdk for javascript
window.fbAsyncInit = function () {
    "use strict";

    FB.init({
        appId      : '1540489982923433',
        cookie     : true, //allow server to access session
        xfbml      : true, //parse social plugin on page
        version    : 'v2.0'
    });
    

checkLoginState(); 

/**
 * Set up success login/login event
 */
FB.Event.subscribe('auth.login', login_event);
FB.Event.subscribe('auth.logout', logout_event);

};

/**
 * On successful login, redirect user to main page
 */
var login_event = function(response) {
  
  if (response.status === 'connected') {
  console.log(response);
  window.location="/partials/main-page.html";
  }
  
}

/**
 * On successful logout, redirect user to login page
 */
var logout_event = function(response) {
    
    if (response.status === 'unknown') {
        console.log(response); 
        window.location="/index.html";
    }
  
}

/*
Fired everytime user changes fb login status
Call with results from FB.getLoginStatus()
*/
function loginStatusChangeCallback(response) {    
    /**
     * Retreives login status:
     * 
     * 1. logged into app ; 'connected'
     * 2. logged into fb but not app; 'not_authorized'
     * 3. not logged into fb and not sure if logged into app; unknown
     */
     
    if (response.status === 'connected') {
    // Logged into your app and Facebook.
    
    //Ensures page will not attempt to keep re-loading     
      if(window.location.href.indexOf("/partials/main-page.html") <= -1) {
       window.location="/partials/main-page.html";
    }     
  } else if (response.status === 'not_authorized') {
    // The person is logged into Facebook, but not your app.
  } else {
    // The person is not logged into Facebook, so we're not sure if
    // they are logged into this app or not.
    
     //Ensures page will not attempt to keep re-loading   
    if(window.location.href.indexOf("/index.html") <= -1) {
       window.location="/index.html";
    }   
  }
}	

/*
Called when someone finishes with the login button
*/
function checkLoginState() {    

    FB.getLoginStatus(function(response) {
      loginStatusChangeCallback(response);
    });
}

//set up fb sdk for javascript
//loads sdk asynchronously
(function (d, s, id) {
    "use strict";

    var js, fjs = d.getElementsByTagName(s)[0];
    if (d.getElementById(id)) {
        return;
    }
    js = d.createElement(s);
    js.id = id;
    js.src = "//connect.facebook.net/en_US/sdk.js";
    fjs.parentNode.insertBefore(js, fjs);
}(document, 'script', 'facebook-jssdk'));