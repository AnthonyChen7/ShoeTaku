<html>
<head>

    <script src="/components/jquery/jquery-2.2.3.js" type="text/javascript" charset="utf-8"></script>
    <link href="/components/bootstrap-3.3.6-dist 2/css/bootstrap.min.css" rel="stylesheet">
    <link href="/components/bootstrap-3.3.6-dist 2/css/bootstrap-theme.min.css" rel="stylesheet">
    <link href="/components/layout.css" rel="stylesheet">
    <script src="/components/bootstrap-3.3.6-dist 2/js/bootstrap.min.js" type="text/javascript" charset="utf-8"></script>
    <link rel="stylesheet" href="/components/font-awesome-4.6.3/css/font-awesome.min.css">

	<script src="/partials/fblogin-controller.js" type="text/javascript" charset="utf-8"></script>
    <script src="/partials/nonFBlogin-controller.js" type="text/javascript" charset="utf-8"></script>
    <script src="/partials/authentication.js" type="text/javascript" charset="utf-8"></script>

    <title>ShoeTaku</title>

	<style>
		#listofPages div { 
			display: inline-block;
			margin-left: 5px;
			margin-right: 5px;
		}
	</style>

</head>
<body> 
	<nav class="navbar navbar-default">
	    <div class="container-fluid">

	        <!-- Brand and toggle get grouped for better mobile display -->
	        <div class="navbar-header">
	            <button type="button" class="navbar-toggle collapsed" data-toggle="collapse" data-target="#bs-example-navbar-collapse-1" aria-expanded="false">
	                <span class="sr-only">Toggle navigation</span>
	                <span class="icon-bar"></span>
	                <span class="icon-bar"></span>
	                <span class="icon-bar"></span>
	            </button>
	            <a id="mainDashBoard" class="navbar-brand" href="dashboard.html">ShoeTaku</a>
	        </div>

	        <!-- Collect the nav links, forms, and other content for toggling -->
	        <div class="collapse navbar-collapse" id="bs-example-navbar-collapse-1">
	            <ul class="nav navbar-nav">
	                <li><a id="sellPage" class="page" data-page="sell" href="sell.html">Sell <span class="sr-only">(current)</span></a></li>
	                <li><a id="wantedPage" class="page" data-page="wanted" href="wanted.html">Wanted</a></li>
	                <li><a id="accountPage" class="page" data-page="account" href="account.html">Account Settings</a></li>
	            </ul>

	            <ul class="nav navbar-nav navbar-right">
	                <li><a id="logout">Logout</a></li>
	            </ul>
	        </div> <!-- /.navbar-collapse -->
	    </div><!-- /.container-fluid -->

	</nav>


<div id="postInfo">
	<div>
		<?php
			echo $_GET["id"];
		?>
		price size condition date-created goes here
	</div>
	<div>
		pictures go here (3 max)
	</div>
	<div>
		description goes here
	</div>
	<div>
		<div id="likeButton">LikeButton comes here</div>
		<div id="messageOwner">Message Owner comes here</div>
	</div>
</div>
<script src="/partials/sellPost-controller.js" type="text/javascript" charset="utf-8"></script>
</body>
</html>