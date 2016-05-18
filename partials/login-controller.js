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
    
    /**
     * Retreives login status:
     * 
     * 1. logged into app ; 'connected'
     * 2. logged into fb but not app; 'not_authorized'
     * 3. not logged into fb and not sure if logged into app; unknown
     */
     FB.getLoginStatus(function (response) {
        loginStatusChangeCallback(response);
    });
    
    FB.Event.subscribe('auth.login', login_event);
FB.Event.subscribe('auth.logout', logout_event);

};

// In your JavaScript code:
var login_event = function(response) {

  
   if (response.status === 'connected') {
       // Logged into your app and Facebook.
  console.log("login_event");
  
  console.log(response);
  window.location="/partials/main-page.html";
  
  }
  
}

var logout_event = function(response) {
    if (response.status === 'unknown') {
       console.log("logout_event");
  
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
     * reponse is a status field that lets app know
     * current login status of the person.
     */
     
    if (response.status === 'connected') {
    // Logged into your app and Facebook.
    
        //testAPI();
  } else if (response.status === 'not_authorized') {
    // The person is logged into Facebook, but not your app.
  } else {
    // The person is not logged into Facebook, so we're not sure if
    // they are logged into this app or not.
    
    //window.location="/index.html";
  }
}	

/*
Called when someone finishes wit the login button
*/
function checkLoginState() {    

    FB.getLoginStatus(function (response) {
        loginStatusChangeCallback(response);
    });
}

/**
 * Runs a test of Graph API after login is successful.
 */
function testAPI(){
   
   FB.api('/me', function(response){
       
       //console.log('Successful login for: ' + response.name);
        
        //window.location="/partials/main-page.html";
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