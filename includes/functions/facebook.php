<?php
	if(!isset($_SESSION)) 
    { 
        session_start(); 
    }
	include('includes/functions/connect.php');

    require_once 'includes/Api/Facebook/autoload.php';

    $FB = new  \Facebook\Facebook([
        'app_id' => 'Add-your-id' ,
        'app_secret' => 'Add-your-appsecretKey' , 
        'default_graph_version' => 'v2.10' 
    ]);

    $helper = $FB->getRedirectLoginHelper();


?>
