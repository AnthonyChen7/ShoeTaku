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

};

/*
Fired everytime user changes fb login status
Call with results from FB.getLoginStatus()
*/
function loginStatusChangeCallback(response) {
    console.log('in loginStatusChangeCallback');
    
    console.log("response is " + response);
    
    /**
     * reponse is a status field that lets app know
     * current login status of the person.
     * 
     * 
     */
    
    if(response.status === 'connected'){
        //Person is logged into FB but not app
        document.getElementById('status').innerHTML = 'Please log ' +'into this app.';
        testAPI();
    }else{
        /**
         * Person is not logged into FB, so we r not sure
         * if they r logged into this app or not
         */
        document.getElementById('status').innerHTML = 'Please log ' +
        'into Facebook.';
    }
}	

/*
Called when someone finishes wit the login button
*/
function checkLoginState() {    
    console.log("in checkLoginState");
    
    FB.getLoginStatus(function (response) {
        loginStatusChangeCallback(response);
    });
}

/**
 * Runs a test of Graph API after login is successful.
 */
function testAPI(){
   console.log("Welcome! Fetching your information....");
   
   FB.api('/me', function(response){
       console.log('Successful login for: ' + response.name);
       document.getElementById('status').innerHTML =
        'Thanks for logging in, ' + response.name +
        +response.email + '!';
        
        //window.location="/partials/loginSuccess.html";
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