$('document').ready(function(){
	console.log("we made it");
	var pathname = window.location.pathname; // Returns path only
	var url      = window.location.href;     // Returns full URL
	console.log("pathname is " + pathname)
	console.log("url is " + url)

	var postId = url.substring(url.length-1,url.length);

	console.log(postId);

	// make ajax call and bring out all the info

});