<?php
$root = "..";
$html = $root."/partials/error.html";
if (isset($_POST["page"])) {
	$page = $_POST["page"];
	$html = $root."/partials/".$page.".html";
}

if (file_exists($html)) {
	readfile($html);
}else{
	readfile($html);
}

?>