<?php
session_start();
require_once "../config.php";
require_once "./functions.php";
$dbcon = dbConnect($config);
if ($dbcon){
	//$isActiv = isSessionActive($dbcon);
    if (!($isActiv = isSessionActive($dbcon))){
	sessionExit();
	goBack();
	}
}

/*if (!$isActiv){
	header("Location: ./signin.php");
	//redirect
}*/